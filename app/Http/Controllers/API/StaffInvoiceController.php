<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffFeeMasters;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StaffInvoiceController extends Controller
{
    public function getAllStaffFees()
    {
        try {
            // Get latest due amounts from transactions
            $latestDues = DB::table("staff_transactions as st")
                ->select("st.staff_id", "st.due_amount")
                ->whereIn("st.id", function ($query) {
                    $query
                        ->selectRaw("MAX(id)")
                        ->from("staff_transactions")
                        ->groupBy("staff_id");
                })
                ->get()
                ->keyBy("staff_id"); // Index by staff_id for quick lookup

            // Fetch latest active invoices
            $staffFees = DB::table("staff_invoices as si")
                ->join("users as u", "si.created_by", "=", "u.id")
                ->leftJoin(
                    "staff_invoice_details as sid",
                    "si.id",
                    "=",
                    "sid.invoice_id"
                )
                ->leftJoin(
                    "staff_payments as sp",
                    "si.id",
                    "=",
                    "sp.invoice_id"
                )
                ->select(
                    "si.id as invoice_id",
                    "si.invoice_no",
                    "si.total_amount as invoice_total",
                    "si.status as invoice_status",
                    "si.created_by as staff_id",
                    "u.name as staff_name",
                    // DB::raw('JSON_ARRAYAGG(
                    //     JSON_OBJECT(
                    //         "fee_id", sid.id,
                    //         "fees_type", sid.fees_type,
                    //         "amount", sid.amount,
                    //         "created_at", sid.created_at,
                    //         "updated_at", sid.updated_at
                    //     )
                    // ) as fee_details'),
                    DB::raw("COALESCE(SUM(sp.amount), 0) as total_paid"),
                    DB::raw(
                        "(COALESCE(si.total_amount, 0) - COALESCE(SUM(sp.amount), 0)) as due_amount"
                    )
                )
                ->whereIn("si.id", function ($query) {
                    $query
                        ->selectRaw("MAX(id)")
                        ->from("staff_invoices")
                        ->groupBy("created_by");
                })
                ->where("si.status", "!=", "disabled")
                ->groupBy(
                    "si.id",
                    "si.invoice_no",
                    "si.total_amount",
                    "si.status",
                    "si.created_by",
                    "u.name"
                )
                ->get();

            // Fetch all past invoices per staff
            $pastInvoices = DB::table("staff_invoices")
                ->select(
                    "created_by as staff_id",
                    DB::raw("GROUP_CONCAT(invoice_no) as past_invoices")
                )
                ->groupBy("created_by")
                ->get()
                ->keyBy("staff_id");

            // Fetch all receipt numbers per invoice
            $receiptNumbers = DB::table("staff_payments")
                ->select(
                    "invoice_id",
                    DB::raw("GROUP_CONCAT(transaction_no) as receipt_numbers")
                )
                ->groupBy("invoice_id")
                ->get()
                ->keyBy("invoice_id"); // Index by invoice_id for easy lookup

            // Merge latest dues, past invoices, and receipt numbers into staffFees
            // $staffFees->transform(function ($invoice) use ($latestDues, $pastInvoices, $receiptNumbers) {
            //     // $invoice->fee_details = json_decode($invoice->fee_details, true);
            //     $invoice->latest_due = isset($latestDues[$invoice->staff_id])
            //         ? $latestDues[$invoice->staff_id]->due_amount
            //         : 0; // Default to 0 if no record found

            //     $invoice->past_invoices = isset($pastInvoices[$invoice->staff_id])
            //         ? json_decode($pastInvoices[$invoice->staff_id]->past_invoices, true)
            //         : [];

            //     $invoice->receipt_numbers = isset($receiptNumbers[$invoice->invoice_id])
            //         ? json_decode($receiptNumbers[$invoice->invoice_id]->receipt_numbers, true)
            //         : [];

            //     return $invoice;
            // });
            $allOldInvoiceNos = collect($pastInvoices)
                ->flatMap(function ($item) {
                    return json_decode($item->past_invoices ?? "[]", true);
                })
                ->unique()
                ->values()
                ->toArray();

            $invoiceMap = DB::table("staff_invoices")
                ->whereIn("invoice_no", $allOldInvoiceNos)
                ->pluck("id", "invoice_no"); // invoice_no => id

            $oldReceipts = DB::table("staff_payments")
                ->whereIn("invoice_id", $invoiceMap->values())
                ->select("invoice_id", "transaction_no")
                ->get()
                ->groupBy("invoice_id");

            $staffFees->transform(function ($invoice) use (
                $latestDues,
                $pastInvoices,
                $receiptNumbers,
                $invoiceMap,
                $oldReceipts
            ) {
                $invoice->latest_due =
                    $latestDues[$invoice->staff_id]->due_amount ?? 0;
                $invoice->receipt_numbers = json_decode(
                    $receiptNumbers[$invoice->invoice_id]->receipt_numbers ??
                        "[]",
                    true
                );

                $past = isset($pastInvoices[$invoice->staff_id])
                    ? json_decode(
                        $pastInvoices[$invoice->staff_id]->past_invoices ??
                            "[]",
                        true
                    )
                    : [];

                $invoice->past_receipt_numbers = collect($past)
                    ->map(function ($invoiceNo) use (
                        $invoiceMap,
                        $oldReceipts
                    ) {
                        $invoiceId = $invoiceMap[$invoiceNo] ?? null;
                        if (!$invoiceId || !isset($oldReceipts[$invoiceId])) {
                            return null;
                        }

                        return [
                            "invoice_no" => $invoiceNo,
                            "receipt_numbers" => $oldReceipts[$invoiceId]
                                ->pluck("transaction_no")
                                ->toArray(),
                        ];
                    })
                    ->filter()
                    ->values();

                return $invoice;
            });

            return response()->json(["data" => $staffFees], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getReceiptDetails($receiptNo)
    {
        try {
            // Fetch receipt details with invoice
            $receipt = DB::table("staff_payments as sp")
                ->join("staff_invoices as si", "sp.invoice_id", "=", "si.id")
                ->join("users as u", "si.created_by", "=", "u.id")
                ->select(
                    "sp.transaction_no",
                    "sp.amount as receipt_amount",
                    "sp.invoice_id", // ✅ Fix: Added invoice_id
                    "sp.created_at as payment_date",
                    "si.invoice_no",
                    "si.total_amount as invoice_total",
                    "si.status as invoice_status",
                    "si.created_by as staff_id",
                    "u.name as staff_name",
                    "u.email as staff_email",
                    "u.roll_no as staff_roll_no",
                    "u.created_at as staff_created_at",
                    "u.updated_at as staff_updated_at"
                )
                ->where("sp.transaction_no", $receiptNo) // ✅ Corrected condition
                ->first();

            if (!$receipt) {
                return response()->json(["error" => "Receipt not found"], 404);
            }

            // ✅ Fetch invoice fee details correctly
            $feeDetails = DB::table("staff_invoice_details")
                ->where("invoice_id", $receipt->invoice_id) // ✅ Now invoice_id is available
                ->get();

            // ✅ Fetch user_master details from staff table if available
            $userMaster = DB::table("staff")
                ->where("staff_id", $receipt->staff_roll_no)
                ->first();

            $receipt->fee_details = $feeDetails;
            $receipt->user_master = $userMaster;

            return response()->json(["data" => $receipt], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function getInvoiceDetails($invoiceNo)
{
    try {
        // Fetch invoice details
        $invoice = DB::table("staff_invoices as si")
            ->join("users as u", "si.created_by", "=", "u.id")
            ->select(
                "si.id as invoice_id",
                "si.invoice_no",
                "si.total_amount as invoice_total",
                "si.status as invoice_status",
                "si.created_by as staff_id",
                "u.name as staff_name",
                "u.email as staff_email",
                "u.roll_no as staff_roll_no",
                "u.created_at as staff_created_at",
                "u.updated_at as staff_updated_at"
            )
            ->where("si.invoice_no", $invoiceNo)
            ->first();

        if (!$invoice) {
            return response()->json(["error" => "Invoice not found"], 404);
        }

        // Manually fetch fee details
        $feeDetails = DB::table("staff_invoice_details")
            ->where("invoice_id", $invoice->invoice_id)
            ->get();

        // Fetch user_master details from staff table if available
        $userMaster = DB::table("staff")
            ->where("staff_id", $invoice->staff_roll_no)
            ->first();

        $invoice->fee_details = $feeDetails;
        $invoice->user_master = $userMaster;

        return response()->json(["data" => $invoice], 200);
    } catch (\Exception $e) {
        return response()->json(["error" => $e->getMessage()], 500);
    }
}

    public function createInvoices(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get staff IDs from request
            $staffIds = $request->input("staff_ids");

            // Fetch pending fees for given staff
            $query = DB::table("staff_fees_mapping")->where(
                "status",
                "pending"
            );

            if (!empty($staffIds)) {
                $staffIdsArray = is_array($staffIds) ? $staffIds : [$staffIds];
                $query->whereIn("staff_id", $staffIdsArray);
            }

            $pendingFees = $query->get();

            if ($pendingFees->isEmpty()) {
                return response()->json(
                    ["message" => "No pending fees found"],
                    404
                );
            }

            $staffInvoices = [];

            foreach ($pendingFees->groupBy("staff_id") as $staffId => $fees) {
                $totalAmount = $fees->sum("amount");

                // Disable all previous invoices except the most recent one
                DB::table("staff_invoices as si")
                    ->where("si.created_by", $staffId)
                    ->whereNotIn("si.id", function ($subQuery) use ($staffId) {
                        $subQuery
                            ->selectRaw("MAX(id)")
                            ->from("staff_invoices")
                            ->where("created_by", $staffId);
                    })
                    ->update(["status" => "disabled"]);

                // 🔹 Generate Invoice Number (STFDDMMYYSSXXXX)
                $datePart = now()->format("dmYs"); // ddmmyy + seconds
                $lastInvoice = DB::table("staff_invoices")
                    ->where("invoice_no", "LIKE", "STF{$datePart}%")
                    ->orderBy("invoice_no", "desc")
                    ->value("invoice_no");

                // Extract last sequence and increment
                $nextSequence = $lastInvoice
                    ? intval(substr($lastInvoice, -4)) + 1
                    : 1;
                $invoiceNo =
                    "STF{$datePart}" .
                    str_pad($nextSequence, 4, "0", STR_PAD_LEFT);

                // 🔹 Calculate Dues
                $totalPaid = DB::table("staff_payments")
                    ->whereIn("invoice_id", function ($query) use ($staffId) {
                        $query
                            ->select("id")
                            ->from("staff_invoices")
                            ->where("created_by", $staffId);
                    })
                    ->sum("amount");

                $totalInvoices = DB::table("staff_invoices")
                    ->where("created_by", $staffId)
                    ->sum("total_amount");

                $previousDue = max($totalInvoices - $totalPaid, 0);
                $newDue = $previousDue + $totalAmount;

                // Insert into invoices table
                $invoiceId = DB::table("staff_invoices")->insertGetId([
                    "invoice_no" => $invoiceNo,
                    "total_amount" => $totalAmount,
                    "due_amount" => $newDue,
                    "status" => "pending",
                    "created_by" => $staffId,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                // Insert invoice details
                foreach ($fees as $fee) {
                    DB::table("staff_invoice_details")->insert([
                        "invoice_id" => $invoiceId,
                        "fees_type" => $fee->fees_type,
                        "amount" => $fee->amount,
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);

                    // Update mapping table status to "generated"
                    DB::table("staff_fees_mapping")
                        ->where("id", $fee->id)
                        ->update(["status" => "invoice_generated"]);
                }

                // Record transaction
                DB::table("staff_transactions")->insert([
                    "staff_id" => $staffId,
                    "transaction_date" => now(),
                    "transaction_no" => $invoiceNo,
                    "transaction_type" => "INVOICE",
                    "invoice_amount" => $totalAmount,
                    "receipt_amount" => 0,
                    "due_amount" => $newDue,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                $staffInvoices[$staffId] = $invoiceNo;
            }

            DB::commit();
            return response()->json(
                [
                    "message" => "Invoices generated successfully",
                    "invoice_nos" => $staffInvoices,
                ],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function deleteStaffInvoiceByInvoiceNo($invoiceNo)
    {
        try {
            DB::beginTransaction();

            // Fetch the invoice using invoice_no
            $invoice = DB::table("staff_invoices")
                ->where("invoice_no", $invoiceNo)
                ->first();

            if (!$invoice) {
                return response()->json(
                    ["message" => "Invoice not found"],
                    404
                );
            }

            // Delete all related staff payments
            DB::table("staff_payments")
                ->where("invoice_id", $invoice->id)
                ->delete();

            // Delete all related invoice details
            DB::table("staff_invoice_details")
                ->where("invoice_id", $invoice->id)
                ->delete();

            // Delete corresponding transaction
            DB::table("staff_transactions")
                ->where("transaction_no", $invoiceNo)
                ->where("staff_id", $invoice->created_by)
                ->where("transaction_type", "INVOICE")
                ->delete();

            // Delete the invoice
            DB::table("staff_invoices")
                ->where("id", $invoice->id)
                ->delete();

            DB::commit();
            return response()->json(
                [
                    "message" =>
                        "Invoice and related records deleted successfully.",
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // public function createInvoices(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Check if staff_id(s) is provided
    //         $staffIds = $request->input('staff_ids'); // Can be single ID or array

    //         $query = DB::table('staff_fees_mapping')->where('status', 'pending');

    //         if (!empty($staffIds)) {
    //             $staffIdsArray = is_array($staffIds) ? $staffIds : [$staffIds]; // Convert to array if single ID
    //             $query->whereIn('staff_id', $staffIdsArray);
    //         }

    //         $pendingFees = $query->get();

    //         if ($pendingFees->isEmpty()) {
    //             return response()->json(['message' => 'No pending fees found'], 404);
    //         }

    //         $staffInvoices = [];

    //         foreach ($pendingFees->groupBy('staff_id') as $staffId => $fees) {
    //             $totalAmount = $fees->sum('amount');

    //             // Insert invoice
    //             $invoiceId = DB::table('staff_invoices')->insertGetId([
    //                 'total_amount' => $totalAmount,
    //                 'status' => 'pending',
    //                 'created_by' => $staffId,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             // Insert invoice details
    //             foreach ($fees as $fee) {
    //                 DB::table('staff_invoice_details')->insert([
    //                     'invoice_id' => $invoiceId,
    //                     'fees_type' => $fee->fees_type,
    //                     'amount' => $fee->amount,
    //                     'created_at' => now(),
    //                     'updated_at' => now(),
    //                 ]);

    //                 // Update mapping table status
    //                 DB::table('staff_fees_mapping')
    //                     ->where('id', $fee->id)
    //                     ->update(['status' => 'invoice_generated']);
    //             }

    //             $staffInvoices[$staffId] = $invoiceId;
    //         }

    //         DB::commit();
    //         return response()->json([
    //             'message' => 'Invoices generated successfully',
    //             'invoice_ids' => $staffInvoices
    //         ], 201);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    // Store Staff Payment
    public function storePayment(Request $request)
    {
        try {
            DB::beginTransaction();

            $payments = $request->payments; // Array of payments

            if (empty($payments) || !is_array($payments)) {
                return response()->json(
                    ["error" => "Invalid payment data"],
                    400
                );
            }

            foreach ($payments as $payment) {
                $invoiceId = $payment["invoice_id"];
                $amountPaid = $payment["amount"];

                // Get staff ID from invoice
                $invoice = DB::table("staff_invoices")
                    ->where("id", $invoiceId)
                    ->first();
                if (!$invoice) {
                    return response()->json(
                        ["error" => "Invoice ID $invoiceId not found"],
                        404
                    );
                }
                $staffId = $invoice->created_by;

                // ✅ Generate a new sequential receipt number
                $lastReceipt = DB::table("staff_transactions")
                    ->whereDate("transaction_date", now()->toDateString())
                    ->where("transaction_no", "LIKE", "R" . date("dmY") . "%")
                    ->orderBy("transaction_no", "desc")
                    ->value("transaction_no");

                $newNumber = $lastReceipt
                    ? (int) substr($lastReceipt, -4) + 1
                    : 1;
                $receiptNo =
                    "R" .
                    date("dmY") .
                    str_pad($newNumber, 4, "0", STR_PAD_LEFT);

                // ✅ Get latest due amount from `staff_transactions`
                $latestDue =
                    DB::table("staff_transactions")
                        ->where("staff_id", $staffId)
                        ->orderBy("id", "desc")
                        ->value("due_amount") ?? 0;

                // ✅ Calculate new due amount
                $newDue = max($latestDue - $amountPaid, 0);

                // ✅ Insert the new payment as a transaction (RECEIPT)
                DB::table("staff_transactions")->insert([
                    "staff_id" => $staffId,
                    "transaction_date" => now(),
                    "transaction_no" => $receiptNo,
                    "transaction_type" => "RECEIPT",
                    "invoice_amount" => 0,
                    "receipt_amount" => $amountPaid,
                    "due_amount" => $newDue,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                // ✅ Insert the payment into `staff_payments`
                DB::table("staff_payments")->insert([
                    "transaction_no" => $receiptNo,
                    "invoice_id" => $invoiceId,
                    "amount" => $amountPaid,
                    "payment_mode" => $payment["payment_mode"] ?? null,
                    "paid_by" => $payment["paid_by"] ?? null,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                // ✅ Check if all dues are cleared from `staff_transactions`
                $totalDueLeft =
                    DB::table("staff_transactions")
                        ->where("staff_id", $staffId)
                        ->orderBy("id", "desc")
                        ->value("due_amount") ?? 0;

                if ($totalDueLeft == 0) {
                    DB::table("staff_invoices")
                        ->where("id", $invoiceId)
                        ->update(["status" => "paid"]);
                }
            }

            DB::commit();
            return response()->json(
                ["message" => "Payments recorded successfully"],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function deleteStaffFeeWithPayments($id)
    {
        try {
            DB::beginTransaction();

            // Check if the fee exists
            $staffFee = DB::table("staff_fees_mapping")
                ->where("id", $id)
                ->first();
            if (!$staffFee) {
                return response()->json(
                    ["error" => "Fee record not found"],
                    404
                );
            }

            // Fetch related invoice
            $invoice = DB::table("staff_invoices")
                ->where("created_by", $staffFee->staff_id)
                ->first();

            if ($invoice) {
                // Delete associated payments
                DB::table("staff_payments")
                    ->where("invoice_id", $invoice->id)
                    ->delete();

                // Delete the invoice
                DB::table("staff_invoices")
                    ->where("id", $invoice->id)
                    ->delete();
            }

            // Delete the staff fee mapping entry
            DB::table("staff_fees_mapping")
                ->where("id", $id)
                ->delete();

            DB::commit();
            return response()->json(
                [
                    "message" =>
                        "Staff fee and related payments deleted successfully",
                ],
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function storePaymentold(Request $request)
    {
        try {
            DB::beginTransaction();

            // Insert into staff_payments
            DB::table("staff_payments")->insert([
                "invoice_id" => $request->invoice_id,
                "amount" => $request->amount,
                "payment_mode" => $request->payment_mode,
                "paid_by" => $request->paid_by,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]);

            // Update invoice status if fully paid
            $invoice = DB::table("staff_invoices")
                ->where("id", $request->invoice_id)
                ->first();
            $totalPaid = DB::table("staff_payments")
                ->where("invoice_id", $request->invoice_id)
                ->sum("amount");

            if ($totalPaid >= $invoice->total_amount) {
                DB::table("staff_invoices")
                    ->where("id", $request->invoice_id)
                    ->update(["status" => "paid"]);
            }

            DB::commit();
            return response()->json(
                ["message" => "Payment recorded successfully"],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    // Get Invoice Details
    public function getInvoice($id)
    {
        try {
            $invoice = DB::table("staff_invoices")
                ->where("id", $id)
                ->first();
            $details = DB::table("staff_invoice_details")
                ->where("invoice_id", $id)
                ->get();
            // Fetch user details from the 'user' table without the password field
            $user = DB::table("users")
                ->where("id", $invoice->created_by)
                ->select(
                    "id",
                    "name",
                    "email",
                    "roll_no",
                    "created_at",
                    "updated_at"
                ) // Add required fields
                ->first();
            $payments = DB::table("staff_payments")->where("invoice_id", $id)->first();

            // Fetch user_master from 'staff' table using roll_no if user exists, otherwise set to null
            $userMaster = $user ? DB::table("staff")->where("staff_id", $user->roll_no)->first(): null;

            return response()->json(
                [
                    "invoice" => $invoice,
                    "details" => $details,
                    "payments" => $payments,
                    "user" => $user,
                    "user_master" => $userMaster,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    // mappingggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg

    public function listAllStaffmapFees()
    {
        try {
            $staffFees = DB::table("staff_fees_mapping")
                ->join("users", "staff_fees_mapping.staff_id", "=", "users.id")
                ->select(
                    "staff_fees_mapping.id",
                    "staff_fees_mapping.staff_id",
                    "users.name as staff_name",
                    "staff_fees_mapping.fees_type as feesType",
                    "staff_fees_mapping.amount",
                    "staff_fees_mapping.status",
                    "staff_fees_mapping.created_at",
                    "staff_fees_mapping.updated_at"
                )
                ->orderBy("staff_fees_mapping.created_at", "desc")
                ->get();

            return response()->json(["data" => $staffFees], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function mapStaffFees(Request $request)
    {
        try {
            DB::beginTransaction();

            $staffs = is_array($request->staffs) ? $request->staffs : [];
            $feesDetails = is_array($request->feesDetails)
                ? $request->feesDetails
                : [];

            if (empty($staffs) || empty($feesDetails)) {
                return response()->json(
                    ["error" => "Invalid staff or fee data"],
                    400
                );
            }

            foreach ($staffs as $staff) {
                foreach ($feesDetails as $fee) {
                    DB::table("staff_fees_mapping")->insert([
                        "staff_id" => $staff["id"],
                        "fees_type" => $fee["feesType"],
                        "amount" => $fee["amount"],
                        "status" => "pending",
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);
                }
            }

            DB::commit();
            return response()->json(
                ["message" => "Staff mapped to fees successfully"],
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function mapStaffFeesbyid(Request $request, $id)
    {
        try {
        $updated = DB::table("staff_fees_mapping")->where("id", $id)->first();
        return $updated;
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function updateStaffFees(Request $request, $id)
    {
        try {
            $id = $request->id;
            $data = $request->only(["feesType", "amount"]);

            if (empty($data["feesType"]) || empty($data["amount"])) {
                return response()->json(["error" => "Invalid data"], 400);
            }

            $updated = DB::table("staff_fees_mapping")
                ->where("id", $id)
                ->update([
                    "fees_type" => $data["feesType"],
                    "amount" => $data["amount"],
                    "updated_at" => now(),
                ]);

            if ($updated) {
                return response()->json(
                    ["message" => "Fee mapping updated successfully"],
                    200
                );
            }

            return response()->json(["error" => "Fee mapping not found"], 404);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function deletemapStaffFees(Request $request, $id)
    {
        try {
            $id = $request->id;
            $deleted = DB::table("staff_fees_mapping")
                ->where("id", $id)
                ->delete();

            if ($deleted) {
                return response()->json(
                    ["message" => "Fee mapping deleted successfully"],
                    200
                );
            }

            return response()->json(["error" => "Fee mapping not found"], 404);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    
}
