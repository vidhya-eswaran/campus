<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SponserMaster;
use App\Models\Invoice_list;
use App\Models\ByPayInformation;

use Illuminate\Support\Facades\Validator;
use App\Models\GenerateInvoiceView;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentOrdersDetails;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;

use App\Mail\InvoiceGenerated;

use Illuminate\Support\Facades\Log; // Add this import statement
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Else_;
use App\Models\PaymentOrdersStatuses;
use Carbon\Carbon;
use App\Helpers\FastInvoiceHelper;

class InvoiceController extends Controller
{
    //
    public function getInvoiceListshort(Request $request)
    {
        $requestData = $request->all();
        // $fees_cat = $requestData["inoviceTypes"];
        $user_id = $requestData["userId"];
        $user_type = $requestData["userType"];

        $student_list = [];
        if ($user_type == "sponser") {
            $student_records = User::select("roll_no")
                ->where("sponser_id", $user_id)
                ->where("status", "=", 1)
                ->get();
        } elseif ($user_type == "parent" || $user_type == "student") {
            $student_records = User::select("roll_no")
                ->where("id", $user_id)
                ->where("status", "=", 1)
                ->get();
        } elseif ($user_type == "admin") {
            $student_records = User::select("roll_no")
                ->where("status", "=", 1)
                ->get();
        }
        foreach ($student_records as $student_record) {
            if ($student_record->roll_no) {
                array_push($student_list, $student_record->roll_no);
            }
        }
        $invoiceLists = DB::table("generate_invoice_views")
            ->leftJoin("invoice_lists", function ($join) {
                $join
                    ->on(
                        "generate_invoice_views.slno",
                        "=",
                        "invoice_lists.invoice_id"
                    )
                    ->where(
                        "invoice_lists.transaction_completed_status",
                        "=",
                        1
                    );
            })
            ->whereIn("roll_no", $student_list)
            ->select(
                "generate_invoice_views.*",
                DB::raw(
                    "CAST(invoice_lists.payment_transaction_id AS CHAR) AS paymentTransactionId"
                )
            )
            ->orderBy("generate_invoice_views.slno", "desc") // Arrange in descending order based on slno
            ->distinct() // Use distinct to eliminate duplicate rows
            ->get();
        //     return response()->json(['data' => $invoiceLists], 200)->header("Access-Control-Allow-Origin",  "*");

        $data = [];
        $slnoSet = [];
        foreach ($invoiceLists as $invoice) {
            if (!in_array($invoice->slno, $slnoSet)) {
                $slnoSet[] = $invoice->slno;
                $data[] = [
                    "id" => $invoice->slno,
                    "invoiceNo" => $invoice->invoice_no,
                    "studentName" => $invoice->name,
                    "studentRegNo" => $invoice->roll_no,
                    "academicYear" => $invoice->acad_year,
                    "due_date" => date("d/M/Y", strtotime($invoice->due_date)),
                    "payStatus" => $invoice->payment_status,
                    "sponserId" => $invoice->slno,
                ];
            }
        }
        return response()
            ->json(["data" => $data], 200)
            ->header("Access-Control-Allow-Origin", "*");
    }

    public function getReceiptListshort(Request $request)
    {
        $requestData = $request->all();
        $userId = $requestData["userId"];
        $userType = $requestData["userType"];
        $user = User::where("id", $userId)->first();

        if (!$user) {
            return response()->json(["error" => "User not found"], 404);
        }

        // Initialize query
        $query = ByPayInformation::select(
            "transactionId",
            "amount",
            "created_at"
        );
        // dd($user);
        // Apply sponsor or student filter based on user type
        if ($user->user_type == "sponser") {
            $query->where("sponsor", $user->id);
        } else {
            $query->where("student_id", $user->id);
        }

        // Add condition to check for null or empty inv_amt
        $query->where(function ($q) {
            $q->whereNull("inv_amt")->orWhere("inv_amt", "");
        });

        // Execute the query and get the results
        $invoices = $query->get();

        return response()->json(["data" => $invoices]);
    }

    public function getInvoiceList(Request $request)
    {
        $requestData = $request->all();
        if (isset($requestData["invoiceId"])) {
            $invoiceNo = $requestData["invoiceId"];

            $invoiceLists = DB::table("generate_invoice_views")
                ->leftJoin("invoice_lists", function ($join) use ($invoiceNo) {
                    $join
                        ->on(
                            "generate_invoice_views.slno",
                            "=",
                            "invoice_lists.invoice_id"
                        )
                        ->where(
                            "invoice_lists.transaction_completed_status",
                            "=",
                            1
                        );
                })
                ->leftJoin("student_infos", function ($join) {
                    $join->on(
                        "generate_invoice_views.student_id",
                        "=",
                        "student_infos.id"
                    );
                })
                ->where("generate_invoice_views.invoice_no", "=", $invoiceNo)
                ->select(
                    "generate_invoice_views.*",
                    "generate_invoice_views.roll_no as student_roll_number",
                    "generate_invoice_views.sec as student_sec",
                    "generate_invoice_views.created_at as invoiceCreatedDate",
                    "invoice_lists.*",
                    "invoice_lists.created_at as paymentDate",
                    "student_infos.*"
                )
                ->first(); //get particular invoice Details

            $returnData = (object) [];
            $invoiceDetails = (object) [];

            if ($invoiceLists) {
                $invoiceDetails->studentName = $invoiceLists->name;
                // $invoiceDetails->grade = numberToRomanRepresentation($invoiceLists->standard);
                $invoiceDetails->grade = $invoiceLists->standard;
                $invoiceDetails->section = strtoupper(
                    $invoiceLists->student_sec
                );
                $invoiceDetails->rollNo = $invoiceLists->student_roll_number;
                $invoiceDetails->group = $invoiceLists->Group;
                $invoiceDetails->invoiceDate = date(
                    "d/m/Y",
                    strtotime(
                        str_replace("-", "/", $invoiceLists->invoiceCreatedDate)
                    )
                );
                $invoiceDetails->invoiceNo = $invoiceLists->invoice_no;
                $invoiceDetails->total = $invoiceLists->amount;
                $invoiceDetails->previousDues =
                    $invoiceLists->previous_pending_amount;
                $invoiceDetails->discount = $invoiceLists->discount_percent
                    ? $invoiceLists->discount_percent
                    : null;
                $invoiceDetails->amount = $invoiceLists->amount;
                $invoiceDetails->invoice_pending_amount =
                    $invoiceLists->invoice_pending_amount;
                $invoiceDetails->totalPayment =
                    $invoiceLists->total_invoice_amount;
                $returnData->invoiceDetails = $invoiceDetails;
                $returnData->paymentDetails = json_decode(
                    $invoiceLists->fees_items_details
                );
                // $returnData->discount_list = json_decode($invoiceLists->discount_items_details);
                if ($invoiceLists->fees_cat == "school") {
                    $returnData->discount_list = json_decode(
                        $invoiceLists->discount_items_details
                    );
                } elseif ($invoiceLists->fees_cat !== "school") {
                    $returnData->discount_list = json_decode(
                        $invoiceLists->discount_items_details
                    );
                } else {
                    $returnData->discount_list = null;
                }
            }

            $invoiceDetails = DB::table("generate_invoice_views")
                ->where("invoice_no", $invoiceNo)
                ->first();

            $bypayDetails = DB::table("by_pay_informations")
                ->where("type", $invoiceDetails->fees_cat)
                ->where("invoice_id", $invoiceDetails->slno)
                ->where(function ($query) {
                    $query
                        ->where("payment_status", "=", "")
                        ->orWhereNull("payment_status");
                })
                ->get();

            $latestbypayDetails = DB::table("by_pay_informations")
                ->where("type", $invoiceDetails->fees_cat)
                ->where("student_id", $invoiceDetails->student_id)
                ->where("invoice_id", "!=", $invoiceDetails->slno)
                ->latest("id")
                ->first();
            return response()
                ->json([
                    "data" => $returnData,
                    "bypayDetails" => $bypayDetails,
                    "latestbypayDetails" => $latestbypayDetails,
                    "invoiceDetails" => $invoiceDetails,
                ])
                ->header("Access-Control-Allow-Origin", "*");
        } else {
            $fees_cat = $requestData["inoviceTypes"];
            $user_id = $requestData["userId"];
            $user_type = $requestData["userType"];

            $student_list = [];
            if ($user_type == "sponser") {
                $student_records = User::select("roll_no")
                    ->where("sponser_id", $user_id)
                    ->where("status", "=", 1)
                    ->get();
            } elseif ($user_type == "parent" || $user_type == "student") {
                $student_records = User::select("roll_no")
                    ->where("id", $user_id)
                    ->where("status", "=", 1)
                    ->get();
            } elseif ($user_type == "admin") {
                $student_records = User::select("roll_no")
                    ->where("status", "=", 1)
                    ->get();
            }
            foreach ($student_records as $student_record) {
                if ($student_record->roll_no) {
                    array_push($student_list, $student_record->roll_no);
                }
            }

            // $invoiceLists = DB::table('generate_invoice_views')
            //     ->leftJoin('invoice_lists', function ($join) {
            //         $join->on('generate_invoice_views.slno', '=', 'invoice_lists.invoice_id')
            //             ->where('invoice_lists.transaction_completed_status', '=', 1);
            //     })
            //  //   ->where('generate_invoice_views.fees_cat', $fees_cat)
            //     ->whereIn('roll_no', $student_list)
            //     ->select(
            //         'generate_invoice_views.*',
            //         DB::raw("CAST(invoice_lists.payment_transaction_id AS CHAR) AS paymentTransactionId")
            //     )
            //     ->get(); //get all invoices
            $invoiceLists = DB::table("generate_invoice_views")
                ->leftJoin("invoice_lists", function ($join) {
                    $join
                        ->on(
                            "generate_invoice_views.slno",
                            "=",
                            "invoice_lists.invoice_id"
                        )
                        ->where(
                            "invoice_lists.transaction_completed_status",
                            "=",
                            1
                        );
                })
                ->whereIn("roll_no", $student_list)
                ->select(
                    "generate_invoice_views.*",
                    DB::raw(
                        "CAST(invoice_lists.payment_transaction_id AS CHAR) AS paymentTransactionId"
                    )
                )
                ->orderBy("generate_invoice_views.slno", "desc") // Arrange in descending order based on slno
                ->distinct() // Use distinct to eliminate duplicate rows
                ->get();

            //     return response()->json(['data' => $invoiceLists], 200)->header("Access-Control-Allow-Origin",  "*");

            $data = [];
            $slnoSet = [];
            foreach ($invoiceLists as $invoice) {
                // Check if slno already exists
                $paymentInformation = DB::table("by_pay_informations")
                    ->where("student_id", $invoice->student_id)
                    ->where("type", $invoice->fees_cat)
                    ->latest("id")
                    ->first();
                $most_recent_dues = $paymentInformation->due_amount;
                // $s_excess = $paymentInformation->s_excess_amount;
                // $h_excess = $paymentInformation->h_excess_amount;
                if (!in_array($invoice->slno, $slnoSet)) {
                    $slnoSet[] = $invoice->slno;
                    $data[] = [
                        "id" => $invoice->slno,
                        "invoiceNo" => $invoice->invoice_no,
                        "feesCat" => $invoice->fees_cat,
                        "paymentTransactionId" =>
                            $invoice->paymentTransactionId,
                        "studentName" => $invoice->name,
                        "studentRegNo" => $invoice->roll_no,
                        "academicYear" => $invoice->acad_year,
                        "due_date" => date(
                            "d/M/Y",
                            strtotime($invoice->due_date)
                        ),
                        "payStatus" => $invoice->payment_status,
                        "sponserId" => $invoice->slno,
                        "amount" => $invoice->amount,
                        "previous_pending_amount" =>
                            $invoice->previous_pending_amount,
                        // 'payableAmount' => $invoice->total_invoice_amount,
                        "paid" => $invoice->paid_amount,
                        "invoice_pending" => $invoice->invoice_pending_amount,
                        "payableAmount" => $most_recent_dues,
                        "dwonloadReceipt" =>
                            $invoice->payment_status == "Paid" ||
                            $invoice->payment_status == "Partial Paid"
                                ? true
                                : false,
                        "disable" => $invoice->disable,
                    ];
                }
            }
            $invoiceDetails = DB::table("generate_invoice_views")
                ->where("invoice_no", $invoice->invoice_no)
                ->first();

            $bypayDetails = DB::table("by_pay_informations")
                ->where("type", $invoiceDetails->fees_cat)
                ->where("invoice_id", $invoiceDetails->slno)
                ->where(function ($query) {
                    $query
                        ->where("payment_status", "=", "")
                        ->orWhereNull("payment_status");
                })
                ->get();

            $latestbypayDetails = DB::table("by_pay_informations")
                ->where("type", $invoiceDetails->fees_cat)
                ->where("student_id", $invoiceDetails->student_id)
                ->where("invoice_id", "!=", $invoiceDetails->slno)
                ->latest("id")
                ->first();

            return response()
                ->json(
                    [
                        "data" => $data,
                        "bypayDetails" => $bypayDetails,
                        "latestbypayDetails" => $latestbypayDetails,
                        "invoiceDetails" => $invoiceDetails,
                    ],
                    200
                )
                ->header("Access-Control-Allow-Origin", "*");
        }
    }
    public function getsponinfo()
    {
        $sponserorstudentDetails = DB::table("by_pay_informations")
            ->select(
                "id",
                "student_id",
                "invoice_id",
                "transactionId",
                "amount",
                "sponsor"
            )
            ->selectRaw(
                'CONVERT_TZ(created_at, "+00:00", "+05:30") as created_at'
            )
            ->where("sponsor", "!=", "")
            ->get();

        foreach ($sponserorstudentDetails as $sponserorstudentDetail) {
            // Retrieve user_id from sponser_masters
            $user = User::where(
                "id",
                $sponserorstudentDetail->sponsor
            )->first();
            $stu_user = User::where(
                "id",
                $sponserorstudentDetail->student_id
            )->first();

            // Set sponsor name if a matching record is found
            if ($user) {
                $sponserorstudentDetail->name = $user->name ?? "";
                $sponserorstudentDetail->stu_user = $stu_user->name ?? "";
                $sponserorstudentDetail->std = $stu_user->standard ?? "";
                $sponserorstudentDetail->rollNo = $stu_user->roll_no ?? "";
            } else {
                // If no matching record is found, set sponsor name to null
                $sponserorstudentDetail->name = null;
                $sponserorstudentDetail->stu_user = $stu_user->name ?? "";
                $sponserorstudentDetail->std = $stu_user->standard ?? "";
                $sponserorstudentDetail->rollNo = $stu_user->roll_no ?? "";
            }
        }

        return response()
            ->json(["sponserorstudentDetail" => $sponserorstudentDetails])
            ->header("Access-Control-Allow-Origin", "*");
    }
    public function getPaymentReceiptList(Request $request)
    {
        $requestData = $request->all();
        if (isset($requestData["slno"])) {
            $slNo = $requestData["slno"];
            $paidReceiptList = DB::table("generate_invoice_views")
                ->leftjoin(
                    "invoice_lists",
                    "invoice_lists.invoice_id",
                    "generate_invoice_views.slno"
                )
                //  ->where('invoice_lists.transaction_completed_status', 1)
                ->where("invoice_lists.status", "success")
                ->where("generate_invoice_views.slno", $slNo)
                ->select(
                    "generate_invoice_views.*",
                    "invoice_lists.payment_transaction_id"
                )
                ->get();
            foreach ($paidReceiptList as $sponsorOption) {
                $sponsorOption->payment_transaction_id =
                    (string) $sponsorOption->payment_transaction_id;
            }
            return response()->json(["data" => $paidReceiptList]);
        }
    }
    public function getPaymentReceipt(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData["transactionNo"])) {
            $transactionNo = $requestData["transactionNo"];
            $sponserorstudentDetails = DB::table("by_pay_informations")
                ->where("transactionId", $transactionNo)
                ->first();

            if (!empty($sponserorstudentDetails->sponsor)) {
                // Retrieve user_id from sponser_masters
                $sponsorDetails = DB::table("sponser_masters")
                    ->where("user_id", $sponserorstudentDetails->sponsor)
                    ->first();

                // Set sponsor to user_id if a matching record is found
                if ($sponsorDetails) {
                    $sponserorstudentDetails->sponsor_info = $sponsorDetails;
                } else {
                    // If no matching record is found, set sponsor to null
                    $sponserorstudentDetails->sponsor_info = null;
                }
            }
            // Get the latest receipt for the student and invoice
            $latestReceipt = ByPayInformation::where(
                "student_id",
                $sponserorstudentDetails->student_id
            )
                ->whereNull("inv_amt")
                //  ->where('invoice_id', $sponserorstudentDetails->invoice_id)
                ->orderBy("created_at", "desc")
                ->first();

            $disable_delete = null;
            if (
                $latestReceipt &&
                $latestReceipt->id == $sponserorstudentDetails->id
            ) {
                $disable_delete = false; // Enable the button
            } else {
                // The receipt cannot be deleted, disable the delete button
                $disable_delete = true; // Disable the button
            }

            $invoiceListsA = DB::table("invoice_lists")
                ->select(
                    "user_uuid",
                    "invoice_id",
                    "payment_transaction_id",
                    "unique_payment_transaction_id"
                )
                ->where("payment_transaction_id", $transactionNo)
                ->latest() // Sorts by the `created_at` column by default
                ->first();
            //  return response()->json(["data" => $invoiceListsA]);
            $transactionDetails = DB::table("payment_orders_statuses")
                ->leftjoin(
                    "payment_orders_details",
                    "payment_orders_details.internal_txn_id",
                    "payment_orders_statuses.clnt_txn_ref"
                )
                ->where("payment_orders_statuses.clnt_txn_ref", $transactionNo)
                ->orWhere(
                    "payment_orders_statuses.clnt_txn_ref",
                    $invoiceListsA->unique_payment_transaction_id
                )
                ->select(
                    "payment_orders_statuses.*",
                    "payment_orders_details.*",
                    "payment_orders_statuses.created_at as paymentDateTime",
                    DB::raw(
                        "CAST(payment_orders_details.internal_txn_id AS CHAR) AS internalTxnId"
                    )
                )
                ->first(); //get all invoices
            $by_pay_info = ByPayInformation::where(
                "transactionId",
                $transactionNo
            )->first();
            $Student_user = User::where(
                "id",
                $by_pay_info->student_id
            )->first();
            $paidinvoiceDetails = DB::table("generate_invoice_views")
                ->leftjoin(
                    "invoice_lists",
                    "invoice_lists.invoice_id",
                    "generate_invoice_views.slno",
                    "generate_invoice_views.name",
                    "generate_invoice_views.standard",
                    "generate_invoice_views.roll_no",
                    "generate_invoice_views.sponser_id"
                )
                ->where("invoice_lists.payment_transaction_id", $transactionNo)
                ->select("generate_invoice_views.*", "invoice_lists.*")
                ->get(); //get all invoices
            $transactionDetailsData = (object) [];
            if ($transactionDetails) {
                $transactionDetailsData->transactionNo =
                    $transactionDetails->internalTxnId;
                $transactionDetailsData->date = date(
                    "d/m/Y",
                    strtotime($transactionDetails->paymentDateTime)
                );
                $transactionDetailsData->time = date(
                    "h:i:sa",
                    strtotime($transactionDetails->paymentDateTime)
                );
                $transactionDetailsData->modeOfPayment =
                    $transactionDetails->paymentMode;
                $transactionDetailsData->paymentStatus =
                    $transactionDetails->txn_msg;
                $transactionDetailsData->paymentAmounttwo = number_format(
                    $transactionDetails->amount,
                    2,
                    ".",
                    ""
                );
                $transactionDetailsData->paymentAmount = $by_pay_info->amount; ////check in future
                $transactionDetailsData->ByPayInformation = $by_pay_info; ////check in future
                $transactionDetailsData->Student = Student::where(
                    "roll_no",
                    $Student_user->roll_no
                )
                    ->where("admission_no", $Student_user->admission_no)
                    ->first();

                //   return response()->json(["data" => $paidinvoiceDetails])->header("Access-Control-Allow-Origin",  "*");
                //   $transactionDetailsData->name = $paidinvoiceDetails['name'];
                //  $transactionDetailsData->standard = $paidinvoiceDetails['standard'];
                //   $transactionDetailsData->admission_no = $paidinvoiceDetails->admission_no;
                $transactionDetailsData->invoice = $paidinvoiceDetails;

                $transactionDetailsData->bankTransactionID =
                    $transactionDetails->BankTransactionID;
            }
            if ($transactionDetails) {
                $tuserdetails = User::where(
                    "id",
                    $transactionDetails->user_id
                )->first();
                if ($tuserdetails && $tuserdetails->user_type === "sponser") {
                    $sponsorDetails = DB::table("sponser_masters")
                        ->where("user_id", $transactionDetails->user_id)
                        ->first();
                    if ($sponsorDetails) {
                        $sponserDetailsponsor_info = $sponsorDetails;
                    }
                }
            }
            // else{
            //     date_default_timezone_set('Asia/Kolkata');
            //     $currentDate = date('d/m/Y');
            //     $currentTime = date('h:i:sa');
            //     $transactionDetailsData->modeOfPayment = 'cash';
            //     $transactionDetailsData->date = $currentDate;
            //     $transactionDetailsData->time = $currentTime;
            //      $transactionDetailsData->bankTransactionID = 'Offline payment';
            //     $transactionDetailsData->transactionNo = $requestData['transactionNo'];

            // }
            //SponserMaster
            $returnData = (object) [];
            $invoiceDetails = [];

            if ($paidinvoiceDetails) {
                foreach ($paidinvoiceDetails as $paidinvoiceDetail) {
                    $invoiceData = (object) [];
                    $invoiceData->invoiceNo = $paidinvoiceDetail->invoice_no;
                    $invoiceData->status = $paidinvoiceDetail->payment_status;
                    $invoiceData->amount = number_format(
                        $paidinvoiceDetail->total_invoice_amount,
                        2,
                        ".",
                        ""
                    );
                    $invoiceData->pending_amount = number_format(
                        $paidinvoiceDetail->invoice_pending_amount,
                        2,
                        ".",
                        ""
                    );
                    // $invoiceData->paidAmount = $paidinvoiceDetail->paid_amount;
                    $invoiceData->name = $paidinvoiceDetail->name;
                    $invoiceData->standard = $paidinvoiceDetail->standard;
                    $invoiceData->roll_no = $paidinvoiceDetail->roll_no;
                    $invoiceData->Sponsername =
                        User::where(
                            "id",
                            $paidinvoiceDetail->sponser_id
                        )->value("name") ?? "";
                    if (!is_null($paidinvoiceDetail->sponser_id)) {
                        $user = SponserMaster::where(
                            "user_id",
                            $paidinvoiceDetail->sponser_id
                        )->first();
                        $sponserName = $user ? $user->name : "";
                        $sponserCity = $user ? $user->city : "";
                        $sponserCompanyName = $user ? $user->company_name : "";
                        $sponserPincode = $user ? $user->pincode : "";
                        $sponserLocation = $user ? $user->location : "";

                        $invoiceData->address =
                            "<b>" .
                            $sponserName .
                            "</b>,<br>" .
                            $sponserCompanyName .
                            ", " .
                            $sponserCity .
                            ", " .
                            $sponserLocation .
                            ", " .
                            $sponserPincode;

                        $invoiceData->gst =
                            SponserMaster::where(
                                "user_id",
                                $paidinvoiceDetail->sponser_id
                            )->value("gst") ?? "";
                        $invoiceData->pan =
                            SponserMaster::where(
                                "user_id",
                                $paidinvoiceDetail->sponser_id
                            )->value("pan") ?? "";
                    }
                    array_push($invoiceDetails, $invoiceData);
                }
            }
            $transactionDetailsData->transactionNo =
                $transactionDetailsData->ByPayInformation->transactionId;
            //invoiceData.transactionDetails.transactionNo}    transactionDetails.ByPayInformation.transactionId
            $returnData->transactionDetails = $transactionDetailsData;
            $returnData->paidinvoiceDetails = $invoiceDetails;
            $returnData->sponserorstudentDetails =
                $sponserorstudentDetails ?? "";
            $returnData->disable_delete = $disable_delete ?? "";
            $returnData->sponserDetailsponsor_info =
                $sponserDetailsponsor_info ?? "";
            $returnData->sponserDetailsponsor_info =
                $sponserDetailsponsor_info ?? "";
        }
        return response()
            ->json(["data" => $returnData])
            ->header("Access-Control-Allow-Origin", "*");
    }
    public function getSponsorSelectOptions()
    {
        $sponsorOptions = User::select(
            "id",
            "name",
            "email",
            "excess_amount",
            "h_excess_amount"
        )->where("user_type", "sponser")->where("status", 1)->get();

        foreach ($sponsorOptions as $sponsorOption) {
            // Store the original name to use later
            $sponsorOption->sponsername = $sponsorOption->name;

            // Initialize strings for school and hostel excess amounts
            $schoolExcess =
                $sponsorOption->excess_amount !== null &&
                $sponsorOption->excess_amount != 0
                    ? "S(Rs. " . $sponsorOption->excess_amount . ")"
                    : "S(Rs. 0)";

            $hostelExcess =
                $sponsorOption->h_excess_amount !== null &&
                $sponsorOption->h_excess_amount != 0
                    ? "H(Rs. " . $sponsorOption->h_excess_amount . ")"
                    : "H(Rs. 0)";

            // Update the name field by appending both school and hostel excess amounts
            $sponsorOption->name =
                $sponsorOption->name .
                " - " .
                $schoolExcess .
                " - " .
                $hostelExcess;
        }

        // Return the modified sponsorOptions
        return response()->json(["sponsorOptions" => $sponsorOptions]);
    }

    public function getSponsorSelectOptionstwo()
    {
        $sponsorOptions = User::select(
            "id",
            "name",
            "email",
            "excess_amount",
            "h_excess_amount"
        )
            ->where("user_type", "sponser")
            ->where("status", 1)
            ->get();

        foreach ($sponsorOptions as $sponsorOption) {
            $sponsorOption->sponsername = $sponsorOption->name;
            if (
                $sponsorOption->excess_amount !== null &&
                $sponsorOption->excess_amount !== 0
            ) {
                // Update the 'name' field by appending the excess_amount
                $sponsorOption->name =
                    $sponsorOption->name .
                    "-S(Rs. " .
                    $sponsorOption->excess_amount .
                    ")- H(" .
                    $sponsorOption->h_excess_amount .
                    ")";
            } else {
                // Set '0' as the excess_amount when it's null or zero
                $sponsorOption->name = $sponsorOption->name . " -  Rs. 0";
            }
        }

        // Return the modified sponsorOptions
        return response()->json(["sponsorOptions" => $sponsorOptions]);
    }
    public function getParentSelectOptions()
    {
        $sponsorOptions = User::select("id", "name", "email", "excess_amount")
            ->where("user_type", "student")
            ->where("status", 1)
            ->get();

        // Initialize $sponsorOptiont outside the loop
        $sponsorOptiont = [];

        foreach ($sponsorOptions as $sponsorOption) {
            if (
                $sponsorOption->excess_amount !== null &&
                $sponsorOption->excess_amount !== 0
            ) {
                // Update the 'name' field by appending the excess_amount
                $sponsorOptiont[] = [
                    "name" =>
                        $sponsorOption->name .
                        " - Rs. " .
                        $sponsorOption->excess_amount,
                    "id" => $sponsorOption->id,
                    "email" => $sponsorOption->email,
                    "excess_amount" => $sponsorOption->excess_amount,
                    "sponsername" => $sponsorOption->name,
                ];
            }
        }

        // Return the modified sponsorOptions
        return response()->json(["sponsorOptiont" => $sponsorOptiont]);
    }

    public function getsponserstudent(Request $request)
    {
        $sponsorIds = User::select("id", "name", "email") // Select the 'name' and 'email' fields for sponsors
            ->where("user_type", "sponser")
            ->get();

        $studentRecords = [];

        foreach ($sponsorIds as $sponsor) {
            $studentRecords[$sponsor->id] = [
                "sponsor_name" => $sponsor->name,
                "sponsor_email" => $sponsor->email,
                "students" => User::select(
                    "id",
                    "admission_no",
                    "roll_no",
                    "name",
                    "gender",
                    "standard",
                    "twe_group",
                    "sec",
                    "hostelOrDay",
                    "email"
                )
                    ->where("sponser_id", $sponsor->id)
                    ->where("status", 1)
                    ->get(),
            ];
        }

        return response()->json(["student_records" => $studentRecords]);

        // $student_list = array();
        // $user_id=
        //                 $student_records = User::select(*)
        //                     ->where('sponser_id', $user_id)
        //                     ->where('status', '=', 1)
        //                     ->get();

        //             foreach ($student_records as $student_record) {
        //                 if ($student_record) {
        //                     array_push($student_list, $student_record);
        //                 }
        //             }
    }
    public function getSponsorIDStudents($sponsorId)
    {
        // Fetch and return the sponsor's students based on $sponsorId
        // You can use the code discussed earlier to retrieve the students
        $students = User::where("sponser_id", $sponsorId)
            ->where("status", 1)
            ->select(
                "id",
                "admission_no",
                "roll_no",
                "name",
                "gender",
                "standard",
                "twe_group",
                "sec",
                "hostelOrDay",
                "email"
            )
            ->get();

        // Initialize an empty array to store student records
        $studentRecords = [];
        $studentinfo = [];

        foreach ($students as $student) {
            $studentRecord = GenerateInvoiceView::where(
                "student_id",
                $student->id
            )
                // ->where('status', 1) // Uncomment this line if you want to filter by status
                ->get();

            // Loop through the students and retrieve their records
            // Check if $studentRecord is not empty
            if (!$studentRecord->isEmpty()) {
                // Check each $studentRecord for 'slno'
                foreach ($studentRecord as $invoiceRecord) {
                    if (isset($invoiceRecord->slno)) {
                        // Calculate the pending amount for this invoiceRecord
                        if (
                            isset($invoiceRecord->invoice_pending_amount) &&
                            $invoiceRecord->invoice_pending_amount !== null &&
                            $invoiceRecord->invoice_pending_amount !== 0
                        ) {
                            $pendingAmount =
                                $invoiceRecord->invoice_pending_amount;
                            // $studentRecord->pendingAmountwithconditon = $pendingAmount;
                        } else {
                            $pendingAmount =
                                $invoiceRecord->total_invoice_amount;
                            // $studentRecord->pendingAmountwithconditon = $pendingAmount;
                        }
                        $invoiceRecord->pendingAmountwithconditon = $pendingAmount;
                    } else {
                        // Skip this $studentRecord as it doesn't contain 'slno'
                        continue;
                    }
                }
                // $studentRecord->pendingAmountwithconditon = $pendingAmount;

                // Add the pendingAmount to the studentRecord as a new key-value pair
                $studentRecords[] = $studentRecord;
                $studentinfo[] = $student;
            }
        }

        // Check if there are no records found
        if (empty($studentRecords)) {
            // Return a custom JSON response indicating no records found
            return response()->json(
                ["message" => "No records found for this sponsor."],
                404
            );
        }

        // Return a JSON response containing the students' records
        return response()->json([
            "studentsInvoice" => $studentRecords,
            "studentinfo" => $studentinfo,
        ]);
    }

    public function getSponsorIDStudentstwo(Request $request, $sponsorId)
    {
        // Fetch and return the sponsor's students based on $sponsorId
        // You can use the code discussed earlier to retrieve the students
        $sponsorId =  $request->query("sponsorId");
        $sponsorType = $request->query("sponsortype");
        $students = User::where("sponser_id", $sponsorId)
            ->where("status", 1)
            ->select(
                "id",
                "admission_no",
                "roll_no",
                "name",
                "gender",
                "standard",
                "twe_group",
                "sec",
                "hostelOrDay",
                "email"
            )
            ->get();
        dd($students);
        // Initialize an empty array to store student records
        $studentRecords = [];
        $studentinfo = [];

        foreach ($students as $student) {
             dd($student->id,"hhuhhb", $sponsorType); 
            $studentRecord = GenerateInvoiceView::where(
                "student_id",
                $student->id
            )
                ->where("fees_cat", $sponsorType)
                ->get();

                  
            // Loop through the students and retrieve their records
            // Check if $studentRecord is not empty
            if (!$studentRecord->isEmpty()) {
                // Check each $studentRecord for 'slno'
                foreach ($studentRecord as $invoiceRecord) {
                    if (isset($invoiceRecord->slno)) {
                        // Calculate the pending amount for this invoiceRecord
                        // if (isset($invoiceRecord->invoice_pending_amount) && $invoiceRecord->invoice_pending_amount !== null && $invoiceRecord->invoice_pending_amount !== 0) {
                        if (isset($invoiceRecord->payment_status)) {
                            if ($invoiceRecord->payment_status === "Paid") {
                                $pendingAmount = "";
                            } elseif (
                                $invoiceRecord->payment_status ===
                                "Partial Paid"
                            ) {
                                $pendingAmount =
                                    $invoiceRecord->invoice_pending_amount;
                            } else {
                                $pendingAmount =
                                    $invoiceRecord->total_invoice_amount;
                            }
                        } else {
                            // Handle the case where $invoiceRecord->payment_status is not set
                            // You may want to assign a default value to $pendingAmount in this case
                            $pendingAmount = "Unknown"; // Change this to your desired default value
                        }

                        $paymentInformation = DB::table("by_pay_informations")
                            ->where("student_id", $invoiceRecord->student_id)
                            ->where("type", $invoiceRecord->fees_cat)
                            ->latest("id")
                            ->first();
                        // dd( $invoiceRecord->student_id,$invoiceRecord->fees_cat);
                        // $most_recent_dues = $paymentInformation->due_amount;

                        // $invoiceRecord->pendingAmountwithconditon = $most_recent_dues;
                    } else {
                        // Skip this $studentRecord as it doesn't contain 'slno'
                        continue;
                    }
                }
                $studentRecord = $studentRecord->toArray();

                // Add the pendingAmount to the studentRecord as a new key-value pair
                // $studentRecord[0]['pendingAmountwithconditon'] = $pendingAmount;

                // Merge the student record with the main array
                $studentRecords = array_merge($studentRecords, $studentRecord);

                // $studentRecord->pendingAmountwithconditon = $pendingAmount;

                // Add the pendingAmount to the studentRecord as a new key-value pair
                $studentinfo[] = $student;
            }
        }

        // Check if there are no records found
        if (empty($studentRecords)) {
            // Return a custom JSON response indicating no records found
            return response()->json(
                ["message" => "No records found for this sponsor."],
                404
            );
        }

        // Return a JSON response containing the students' records
        return response()->json([
            "studentsInvoice" => $studentRecords,
            "studentinfo" => $studentinfo,
        ]);
    }
    public function getUserDetailsWithExcessAmount()
    {
        // Fetch user details with excess_amount, user_type, standard, and email where excess_amount is not null
        $users = DB::table("users")
            ->where("status", 1)
            ->where(function ($query) {
                $query
                    ->where("excess_amount", "<>", 0)
                    ->orWhere("excess_amount", "IS NOT", null)
                    ->orWhere("h_excess_amount", "<>", 0)
                    ->orWhere("h_excess_amount", "IS NOT", null);
            })
            ->get();
        //dd($users);

        // Check if there are no records found
        if ($users->isEmpty()) {
            // Return a custom JSON response indicating no records found
            return response()->json(
                ["message" => "No records found with non-null excess_amount."],
                404
            );
        }

        // Return a JSON response containing the user details with excess_amount, user_type, standard, and email
        return response()->json(["userDetails" => $users]);
    }
    public function addExcessAmountForSponsor(Request $request)
    {
        $request->validate([
            'sponser_id' => 'required|integer',
            'school_excess_amount' => 'nullable|numeric',
            'hostel_excess_amount' => 'nullable|numeric',
        ]);

        $sponserId = $request->input('sponser_id');
        $schoolExcess = $request->input('school_excess_amount');
        $hostelExcess = $request->input('hostel_excess_amount');

        // Fetch all users with the given sponsor
        $users = DB::table('users')->where('sponser_id', $sponserId)->get();

        $sponser = DB::table('users')->where('id', $sponserId)->first();

        if (!$sponser) {
            return response()->json(['message' => 'Sponsor not found.'], 404);
        }

        $updateData = [];
            if (!is_null($schoolExcess)) {
                $updateData['excess_amount'] = $schoolExcess;
            }
            if (!is_null($hostelExcess)) {
                $updateData['h_excess_amount'] = $hostelExcess;
            }

            DB::table('users')->where('id', $sponserId)->update($updateData);

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found for this sponsor.'], 404);
        }

        foreach ($users as $user) {
            $updateData = [];
            if (!is_null($schoolExcess)) {
                $updateData['excess_amount'] = $schoolExcess;
            }
            if (!is_null($hostelExcess)) {
                $updateData['h_excess_amount'] = $hostelExcess;
            }

            DB::table('users')->where('id', $user->id)->update($updateData);

            // Insert history
            DB::table('user_excess_histories')->insert([
                'sponser_id' => $sponserId,
                'excess_amount' => $schoolExcess,
                'h_excess_amount' => $hostelExcess,
                'created_at' => now()->setTimezone("Asia/Kolkata"),
            ]);
        }

        return response()->json(['message' => 'Excess amount updated for all users under sponsor.']);
    }

    public function updateExcessAmount(Request $request)
    {
        // Validate the request input (e.g., you can validate the 'id' and any of the excess amounts)
        $userId = $request->input("id");
        $newExcessAmount = $request->input("new_excess_amount");
        $hnewExcessAmount = $request->input("hnew_excess_amount");

        // Check if the user exists
        $user = DB::table("users")
            ->where("id", $userId)
            ->first();

        if (!$user) {
            // User not found
            return response()->json(["message" => "User not found."], 404);
        }

        // Prepare the data to be updated
        $updateData = [];

        // Check if 'new_excess_amount' is present, not null, and not 0
        if (isset($newExcessAmount) && $newExcessAmount !== null) {
            $updateData["excess_amount"] = $newExcessAmount;
        }

        // Check if 'h_new_excess_amount' is present, not null, and not 0
        if (isset($hnewExcessAmount) && $hnewExcessAmount !== null) {
            $updateData["h_excess_amount"] = $hnewExcessAmount;
        }

        // If no valid excess amounts to update, return a message
        if (empty($updateData)) {
            return response()->json(
                ["message" => "No valid excess amounts to update."],
                400
            );
        }

        // Update the user's excess amounts in the database
        DB::table("users")
            ->where("id", $userId)
            ->update($updateData);
        $insertData = [
            "sponser_id" => $userId,
            "excess_amount" => $newExcessAmount ?? null,
            "h_excess_amount" => $hnewExcessAmount ?? null,
            "created_at" => now()->setTimezone("Asia/Kolkata"),
        ];
        DB::table("user_excess_histories")->insert($insertData);

        // Optionally, return a success message or updated user details
        return response()->json([
            "message" => "Excess amounts updated successfully.",
        ]);
    }

    public function viewExcessAmount($id)
    {
        // Validate the request input (e.g., you can validate the 'id' and 'new_excess_amount' fields here)

        // Retrieve 'id' and 'new_excess_amount' from the request
        $userId = $id;

        // Check if the user exists and 'excess_amount' is not null
        $user = DB::table("users")
            ->where("id", $userId)
            //->whereNotNull('excess_amount')
            ->first();

        if (!$user) {
            // User not found or 'excess_amount' is null
            return response()->json(
                ["message" => "User not found or excess_amount is null."],
                404
            );
        }

        // Optionally, you can return a success message or updated user details
        return response()->json([$user]);
    }

    public function viewpreExcessAmount($id)
    {
        // // Validate the request input (e.g., you can validate the 'id' and 'new_excess_amount' fields here)

        // // Retrieve 'id' and 'new_excess_amount' from the request
        $userId = $id;

        // // Check if the user exists and 'excess_amount' is not null
        // $user = DB::table('user_excess_histories')
        //     ->where('sponser_id', $userId)
        //     //->whereNotNull('excess_amount')
        //     ->get();

        // if (!$user) {
        //     // User not found or 'excess_amount' is null
        //     return response()->json(['message' => 'User not found or excess_amount is null.'], 404);
        // }

        // // Optionally, you can return a success message or updated user details
        // return response()->json([$user]);
        $classes = DB::table("user_excess_histories")
            ->where("sponser_id", $userId)
            ->get();

        $data = []; // create an empty array to hold the data

        foreach ($classes as $class) {
            $data[] = [
                "id" => $class->id,
                "excess_amount" => $class->excess_amount,
                "h_excess_amount" => $class->h_excess_amount,
                "created_at" => $class->created_at,
                "created_by" => User::find($class->sponser_id)->name ?? "",
            ];
        }
        return response()->json(["data" => $data]);
    }
    
    public function processCashPayment(Request $request)
    {
        $requestData = json_encode($request->all());

        $sponsorId = $request->input("sponsorId");
        $sponsortype = $request->input("sponsortype");
        $invoiceData = $request->input("invoiceData"); // Array of invoice data
        $totalAmount = $request->input("totalAmount"); // Total payment amount
        // return response()->json(['message' => $invoiceData, 'total' => $totalAmount]);
        $sponsorcheckexcess = User::where("id", $sponsorId)->first();
        // $studentcheckexcess = User::where('id', $invoiceDetails->student_id)->first();
        if ($sponsortype == "school") {
            $sponsor_prev_excess = $sponsorcheckexcess->excess_amount;
        } else {
            $sponsor_prev_excess = $sponsorcheckexcess->h_excess_amount;
        }
        // return response()->json(['message' => $requestData, 'total' => $totalAmount]);
        // dd($sponsor_prev_excess , $totalAmount);
        if ($sponsor_prev_excess < $totalAmount) {
            return response()->json([
                "message" =>
                    "Total amount is greater than sponsor excess amount.",
                "status" => "NoAmount",
            ]);
        }
        if ($sponsortype == "school") {
            $sponsorremainexcess = $sponsor_prev_excess - $totalAmount;

            User::where("id", $sponsorId)->update([
                "excess_amount" => $sponsorremainexcess,
            ]);
        } else {
            $sponsorremainexcess = $sponsor_prev_excess - $totalAmount;

            User::where("id", $sponsorId)->update([
                "h_excess_amount" => $sponsorremainexcess,
            ]);
        }

        // $amount = $request->amount;
        $sponsor = $request->input("sponsorId");
        $mode = "cash";
        $additionalDetails = "test";

        // Variables to track total payment across all invoices
        $overallExcessAmount = 0;
        $overallPayedAmount = 0;

        foreach ($invoiceData as $invoice) {
            $id = $invoice["id"]; // Get each invoice ID
            $amount = $invoice["amount"];
            $invoiceDetails = GenerateInvoiceView::find($id);
            // return response()->json(['message' => $invoice['amount']]);
            if (!$invoiceDetails) {
                return response()->json(
                    ["message" => "Invoice details not found."],
                    404
                );
            }
            // Generate a random transaction ID for each invoice
            $transactionId = FastInvoiceHelper::generateReceiptWithPrefix(
                $invoiceDetails->fees_cat
            );

            // Initialize variables for each invoice
            $student_excess_amount = 0;
            $excess_amount_used = 0;
            $advance_amount = 0;
            $payed_amount = 0;
            $pending_amount = 0;
            $payment_status = "";
            $excess_source = "";

            // Fetch payment information for the student
            // $paymentInformation = DB::table('by_pay_informations')
            //     ->where('student_id', $invoiceDetails->student_id)
            //     ->where('type', $invoiceDetails->fees_cat)
            //     ->latest('id')
            //     ->first();

            // $most_recent_dues = $paymentInformation->due_amount;
            // $s_excess = $paymentInformation->s_excess_amount;
            // $h_excess = $paymentInformation->h_excess_amount;

            $payed_amount = $amount;
            // Calculate pending amount for each invoice
            $totalInvoiceAmountActual = number_format(
                $invoiceDetails->total_invoice_amount,
                2,
                ".",
                ""
            );
            $previousTransactions = Invoice_list::where(
                "invoice_id",
                $invoiceDetails->slno
            )->sum("transaction_amount");


            $pending_amount =
                $totalInvoiceAmountActual -
                ($previousTransactions + $payed_amount);
            // return response()->json(['message' => $payed_amount]);
            // Payment status determination
            if ($pending_amount > 0) {
                $payment_status = "Partial Paid";
            } elseif ($pending_amount < 0) {
                $upexcess_amount = -$pending_amount;
                $payment_status = "Paid";
            } else {
                $payment_status = "Paid";
            }
            $paymentInformation = DB::table("by_pay_informations")
                ->where("student_id", $invoiceDetails->student_id)
                ->where("type", $invoiceDetails->fees_cat)
                ->latest("id")
                ->first();
                //   dd( $invoiceDetails->student_id,$invoiceDetails->fees_cat,$totalInvoiceAmountActual,$previousTransactions,$paymentInformation);
            $most_recent_dues = $paymentInformation->due_amount;
            if ($payed_amount < $totalInvoiceAmountActual) {
                $dues = $most_recent_dues - $payed_amount;
            } else {
                $dues = 0;
            }

            $student = User::find($invoiceDetails->student_id);
            if (!$student) {
                return response()->json(
                    ["message" => "Student not found."],
                    404
                );
            }
            $studentcheckexcess = User::where(
                "id",
                $invoiceDetails->student_id
            )->first();
            if ($invoiceDetails->fees_cat == "school") {
                $student_prev_excess = $studentcheckexcess->excess_amount;
                if ($invoice["inv_amount"] < $amount) {
                    $school_excess = $amount - $invoice["inv_amount"];
                    $hostel_excess = 0;
                } else {
                    $school_excess = 0;
                    $hostel_excess = 0;
                }
                User::where("id", $invoiceDetails->student_id)->update([
                    "excess_amount" => $student_prev_excess + $school_excess,
                ]);
            } else {
                $student_prev_excess = $studentcheckexcess->h_excess_amount;

                if ($invoice["inv_amount"] < $amount) {
                    $school_excess = 0;
                    $hostel_excess = $amount - $invoice["inv_amount"];
                } else {
                    $school_excess = 0;
                    $hostel_excess = 0;
                }
                User::where("id", $invoiceDetails->student_id)->update([
                    "h_excess_amount" => $student_prev_excess + $hostel_excess,
                ]);
            }

            // Update the invoice record
            GenerateInvoiceView::where(
                "invoice_no",
                $invoiceDetails->invoice_no
            )->update([
                "payment_status" => $payment_status,
                "invoice_status" => 4,
                "paid_amount" => number_format(
                    ($invoiceDetails->paid_amount ?? 0) + $payed_amount,
                    2,
                    ".",
                    ""
                ),
                "invoice_pending_amount" => number_format(
                    $pending_amount,
                    2,
                    ".",
                    ""
                ),
                "additionalDetails" => $additionalDetails,
                "mode" => $mode,
                "due_amount" => $dues ?? null,
                "s_excess_amount" => $school_excess ?? null,
                "h_excess_amount" => $hostel_excess ?? null,
            ]);

            // Accumulate results for all invoices
            $overallExcessAmount += $school_excess + $hostel_excess;
            $overallPayedAmount += $payed_amount;

            $dataInvoicePaymentMaps = [
                "user_uuid" => $invoiceDetails->student_id,
                "invoice_id" => $invoiceDetails->slno,
                "payment_transaction_id" => $transactionId,
                "status" => "success",
                "transaction_completed_status" => 1,
                "transaction_amount" => $payed_amount,
                "balance_amount" => $pending_amount,
            ];

            $GenerateInvoiceView = Invoice_list::create(
                $dataInvoicePaymentMaps
            );

            $payment_order_data["internal_txn_id"] = $transactionId;
            $payment_order_data["user_id"] = $invoiceDetails->student_id; // user id need to mention
            $payment_order_data["amount"] = $amount;
            $payment_order_data["maxAmount"] = null;
            //Payment user details
            $payment_order_data["name"] = $invoiceDetails->name;
            $payment_order_data["custID"] = $invoiceDetails->student_id;
            $payment_order_data["mobNo"] = null;

            //Payment
            $payment_order_data["paymentMode"] = $mode;
            $payment_order_data["accNo"] = null;
            $payment_order_data["debitStartDate"] = null;
            $payment_order_data["debitEndDate"] = null;
            $payment_order_data["amountType"] = null;
            $payment_order_data["currency"] = "INR";
            $payment_order_data["frequency"] = null;
            $payment_order_data["cardNumber"] = null;
            $payment_order_data["expMonth"] = null;
            $payment_order_data["expYear"] = null;
            $payment_order_data["cvvCode"] = null;
            $payment_order_data["scheme"] = null;
            $payment_order_data["accountName"] = null;
            $payment_order_data["ifscCode"] = null;
            $payment_order_data["accountType"] = null;
            $payment_order_data["payment_status"] = null;
            $payment_order_data["order_hash_value"] = null;
            $payment_order_data["payment_status"] = "success";
            $payment_order_data["payment_code"] = null;
            $PaymentOrderDetails = PaymentOrdersDetails::create(
                $payment_order_data
            );

            $save_payment_orders_status_data["clnt_txn_ref"] = $transactionId;
            $save_payment_orders_status_data["txn_status"] = "success";
            $save_payment_orders_status_data["txn_msg"] = null;
            $save_payment_orders_status_data["txn_err_msg"] = null;
            $save_payment_orders_status_data["tpsl_bank_cd"] = null;
            $save_payment_orders_status_data["tpsl_txn_id"] = null;
            $save_payment_orders_status_data["txn_amt"] = $amount;
            $save_payment_orders_status_data["clnt_rqst_meta"] = null;
            $save_payment_orders_status_data["tpsl_txn_time"] = null;
            $save_payment_orders_status_data["bal_amt"] = null;
            $save_payment_orders_status_data["card_id"] = null;
            $save_payment_orders_status_data["alias_name"] = null;
            $save_payment_orders_status_data["BankTransactionID"] = null;
            $save_payment_orders_status_data["mandate_reg_no"] = null;
            $save_payment_orders_status_data["token"] = null;
            $save_payment_orders_status_data["hash"] = null;
            $save_payment_orders_status_data["payment_gatway_response"] = null;
            $save_payment_orders_status_data["pay_res_updatedAt"] = date(
                "Y-m-d H:i:s"
            );

            $PaymentOrdersStatus = PaymentOrdersStatuses::create(
                $save_payment_orders_status_data
            );
            DB::table("by_pay_informations")->insert([
                "student_id" => $invoiceDetails->student_id,
                "invoice_id" => $invoiceDetails->slno,
                "transactionId" => $transactionId,
                "amount" => $amount, // Actual paid amount
                "payment_status" => $payment_status, // Payment status
                "additional_details" => $additionalDetails, // Additional payment details
                "mode" => $mode, // Payment mode
                "sponsor" => $sponsor ?? null, // Sponsor ID (if any)
                "due_amount" => $dues ?? null,
                "s_excess_amount" => $school_excess ?? null,
                "h_excess_amount" => $hostel_excess ?? null,
                "type" => $invoiceDetails->fees_cat ?? null,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
        DB::table("bulk_sponser_payments")->insert([
            "request_data" => $requestData,
        ]);
        return response()->json([
            "message" => "Payment processed successfully",
            "status" => "Success",
            "overall_payed_amount" => $overallPayedAmount,
            "overall_excess_amount" => $overallExcessAmount,
        ]);
    }

    public function getNoDuesCertificatesbyid($id)
    {
        // $id  = $request->input('id');
        // $id = $request->input('id');
        if (strpos($id, ",") !== false) {
            list($id, $acad_year) = explode(",", $id);
        } else {
            $id = $id;
        }

        // Get a list of all student IDs from the 'users' table
        $studentIds = User::where("id", $id)
            ->pluck("id")
            ->toArray();

        $issuedCertificates = [];
        $pendingDues = [];
        $noinvoicethereStudents = [];

        // Iterate through each student ID
        foreach ($studentIds as $studentId) {
            // Check if the student exists
            // $studentId = 13;
            $student = $student = User::select(
                "id",
                "admission_no",
                "roll_no",
                "name",
                "gender",
                "standard",
                "twe_group",
                "sec",
                "hostelOrDay",
                "email",
                "fee_by",
                "sponser_id"
            )
                ->with("sponsor") // Load the sponsor relationship
                ->find($studentId);

            if ($student) {
                $student["sponser_name"] = $student->sponsor->name ?? null;

                // Find all pending invoices for the student
                $pendingInvoices = GenerateInvoiceView::where(
                    "student_id",
                    $studentId
                )
                    ->where("payment_status", "<>", "Paid") // Check for payment status other than 'Paid'
                    ->get();

                if ($pendingInvoices->isEmpty()) {
                    // No pending invoices found, the student has no dues
                    $issuedCertificates[] = [
                        "student_info" => $student,
                        "acad_year" => $acad_year ?? "",
                        "message" =>
                            "No dues found for the student. Issuing No Due Certificate...",
                        "status" => "No Dues Certificate Can be Issued",
                    ];
                } else {
                    // There are pending invoices, the student has dues
                    $pendingDues[] = [
                        "student_info" => $student,
                        "message" => "Student has pending dues",
                        "status" => "Pending Dues",
                        "pending_invoices" => $pendingInvoices,
                    ];
                }
            } else {
                // Student not found
                $pendingDues[] = [
                    "student_info" => $student,
                    "message" => "Student not found",
                    "status" => "Error",
                ];
            }
        }

        // Return an array containing both issued certificates and pending dues
        // $resultArray = [
        //     'nodue_certificates' => $issuedCertificates,
        //   //  'pending_dues' => $pendingDues,
        // ];

        return response()->json($issuedCertificates);
    }
    public function getNoDuesCertificatesAndPendingDues(Request $request)
    {
        $acad_year = $request->input("acad_year");

        // Get a list of all student IDs from the 'users' table
        $studentIds = User::where("user_type", "student")
            ->pluck("id")
            ->toArray();

        $issuedCertificates = [];
        $pendingDues = [];
        $noinvoicethereStudents = [];

        // Iterate through each student ID
        foreach ($studentIds as $studentId) {
            // Check if the student exists
            // $studentId = 13;
            $student = $student = User::select(
                "id",
                "admission_no",
                "roll_no",
                "name",
                "gender",
                "standard",
                "twe_group",
                "sec",
                "hostelOrDay",
                "email",
                "fee_by",
                "sponser_id"
            )
                ->with("sponsor") // Load the sponsor relationship
                ->find($studentId);

            if ($student) {
                $student["sponser_name"] = $student->sponsor->name ?? null;

                // Find all pending invoices for the student
                if (!$acad_year) {
                    $pendingInvoices = GenerateInvoiceView::where(
                        "student_id",
                        $studentId
                    )
                        ->where("payment_status", "<>", "Paid") // Check for payment status other than 'Paid'
                        ->get();
                } else {
                    $pendingInvoices = GenerateInvoiceView::where(
                        "student_id",
                        $studentId
                    )
                        ->where("acad_year", $acad_year)
                        ->where("payment_status", "<>", "Paid") // Check for payment status other than 'Paid'
                        ->get();
                }

                if ($pendingInvoices->isEmpty()) {
                    // No pending invoices found, the student has no dues
                    $issuedCertificates[] = [
                        "student_info" => $student,
                        "acad_year" => $acad_year,
                        "message" =>
                            "No dues found for the student. Issuing No Due Certificate...",
                        "status" => "No Dues Certificate Can be Issued",
                    ];
                } else {
                    // There are pending invoices, the student has dues
                    $pendingDues[] = [
                        "student_info" => $student,
                        "message" => "Student has pending dues",
                        "status" => "Pending Dues",
                        "pending_invoices" => $pendingInvoices,
                    ];
                }
            } else {
                // Student not found
                $pendingDues[] = [
                    "student_info" => $student,
                    "message" => "Student not found",
                    "status" => "Error",
                ];
            }
        }

        // Return an array containing both issued certificates and pending dues
        $resultArray = [
            "nodue_certificates" => $issuedCertificates,
            "pending_dues" => $pendingDues,
        ];

        return response()->json($resultArray);
    }

    public function sendsms(Request $request)
    {
        // Get an array of slno values from the request
        $slnoValues = $request->input("slno"); // Assuming 'slno' is sent as an array

        if (empty($slnoValues)) {
            return response()->json(["message" => "No slno values provided."]);
        }

        // Query the GenerateInvoiceView model based on conditions
        $GenerateInvoiceView = GenerateInvoiceView::whereIn("slno", $slnoValues)
            ->where("payment_status", "<>", "Paid") // Check for payment status other than 'Paid'
            ->get();
        if ($GenerateInvoiceView->isEmpty()) {
            // No records found that match the conditions
            return response()->json([
                "message" =>
                    "No unpaid invoices found for the specified slno values.",
            ]);
        }
        $invoices = [];
        foreach ($GenerateInvoiceView as $invoice) {
            try {
                // Retrieve the phone number based on the student_id
                $phone_no = Student::where("roll_no", $invoice->roll_no)
                    ->where("STUDENT_NAME", "like", "%" . $invoice->name . "%")
                    ->value("MOBILE_NUMBER");

                // Add Indian country code if the phone number is not empty
                if (!empty($phone_no)) {
                    $userDatas = User::where("roll_no", "=", $invoice->roll_no)
                        ->where("name", "like", "%" . $invoice->name . "%")
                        ->first();

                    // Add +91 as the country code for Indian numbers
                    // $phone_no = '+91' . $phone_no;
                    $phone_no = $phone_no;

                    $message =
                        "Dear " .
                        $invoice->name .
                        ", This is to inform you that your ward's fee has been generated. Your invoice number is " .
                        $invoice->invoice_no .
                        " and the due date for payment is " .
                        $invoice->due_date .
                        ". If you have any questions or require support, please feel free to contact the Santhosha Vidhyalaya administrator. Pay Online using  https://santhoshavidhyalaya.com/Payfeeportal/  - Santhosha Vidhyalaya";

                    // Send SMS using Laravel HTTP client
                    $smsResponse = Http::withHeaders([
                        "Content-Type" => "application/json",
                        "Authorization" =>
                            "Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==", // Replace with your BASIC AUTH string
                    ])->post(
                        "https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/",
                        [
                            "Text" => $message,
                            "Number" => $phone_no,
                            "SenderId" => "SVHSTL",
                            "DRNotifyUrl" =>
                                "https://www.domainname.com/notifyurl",
                            "DRNotifyHttpMethod" => "POST",
                            "Tool" => "API",
                        ]
                    );

                    // Handle the SMS response (you can customize this as needed)
                    $smsStatusCode = $smsResponse->status();
                    $smsResponseBody = $smsResponse->body();

                    if ($smsStatusCode == 200) {
                        // SMS sent successfully
                        Log::info(
                            "SMS sent successfully. Response: " .
                                $smsResponseBody
                        );
                    } else {
                        // SMS sending failed
                        Log::error(
                            "SMS sending failed. Response: " . $smsResponseBody
                        );
                    }

                    $contentWithLineBreaks = htmlspecialchars_decode(
                        nl2br(str_replace("<br>", "\n", $invoice->fees_glance))
                    );
                    $feesItemsDetails = json_decode(
                        $invoice->fees_items_details,
                        true
                    );
                    $discountItemsDetails = json_decode(
                        $invoice->discount_items_details,
                        true
                    );
                    $paymentInformation = DB::table("by_pay_informations")
                        ->where("student_id", $invoice->student_id)
                        ->where("type", $invoice->fees_cat)
                        ->latest("id")
                        ->first();
                    //fees_items_details discount_items_details   amount  total_invoice_amount discount_percent
                    $invoiceMail = new InvoiceGenerated(
                        $invoice->invoice_no,
                        $userDatas->roll_no,
                        $userDatas->name,
                        $userDatas->standard,
                        $userDatas->twe_group,
                        $userDatas->sec,
                        $userDatas->hostelOrDay,
                        $userDatas->sponser_id,
                        $userDatas->email,
                        $contentWithLineBreaks,
                        $invoice->fees_cat,
                        $feesItemsDetails,
                        $discountItemsDetails,
                        $invoice->amount,
                        $invoice->total_invoice_amount,
                        $invoice->discount_percent,
                        $invoice->actual_amount,
                        $invoice->invoice_pending_amount,
                        $invoice->created_at->format("d/m/Y"),
                        $paymentInformation->due_amount,
                        $paymentInformation->s_excess_amount,
                        $paymentInformation->h_excess_amount
                    );
                    // Queue the email for sending
                    Mail::to($userDatas->email)->queue($invoiceMail);
                    // Log a success message indicating that the email was queued successfully
                    Log::info(
                        "Email for invoice " .
                            $invoice->invoice_no .
                            " queued for sending to " .
                            $userDatas->email
                    );

                    // Your SMS sending code here (if applicable)

                    // Log a success message indicating that the SMS was sent successfully
                    Log::info(
                        "SMS sent successfully for invoice " .
                            $invoice->invoice_no
                    );
                    $invoices[] = $userDatas->name;
                    // return response()->json(['message' => 'SMS and email notifications sent successfully.','invoiceMail'=>$invoice]);
                }
            } catch (\Exception $e) {
                // Log an error message if there was an issue processing notifications
                Log::error(
                    "Error processing notifications for invoice " .
                        $invoice->invoice_no .
                        ": " .
                        $e->getMessage()
                );
            }
        }

        return response()->json([
            "message" => "SMS and email notifications sent successfully.",
            "invoiceMail" => $invoices,
            "invoiceMfeeail" => json_decode($invoice->fees_items_details),
        ]);
    }
}
function generateReceiptNumber()
{
    // Define a prefix for the receipt number
    $prefix = "SVS";

    // Get the current date in the format "dMY" (day, month, year)
    $date = date("dMY");

    // Generate a unique identifier (e.g., using a random number or database auto-increment)
    $uniqueIdentifier = mt_rand(10000, 99999); // You can adjust the range as needed

    // Combine the elements to create the receipt number
    $receiptNumber = $prefix . $date . $uniqueIdentifier;

    return $receiptNumber;
}
