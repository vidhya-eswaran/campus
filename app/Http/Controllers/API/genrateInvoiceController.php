<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\FeesMap;
use App\Models\SeperateFeesMap;
use App\Models\GenerateInvoiceView;
use App\Models\SchoolFeeDiscount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceGenerated;
use App\Models\StudentFeesMap;
use App\Models\Student;
use PhpParser\Node\Expr\Cast\Object_;
use App\Helpers\TwilioHelper;
use Illuminate\Support\Facades\Log; // Add this import statement
use Illuminate\Support\Facades\Http;
use App\Models\PaymentOrdersDetails;
use App\Models\PaymentOrdersStatuses;
use App\Models\Invoice_list;
use App\Models\ByPayInformation;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Mail\PaymentReceiptMail;
use App\Helpers\FastInvoiceHelper;
use App\Helpers\LifecycleLogger;
use App\Http\Controllers\PushNotificationController;


class genrateInvoiceController extends Controller
{
    // for total amount
    public function getTotalAmount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "std" => "required",
            "group" => "nullable",
            "cat" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => "validator error"], 401);
        }
        $cata = $request->input("cat");
        if ($cata == "school fees") {
            $records = FeesMap::where("status", "=", 1)
                ->where("invoice_generated", "=", 0)
                ->where("standard", "=", $request->input("std"))
                ->where("group", "=", $request->input("group"))
                ->where("fees_heading", "=", $request->input("cat"))
                ->orderBy("id", "DESC")
                ->get();
        } else {
            $records = FeesMap::where("status", "=", 1)
                ->where("invoice_generated", "=", 0)
                ->where("standard", "=", $request->input("std"))
                ->where("group", "=", $request->input("group"))
                ->where("fees_heading", "!=", "school fees")
                ->orderBy("id", "DESC")
                ->get();
        }
        $data = [];

        $concatenatedHeadings = "";

        foreach ($records as $record) {
            $concatenatedHeadings .=
                $record->fees_heading .
                "-" .
                $record->fees_sub_heading .
                "-" .
                $record->amount .
                "<br>";
        }
        if ($cata == "school fees") {
            $data[] = [
                "heading" => "school fees",
                "total" => $records->sum("amount"),
                "glance" => $concatenatedHeadings,
            ];
        } else {
            $data[] = [
                "heading" => "others",
                "total" => $records->sum("amount"),
                "glance" => $concatenatedHeadings,
            ];
        }
        return response()->json(["data" => $data]);
    }
    //////////////////////////////
    public function discountTotalAmount(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            //`id`, `student_id`, `roll_no`, `discount_cat`, `dis_amount`, `year`, `created_by`, `created_at`, `updated_at` FROM
            [
                "student_id" => "nullable",
                "roll_no" => "nullable",
                "invoicefeescat" => "nullable",
                "discount_cat" => "nullable",
                "dis_amount" => "required",
                "year" => "nullable",
            ]
        );

        if ($validator->fails()) {
            return response()->json(["message" => "validator errors"], 401);
        }
        $data = $request->all();
        foreach ($data["student_id"] as $student) {
            $datas["student_id"] = $student;
            $datas["discount_cat"] = $data["discount_cat"];
            $datas["invoicefeescat"] = $data["invoicefeescat"];
            $datas["dis_amount"] = $data["dis_amount"];
            $datas["year"] = $data["year"];
            $user = SchoolFeeDiscount::create($datas);
        }

        if ($user) {
            return response()->json(
                [
                    "message" => "validator sucess Data inserted",
                    "data" => $data,
                ],
                200
            );
        }
    }
   
    public function getDiscountCategories()
    {
        $categories = SchoolFeeDiscount::select("discount_cat")
            ->distinct()
            ->orderBy("discount_cat", "asc")
            ->pluck("discount_cat");

        return response()->json(["categories" => $categories]);
    }

    public function readDiscount(Request $request)
    {
        $fromDate = $request->input("fromDate");
        $toDate = $request->input("toDate");
        $students = $request->input("students");
        $grade = $request->input("grade");
        $discountCat = $request->input("discount_cat");
        if (!empty($students) && !is_array($students)) {
            $students = explode(",", $students); // Convert comma-separated values to array
        }
        $fromDate = !empty($fromDate)
            ? Carbon::parse($fromDate)
                ->startOfDay()
                ->toDateTimeString()
            : null;
        $toDate = !empty($toDate)
            ? Carbon::parse($toDate)
                ->endOfDay()
                ->toDateTimeString()
            : null;

        $users = SchoolFeeDiscount::when($fromDate, function ($query) use (
            $fromDate
        ) {
            return $query->where("created_at", ">=", $fromDate);
        })
            ->when($toDate, function ($query) use ($toDate) {
                return $query->where("created_at", "<=", $toDate);
            })
            ->when($discountCat, function ($query) use ($discountCat) {
                return $query->where("discount_cat", "like", "%$discountCat%");
            })
            ->when(!empty($students), function ($query) use ($students) {
                return $query->whereIn("student_id", (array) $students); // Ensure $students is an array
            })
            ->get();

        // return $users;
        // Apply Grade Filter and Fetch User Details
        $filteredUsers = $users
            ->filter(function ($student) use ($grade) {
                $query = User::select(
                    "id",
                    "name",
                    "roll_no",
                    "standard",
                    "sec"
                )
                    ->where("id", $student->student_id)
                    ->whereNotNull("standard"); // Ensure standard is not null

                if (!empty($grade)) {
                    $query->where("standard", $grade);
                }

                $user = $query->first();

                if ($user) {
                    // Add user details
                    $student->student_id = $student->student_id;
                    $student->name = $user->name;
                    $student->roll_no = $user->roll_no;
                    $student->standard = $user->standard;
                    $student->sec = $user->sec;
                    $student->discount_cat =
                        $student->invoicefeescat . "-" . $student->discount_cat;
                    $student->status =
                        $student->status == "0" ? "Generated" : "Not Generated";
                    $student->created_att = Carbon::parse(
                        $student->created_at
                    )->format("d/m/Y h:i A");
                    return true; // Keep this record
                }
                return false; // Remove this record
            })
            ->values(); // Reset keys

        return response()->json(["data" => $filteredUsers]);
    }

    public function deleteDiscount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
        ]);
        $data = $request->all();

        $id = $data["id"]; // replace with the ID of the data that you want to delete

        SchoolFeeDiscount::destroy($id); // delete
    }
    ///////////////LOAD////////////////LOAD///////////////////LOAD///////////////LOAD///////////////////LOAD///////////////////////////
    public function genrateForGrade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "std" => "required",
            "group" => "nullable",
            "cat" => "required",
            "discount" => "nullable",
            "due_date" => "required",
            "created_by" => "nullable",
        ]);
        if ($validator->fails()) {
            return response()->json(["message" => "validator error"], 401);
        }
        /////////////////////////////ends validator//////////////////////////////////////////////////////////

        $cata = $request->input("cat");
        $fees_cat = $cata == "school fees" ? "school" : "other"; // for insert in  gen invoice table  ternary operator  $cata == "school fees" evaluates to true, then the value 'school' is assigned, otherwise, the value 'other' is assigned.
        $invoiceTypeString = $cata == "school fees" ? "S" : "O"; // for Invoice no random id
        /////////////////////////////////////////////////////////////////////////////////////////////////////

        ///%%%$$%$%$$//////////////Get each user////////////////////////invoice id////////////////////////////////////////////
        $userDatas = User::where("status", "=", 1)
            ->where("standard", "=", $request->input("std"))
            ->where("user_type", "=", "student")
            ->when($request->has("stdid"), function ($query) use ($request) {
                $ids = $request->input("stdid");
                if (is_array($ids) && !empty($ids)) {
                    return $query->whereIn("id", $ids);
                }
                return $query;
            })
            ->get();

        foreach ($userDatas as $userData) {
            unset($fees_items_details);
            $concatenatedHeadings = "";
            $fees_items_details = []; // Fees items details json for invoice print

            //push notification
                
                $title = 'Student Invoice generated';
                $body = 'Your Student Invoice generated.';
                $deviceToken = $userData->device_token;
                $type = 'Invoice';
                $toUserId = $userData->id;
                $data = [
                    'student_id' => $userData->id,
                    'date' => now()->toDateString(),
                ];

                $response = PushNotificationController::sendPushNotification(
                    $title,
                    $body,
                    $type,
                    $data,
                    $toUserId,
                    $deviceToken
                );

            /////////////for each input data ////////////////////////////////

            if ($cata == "school fees") {
                $records = StudentFeesMap::where("status", "=", 1)
                    ->where("invoice_generated", "=", 0)
                    ->where("student_id", "=", $userData->id)
                    ->where("standard", "=", $request->input("std"))
                    ->where("fee_heading", "=", $request->input("cat"))
                    ->orderBy("slno", "DESC")
                    ->get();
                if ($records) {
                    foreach ($records as $record) {
                        // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                        // $concatenatedHeadings .=  $record->fees_heading . '-' . $record->fees_sub_heading . '-' . $record->amount . '<br>';
                        $concatenatedHeadings .=
                            $record->fee_heading .
                            "[" .
                            $record->fee_sub_heading .
                            "]:  Rs." .
                            $record->amount .
                            "<br>";

                        $fees_items = (object) [];
                        $fees_items->fees_heading = $record->fee_heading;
                        $fees_items->fees_sub_heading =
                            $record->fee_sub_heading;
                        $fees_items->amount = $record->amount;
                        $fees_items->Priority = $record->Priority ?? "";
                        array_push($fees_items_details, $fees_items); // pusing each item to the array$userData->id
                    }
                }
            } else {
                $records = StudentFeesMap::where("status", "=", 1)
                    ->where("invoice_generated", "=", 0)
                    ->where("student_id", "=", $userData->id)
                    ->where("standard", "=", $request->input("std"))
                    ->where("fee_heading", "!=", "School Fees")
                    ->orderBy("slno", "DESC")
                    ->get();
                if ($records) {
                    foreach ($records as $record) {
                        // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                        $concatenatedHeadings .=
                            $record->fee_heading .
                            "[" .
                            $record->fee_sub_heading .
                            "]:  Rs." .
                            $record->amount .
                            "<br>";
                        $fees_items = (object) [];
                        $fees_items->fees_heading = $record->fee_heading;
                        $fees_items->fees_sub_heading =
                            $record->fee_sub_heading;
                        $fees_items->amount = $record->amount;
                        $fees_items->Priority = $record->Priority ?? "";
                        array_push($fees_items_details, $fees_items); // pusing each item to the array
                    }
                }
            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            if (!empty($fees_items_details)) {
                $data = (object) [];
                $data->standard = $request->input("std");
                $data->twe_group = $request->input("group");
                $data->amount = $records->sum("amount");
                $data->fees_glance = $concatenatedHeadings;
                $data->fees_items_details = $fees_items_details;
                $data->fees_cat = $fees_cat;
                $data->date = date("d/m/Y");
                $data->acad_year = $record->acad_year;
                $data->due_date = $request->input("due_date");
                $data->payment_status = "Invoice generated";
                $data->created_by = $request->input("created_by");

              
                $invoice_lists = GenerateInvoiceView::select(
                    "invoice_no"
                )->get();
                $usedInvoiceIdsNos = [];
                $usedInvoiceIdsCount = $invoice_lists->count();
                if ($invoice_lists) {
                    foreach ($invoice_lists as $invoice_list) {
                        if ($invoice_list->invoice_no) {
                            array_push(
                                $usedInvoiceIdsNos,
                                strtoupper($invoice_list->invoice_no)
                            );
                        }
                    }
                }
                $currentYear = date("y");

                $year = date("y");
                $month = date("m");
                $rondomIdString = "GI" . $invoiceTypeString . $month . $year;
               
                $total_invoice_amount = 0;
                $pending_amount = 0;

                if ($cata == "school fees") {
                    $discountSum = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "school fees")
                        ->where("status", 1)
                        ->sum("dis_amount");
                    $discountSum_l = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "school fees")
                        ->where("status", 1)
                        ->get();
                } else {
                    $discountSum = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "!=", "school fees")
                        ->where("status", 1)
                        ->sum("dis_amount");
                    $discountSum_l = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "!=", "school fees")
                        ->where("status", 1)
                        ->get();
                }
               

                $amount =
                    null !== $discountSum
                        ? $data->amount - $discountSum
                        : $data->amount;
              
                $total_invoice_amount = $amount;
                // }

                foreach ($discountSum_l as $disrecord) {
                    // $concatenatedHeadings .= 'Fee Heading: ' . $disrecord->fees_heading . ' - Subheading: ' . $disrecord->fees_sub_heading . ' - Amount: ' . $disrecord->amount . '<br>';
                    $dis_items = (object) [];
                    $dis_items->discount_cat = $disrecord->discount_cat;
                    $dis_items->dis_amount = $disrecord->dis_amount;
                    array_push($dis_items_details, $dis_items); // pusing each item to the array
                    // Update the discount status in the database
                    $discountId = $disrecord->id; // Assuming you have a discount_id field in the $disrecord object
                }
              
                $invoice_no = FastInvoiceHelper::generateInvoiceWithPrefix(
                    $fees_cat
                );
                // array_push($usedInvoiceIdsNos, $invoice_no);
                // Inside your generateForGrade method
                $generateInvoiceViewModel = new GenerateInvoiceView();

                $dues1 = $generateInvoiceViewModel->getMostRecentDues(
                    $userData->id,
                    $data->fees_cat
                );
                $excess1 = $generateInvoiceViewModel->getMostRecentExcess(
                    $userData->id,
                    $data->fees_cat
                );

                // For Invoice Insertion
                // If (dues1 + invoice - excess1) > 0: dues = dues1 + invoice - excess1; excess = 0
                // If (dues1 + invoice - excess1) < 0: dues = 0; excess = abs(dues1 + invoice - excess1)
                if ($dues1 + $amount - $excess1 > 0) {
                    $newDues = $dues1 + $amount - $excess1;
                    $newExcess = 0;
                } elseif ($dues1 + $amount - $excess1 < 0) {
                    $newExcess = abs($dues1 + $amount - $excess1);
                    $newDues = 0;
                } else {
                    $newDues = 0;
                    $newExcess = 0;
                }
                $dataFeeMaps = [
                    "student_id" => $userData->id,
                    // 'invoice_no' => strtoupper(randomId(1111, 9999, 'SVS' . date('dMy'), $usedInvoiceIdsNos)),
                    "invoice_no" => $invoice_no,
                    "roll_no" => $userData->roll_no,
                    "name" => $userData->name,
                    "standard" => $userData->standard,
                    "twe_group" => $userData->twe_group,
                    "sec" => $userData->sec,
                    "hostelOrDay" => $userData->hostelOrDay,
                    "sponser_id" => $userData->sponser_id,
                    "email" => $userData->email,
                    "fees_glance" => $data->fees_glance,
                    "fees_cat" => $data->fees_cat,
                    "fees_items_details" => json_encode(
                        $data->fees_items_details
                    ),
                    "discount_items_details" => json_encode($dis_items_details),
                    "actual_amount" => $data->amount,
                    "discount_percent" => $discountSum,
                    // 'amount' => (null !== $request->input('discount')) ? $item['amount'] - ($item['amount'] * ($request->input('discount') / 100)) : $item['amount'],
                    // 'amount' => (null !== $discountSum) ? ((int)$data->amount - (int)$discountSum)  :  (int)$data->amount,
                    "amount" => $amount,
                    "previous_pending_amount" => $pending_amount
                        ? $pending_amount
                        : 0,
                    "total_invoice_amount" => $total_invoice_amount,
                    "invoice_pending_amount" => $total_invoice_amount,
                    "date" => $data->date,
                    "acad_year" => $data->acad_year,
                    "due_date" => $data->due_date,
                    "payment_status" => $data->payment_status,
                    "created_by" => $data->created_by,
                ];

                try {
                    LifecycleLogger::log(
                        "Invoice Generated: {$invoice_no}",
                        $userData->id,
                        "invoice_generated",
                        [
                            "invoice_no" => $invoice_no,
                            "amount" => $amount,
                            "total_invoice_amount" => $total_invoice_amount,
                            "discount_percent" => $discountSum,
                            "standard" => $userData->standard,
                            "sec" => $userData->sec,
                            "group" => $userData->twe_group,
                            "acad_year" => $data->acad_year,
                            "payment_status" => $data->payment_status,
                        ]
                    );
                } catch (\Exception $e) {
                    \Log::error("Failed to log invoice generation lifecycle.", [
                        "invoice_no" => $invoice_no,
                        "student_id" => $userData->id,
                        "error" => $e->getMessage(),
                    ]);
                }
                $GenerateInvoiceView = GenerateInvoiceView::create(
                    $dataFeeMaps
                );
                if (!$GenerateInvoiceView || !$GenerateInvoiceView->slno) {
                    // Log or handle the error
                    throw new Exception(
                        "Invoice creation failed or returned null ID."
                    );
                }
                DB::table("by_pay_informations")->insert([
                    "student_id" => $userData->id,
                    "invoice_id" => $GenerateInvoiceView->slno, // Assuming this is the invoice reference
                    "transactionId" => $invoice_no,
                    "inv_amt" => $amount,
                    "due_amount" => $newDues,
                    "s_excess_amount" =>
                        $data->fees_cat === "school" ? $newExcess : 0, // Inline assignment for s_excess_amount
                    "h_excess_amount" =>
                        $data->fees_cat !== "school" ? $newExcess : 0,
                    "type" => $data->fees_cat, // Set accordingly
                    "payment_status" => $data->payment_status,
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);

                DB::table("generate_invoice_views")
                    ->where("student_id", $userData->id)
                    ->where("fees_cat", $data->fees_cat)
                    ->where("invoice_no", "!=", $invoice_no) // Exclude the new invoice
                    ->update(["disable" => 1]);
                DB::table("users")
                    ->where("id", $userData->id) // Specify the user ID you want to update
                    ->update([
                        "excess_amount" =>
                            $data->fees_cat === "school" ? $newExcess : 0,
                        "h_excess_amount" =>
                            $data->fees_cat !== "school" ? $newExcess : 0,
                        "updated_at" => now(), // Update the timestamp
                    ]);

                if ($GenerateInvoiceView) {
                  
                    if (!empty($discountId)) {
                        // Find the discount record
                        $discount = SchoolFeeDiscount::find($discountId);
                        if ($discount) {
                            // Update the status
                            $discount->status = 0;
                            $discount->save();
                        }
                    }
                    $concatenatedHeadingsresult = "";
                    foreach ($records as $record) {
                        // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                        $concatenatedHeadingsresult .=
                            $record->fee_heading .
                            "-" .
                            $record->fee_sub_heading .
                            "-" .
                            $record->amount .
                            "<br>";
                        $record->invoice_generated = 1;
                        $record->save();
                    }
                    foreach ($discountSum_l as $disrecord) {
                        $disrecord->status = 0;
                        $disrecord->save();
                    }
                    if ($cata == "school fees") {
                        $recordFs = FeesMap::where("status", "=", 1)
                            ->where("invoice_generated", "=", 0)
                            ->where("standard", "=", $request->input("std"))
                            ->where("fees_heading", "=", $request->input("cat"))
                            ->orderBy("id", "DESC")
                            ->get();
                        foreach ($recordFs as $recordF) {
                            // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                            $recordF->invoice_generated = 1;
                            $recordF->save();
                        }
                    } else {
                        $recordFs = FeesMap::where("status", "=", 1)
                            ->where("invoice_generated", "=", 0)
                            ->where("standard", "=", $request->input("std"))
                            ->where("fees_heading", "!=", "school fees")
                            ->orderBy("id", "DESC")
                            ->get();
                        foreach ($recordFs as $recordF) {
                            // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                            $recordF->invoice_generated = 1;
                            $recordF->save();
                        }
                    }
                }
            }
        }

        //////////////////end of each user //////////////////////end of each user////////////////////end of each user//////
        return response()->json([
            "data" => $dataFeeMaps,
            "concatenatedHeadingsresult" => $concatenatedHeadingsresult,
            "status" => "updated",
        ]);
    }
    ////////////////Prakash/////////////////////////////////////////////////////////////////////////////////////////////////////
    public function cashgenrate(Request $request)
    {
        //     $amount = $request->amount;
        //     $mode = $request->mode;
        //     $additionalDetails = $request->additionalDetails;

        //     $id = $request->id;

        //     $total_invoice_amount = $request->total_invoice_amount;

        //     $invoice_pending_amount = $request->invoice_pending_amount;
        //     $Invoice_no = $request->Invoice_no;
        //     $invoiceDetails = GenerateInvoiceView::find($id);
        //    if( isset($invoice_pending_amount) &&  $invoice_pending_amount != 0){
        //     $check = $amount -  $invoice_pending_amount ;}
        /////////////////////////////////////
        //////////////////////////////////////////
        // sponsor
        $amount = $request->amount;
        $sponsor = $request->sponsor;
        $mode = $request->mode;
        $additionalDetails = $request->additionalDetails;
        $id = $request->id;
        $excess_amount = null;
        $spexcess_amount = null;
        $invoiceDetails = GenerateInvoiceView::find($id);
        if (!$sponsor) {
            $excess_amount =
                User::where("id", $invoiceDetails->student_id)->value(
                    "excess_amount"
                ) ?? "";

            if ($excess_amount !== null && is_numeric($excess_amount)) {
                $amount += $excess_amount;
            }
        } else {
            $spexcess_amount = User::where("id", $request->sponsor)->first();
            if ($spexcess_amount->excess_amount < $amount) {
                return response()->json(
                    [
                        "message" =>
                            "Sponsor has less amount than the entered amount",
                    ],
                    422
                );
            }
        }
        if ($amount === null || $amount == 0) {
            return response()->json(
                ["message" => "Invalid amount. Please provide a valid amount."],
                422
            );
        }
        if ($invoiceDetails) {
            $totalInvoiceAmount = $invoiceDetails->total_invoice_amount;

            if (
                $invoiceDetails->invoice_pending_amount === null ||
                $invoiceDetails->invoice_pending_amount == 0 ||
                $invoiceDetails->invoice_pending_amount == 0.0
            ) {
                if ($spexcess_amount !== null) {
                    if ($totalInvoiceAmount < $amount) {
                        return response()->json(
                            [
                                "message" =>
                                    "Add Sponsor Excess amount first to the sponsor totalInvoiceAmount" .
                                    $totalInvoiceAmount .
                                    '$amount' .
                                    $amount,
                            ],
                            422
                        );
                    }
                }
                $totalInvoiceAmountActual = number_format(
                    $totalInvoiceAmount,
                    2,
                    ".",
                    ""
                );
                $amountActual = number_format($amount, 2, ".", "");
                if ($totalInvoiceAmountActual > $amountActual) {
                    $balance_amount = $totalInvoiceAmountActual - $amountActual;
                    $pending_amount = number_format(
                        $balance_amount,
                        2,
                        ".",
                        ""
                    );
                    $payment_status = "Partial Paid";
                    $upexcess_amount = 0;
                } elseif ($totalInvoiceAmountActual < $amountActual) {
                    $balance_amount = $amountActual - $totalInvoiceAmountActual;
                    $upexcess_amount = number_format(
                        $balance_amount,
                        2,
                        ".",
                        ""
                    );
                    $pending_amount = 0.0;
                    $payment_status = "Paid";
                } else {
                    $pending_amount = 0.0;
                    $payment_status = "Paid";
                    $upexcess_amount = 0;
                }

                $record = GenerateInvoiceView::where(
                    "invoice_no",
                    $invoiceDetails->invoice_no
                )->first();

                if (!$record) {
                    throw new \Exception(
                        "Invoice not found On cash payment line 695: " .
                            $invoiceDetails->invoice_no
                    );
                }

                // Update the invoice
                $record->update([
                    "payment_status" => $payment_status,
                    "invoice_status" => 4,
                    "paid_amount" => $amountActual,
                    "invoice_pending_amount" => $pending_amount,
                    "additionalDetails" => $additionalDetails,
                    "mode" => $mode,
                ]);

                // Generate Receipt ID using fees category from the original record
                $transactionId = FastInvoiceHelper::generateReceiptWithPrefix(
                    $record->fees_cat
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

                $save_payment_orders_status_data[
                    "clnt_txn_ref"
                ] = $transactionId;
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
                $save_payment_orders_status_data[
                    "payment_gatway_response"
                ] = null;
                $save_payment_orders_status_data["pay_res_updatedAt"] = date(
                    "Y-m-d H:i:s"
                );

                $PaymentOrdersStatus = PaymentOrdersStatuses::create(
                    $save_payment_orders_status_data
                );

                $dataInvoicePaymentMaps = [
                    "user_uuid" => $invoiceDetails->student_id, // user id need to mention here
                    "invoice_id" => $invoiceDetails->slno,
                    "payment_transaction_id" => $transactionId,
                    "status" => "success",
                    "transaction_completed_status" => 1,
                    "transaction_amount" =>
                        $invoiceDetails->total_invoice_amount,
                    "balance_amount" => $pending_amount,
                ];

                $GenerateInvoiceView = Invoice_list::create(
                    $dataInvoicePaymentMaps
                );

                if ($pending_amount) {
                    DB::table("invoice_pendings")->insert([
                        "fees_cat" => $invoiceDetails->fees_cat,
                        "student_id" => $invoiceDetails->student_id,
                        "invoice_no" => $invoiceDetails->invoice_no,
                        "pending_amount" => $pending_amount,
                    ]);
                }
                if ($excess_amount !== null) {
                    $user = User::where(
                        "id",
                        $invoiceDetails->student_id
                    )->first();
                    if ($user) {
                        // Update the excess amount
                        $user->excess_amount = "";
                        $user->save();
                    }
                }
                if ($spexcess_amount !== null) {
                    $spexcess_amount->excess_amount -= $amount;
                    $spexcess_amount->save();
                }

                if ($upexcess_amount) {
                    if ($excess_amount !== null) {
                        $user = User::where(
                            "id",
                            $invoiceDetails->student_id
                        )->first();
                        if ($user) {
                            // Update the excess amount
                            $user->excess_amount = $upexcess_amount;
                            $user->save();
                        }
                    } else {
                        $user = User::where(
                            "id",
                            $invoiceDetails->student_id
                        )->first();

                        if ($user) {
                            //   return response()->json(['excess_amount' =>$user->excess_amount,'upexcess_amount' =>$upexcess_amount]);
                            // Update the excess amount
                            if (empty($user->excess_amount)) {
                                $user->excess_amount = $upexcess_amount;
                                $user->save();
                            } else {
                                $user->excess_amount += $upexcess_amount;
                                $user->save();
                            }
                        }
                    }
                }
                DB::table("by_pay_informations")->insert([
                    "transactionId" => $transactionId,
                    "sponsor" => $sponsor ?? "",
                    "student_id" => $invoiceDetails->student_id,
                    "invoice_id" => $invoiceDetails->slno,
                    "amount" => $request->amount, ////changed from $amount in 31-07-24  due to student ledger incorrect statememt
                ]);
                return response()->json([
                    "students" => $invoiceDetails,
                    "message" => "amount successful",
                    "excess_amount" => $upexcess_amount ?? null,
                    "pending_amount" => $pending_amount ?? null,
                    "status" => $payment_status,
                ]);
            } else {
                if ($spexcess_amount !== null) {
                    // if ($invoiceDetails->invoice_pending_amount > $amount) {
                    if ($invoiceDetails->invoice_pending_amount < $amount) {
                        return response()->json(
                            [
                                "message" =>
                                    "Add Sponsor Excess amount fist to the sponsor" .
                                    $invoiceDetails->invoice_pending_amount .
                                    $amount,
                            ],
                            422
                        );
                    }
                }
                $totalInvoiceAmountActual = number_format(
                    $invoiceDetails->invoice_pending_amount,
                    2,
                    ".",
                    ""
                );
                $amountActual = number_format($amount, 2, ".", "");
                if ($totalInvoiceAmountActual > $amountActual) {
                    $balance_amount = $totalInvoiceAmountActual - $amountActual;
                    $pending_amount = number_format(
                        $balance_amount,
                        2,
                        ".",
                        ""
                    );
                    $payment_status = "Partial Paid";
                    $upexcess_amount = 0;
                } elseif ($totalInvoiceAmountActual < $amountActual) {
                    $balance_amount = $amountActual - $totalInvoiceAmountActual;
                    $upexcess_amount = number_format(
                        $balance_amount,
                        2,
                        ".",
                        ""
                    );
                    $pending_amount = 0.0;
                    $payment_status = "Paid";
                } else {
                    $pending_amount = 0.0;
                    $payment_status = "Paid";
                    $upexcess_amount = 0;
                }
                GenerateInvoiceView::where(
                    "invoice_no",
                    $invoiceDetails->invoice_no
                )->update([
                    "payment_status" => $payment_status,
                    "invoice_status" => 4,
                    "paid_amount" => $amountActual,
                    "invoice_pending_amount" => $pending_amount,
                    "additionalDetails" => $additionalDetails,
                    "mode" => $mode,
                ]);
                //New update
                $transactionId = randomId(); // randomId(1000,10000,'STU',['STU12345'])  use for string randomId

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

                $save_payment_orders_status_data[
                    "clnt_txn_ref"
                ] = $transactionId;
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
                $save_payment_orders_status_data[
                    "payment_gatway_response"
                ] = null;
                $save_payment_orders_status_data["pay_res_updatedAt"] = date(
                    "Y-m-d H:i:s"
                );

                $PaymentOrdersStatus = PaymentOrdersStatuses::create(
                    $save_payment_orders_status_data
                );

                $dataInvoicePaymentMaps = [
                    "user_uuid" => $invoiceDetails->student_id, // user id need to mention here
                    "invoice_id" => $invoiceDetails->slno,
                    "payment_transaction_id" => $transactionId,
                    "status" => "success",
                    "transaction_completed_status" => 1,
                    "transaction_amount" =>
                        $invoiceDetails->total_invoice_amount,
                    "balance_amount" => $pending_amount,
                ];

                $GenerateInvoiceView = Invoice_list::create(
                    $dataInvoicePaymentMaps
                );

                if ($pending_amount) {
                    // $invoiceDetails->invoice_no
                    // DB::table('invoice_pendings')->insert([
                    //     'fees_cat' => $invoiceDetails->fees_cat,
                    //     'student_id' => $invoiceDetails->student_id,
                    //     'invoice_no' => $invoiceDetails->invoice_no,
                    //     'pending_amount' => $pending_amount,
                    // ]);
                    DB::table("invoice_pendings")->updateOrInsert(
                        [
                            "invoice_no" => $invoiceDetails->invoice_no,
                        ],
                        [
                            "fees_cat" => $invoiceDetails->fees_cat,
                            "student_id" => $invoiceDetails->student_id,
                            "pending_amount" => $pending_amount,
                        ]
                    );
                }
                if ($excess_amount !== null) {
                    $user = User::where(
                        "id",
                        $invoiceDetails->student_id
                    )->first();
                    if ($user) {
                        // Update the excess amount
                        $user->excess_amount = "";
                        $user->save();
                    }
                }
                if ($spexcess_amount !== null) {
                    $spexcess_amount->excess_amount -= $amount;
                    $spexcess_amount->save();
                }
                if ($upexcess_amount) {
                    if ($excess_amount !== null) {
                        $user = User::where(
                            "id",
                            $invoiceDetails->student_id
                        )->first();
                        if ($user) {
                            // Update the excess amount
                            $user->excess_amount = $upexcess_amount;
                            $user->save();
                        }
                    } else {
                        $user = User::where(
                            "id",
                            $invoiceDetails->student_id
                        )->first();

                        if ($user) {
                            //   return response()->json(['excess_amount' =>$user->excess_amount,'upexcess_amount' =>$upexcess_amount]);
                            // Update the excess amount
                            if (empty($user->excess_amount)) {
                                $user->excess_amount = $upexcess_amount;
                                $user->save();
                            } else {
                                $user->excess_amount += $upexcess_amount;
                                $user->save();
                            }
                        }
                    }
                }
                DB::table("by_pay_informations")->insert([
                    "transactionId" => $transactionId,
                    "sponsor" => $sponsor ?? "",
                    "student_id" => $invoiceDetails->student_id,
                    "invoice_id" => $invoiceDetails->slno,
                    "amount" => $request->amount, ////changed from $amount in 31-07-24  due to student ledger incorrect statememt
                ]);
                return response()->json([
                    "students" => $invoiceDetails,
                    "message" => "amount successful",
                    "excess_amount" => $upexcess_amount ?? null,
                    "pending_amount" => $pending_amount ?? null,
                    "status" => $payment_status,
                ]);
            }
        } else {
            return response()->json(
                ["message" => "Invoice details not found."],
                404
            );
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function invoiceSearch(Request $request)
    {
        // Query to fetch all records
        $query = GenerateInvoiceView::query();

        // Get search parameters from the request if provided
        $invoiceNo = $request->input("invoice_no");
        $studentId = $request->input("student_id");
        $rollNo = $request->input("roll_no");
        $name = $request->input("name");
        $sponserId = $request->input("sponser_id");

        // Apply filters if search parameters are provided
        if ($invoiceNo) {
            $query->where("invoice_no", $invoiceNo);
        }
        if ($studentId) {
            $query->where("student_id", $studentId);
        }
        if ($rollNo) {
            $query->where("roll_no", $rollNo);
        }
        if ($name) {
            $query->where("name", "like", "%" . $name . "%");
        }
        if ($sponserId) {
            $query->where("sponser_id", $sponserId);
        }

        // Execute the query and get the results
        $options = $query
            ->orderBy("slno", "desc")
            ->get([
                "slno",
                "invoice_no",
                "student_id",
                "roll_no",
                "name",
                "sponser_id",
            ]);

        // Return the options as JSON
        return response()->json($options);
    }

    public function ReciptSearch(Request $request)
    {
        // Query to fetch all records from the by_pay_informations table
        $options = DB::table("by_pay_informations")->get();

        // Return the options as JSON
        return response()->json($options);
    }
    ///////////////////////////////////////////

    public function listgenrate(Request $request)
    {
        $dateFormat = "Y-m-d";
        $fromre = $request->input("fromDate");
        $tore = $request->input("toDate");

        $draw = $request->input("draw");
        $start = $request->input("start");
        $length = $request->input("length");

        $skip = $request->input("start") ?? 0;
        $take = $request->input("length") ?? 15;
        $searchTerm = $request->input("search.value") ?? "";

        $std = $request->input("std") ?? "";
        $org = $request->input("selectedGen") ?? "";

        if ($request->input("selectedGen") == "Organisation") {
            $org = 1;
        } else {
            $org = 0;
        }

        $studentsQuery = GenerateInvoiceView::orderBy("slno", "desc");

        // Conditionally add whereBetween if from and to are present
        // return response()->json(['data' => $fromre, $tore  ]);

        if ($fromre !== null && $tore !== null) {
            // Log the input values
            logger()->info("From input: " . $fromre);
            logger()->info("To input: " . $tore);

            $fromDate = Carbon::createFromFormat($dateFormat, $fromre);
            $toDate = Carbon::createFromFormat($dateFormat, $tore);
            $from = $fromDate->format("Y-m-d");
            $to = $toDate->format("Y-m-d");
            $studentsQuery->whereBetween(DB::raw("DATE(created_at)"), [
                $from,
                $to,
            ]);
            // return response()->json(['data' => $from,$to  ]);
            if ($std) {
                $studentsQuery->where("standard", $std);
            }

            if ($org == 1) {
                $studentsQuery->where("sponser_id", "!=", "");
            }
        } elseif ($fromre == null && $tore == null) {
            // $fromDate = Carbon::createFromFormat($dateFormat, $fromre);
            // $toDate = Carbon::createFromFormat($dateFormat, $tore);
            // $from = $fromDate->format('Y-m-d');
            // $to = $toDate->format('Y-m-d');
            //     $studentsQuery->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            //     // return response()->json(['data' => $from,$to  ]);
            if ($std) {
                $studentsQuery->where("standard", $std);
            }

            if ($org == 1) {
                $studentsQuery->where("sponser_id", "!=", "");
            }
        }
        if ($searchTerm) {
            $studentsQuery->where(function ($query) use ($searchTerm) {
                // Specify all columns for search
                $columns = [
                    "slno",
                    "invoice_no",
                    "student_id",
                    "roll_no",
                    "name",
                    "sec",
                    "hostelOrDay",
                    "sponser_id",
                    "email",
                    "standard",
                    "twe_group",
                    "actual_amount",
                    "amount",
                    "previous_pending_amount",
                    "total_invoice_amount",
                    "discount_percent",
                    "fees_glance",
                    "fees_cat",
                    "fees_items_details",
                    "discount_items_details",
                    "date",
                    "acad_year",
                    "due_date",
                    "cash_amount",
                    "paid_amount",
                    "invoice_pending_amount",
                    "payment_status",
                    "invoice_status",
                    "additionalDetails",
                    "mode",
                    "created_by",
                    "created_at",
                    "updated_at",
                ];

                foreach ($columns as $column) {
                    $query->orWhere($column, "like", "%" . $searchTerm . "%");
                }
                $query->orWhereHas("sponsors", function ($query) use (
                    $searchTerm
                ) {
                    $query->where("name", "like", "%" . $searchTerm . "%");
                });
            });
        }
        $count = $studentsQuery->count();

        $students = $studentsQuery
            ->skip($start)
            ->take($length)
            ->get();
        $studentst = $studentsQuery->get();

        $totalActualAmount = $studentst->sum("actual_amount");
        $totalAmount = $studentst->sum("amount");
        $totalDiscountPercent = $studentst->sum("discount_percent");
        // $totalPaidAmount = $studentst->sum('paid_amount');
        // $totalInvoicePendingAmount = $studentst->sum('invoice_pending_amount');
        // Calculate total paid amount from `by_pay_informations`
        $totalPaidAmount = ByPayInformation::whereIn(
            "invoice_id",
            $studentst->pluck("slno")
        )
            ->whereNotNull("inv_amt")
            ->sum("amount");

        // Retrieve the latest `due_amount` for each invoice ID
        $totalInvoicePendingAmount = ByPayInformation::whereIn(
            "invoice_id",
            $studentst->pluck("slno")
        )
            ->latest()
            ->value("due_amount");

        //  $count = GenerateInvoiceView::count();
        //  $count = GenerateInvoiceView::orderBy('slno', 'desc')
        // ->orderBy('slno', 'desc')
        // ->count();
        // $students = GenerateInvoiceView::orderBy('slno', 'desc')->get();

        foreach ($students as $key => $student) {
            // $student['checkbox'] = '<input type="checkbox" name="student_checkbox[]" value="' . $student['slno'] . '">';
            $student["checkbox"] =
                '<input
            type="checkbox"
            name="student_checkbox[]"
            value="' .
                $student["slno"] .
                '"
            onclick="handleCheckboxClick(\'' .
                $student["slno"] .
                '\')"
        />';

            $student["sponseruser_id"] = $student["sponser_id"];
            $student["sponser_name"] = optional(
                User::where("id", $student["sponser_id"])->first()
            )->name;
            $student["student_excess"] = optional(
                User::where("id", $student["student_id"])->first()
            )->excess_amount;

            $student["created_by"] = optional(
                User::where("id", $student["created_by"])->first()
            )->name;

            $student["sponser_id"] = optional(
                User::where("id", $student["sponser_id"])->first()
            )->name;

            if ($student["payment_status"] != "Invoice generated") {
                $invoice_id = $student["slno"];

                $invoiceLists = DB::table("invoice_lists")
                    ->where("invoice_id", "=", $invoice_id)
                    ->where("status", "=", "success")
                    ->get();

                $urls = [];

                foreach ($invoiceLists as $invoiceList) {
                    $paymentTransactionId =
                        $invoiceList->payment_transaction_id;
                    $url = "/PaymentReceipt/" . $paymentTransactionId;
                    $urls[] = $url;
                }

                $student["urls"] = $urls;
            } else {
                // Handle the case where payment_status is "Invoice generated"
                $student["urls"] = [];
            }

            // Replace the existing student record with the modified one
            $students[$key] = $student;
        }

        return response()->json([
            "data" => $students,
            "draw" => $draw,
            "recordsFiltered" => $count,
            "recordsTotal" => $count,
            "totals" => [
                "total_actual_amount" => $totalActualAmount,
                "total_amount" => $totalAmount,
                "total_discount_percent" => $totalDiscountPercent,
                "total_paid_amount" => $totalPaidAmount,
                "total_invoice_pending_amount" => $totalInvoicePendingAmount,
            ],
        ]);
    }

   
    public function listgenrateById(Request $request)
    {
        $id = $request->id;
        $students = GenerateInvoiceView::find($id);

        if ($students) {
            // $excessAmount = User::where('id', $students->student_id)->value('excess_amount');
            // $hexcessAmount = User::where('id', $students->student_id)->value('h_excess_amount');
            // $students->excessAmount = $excessAmount ?? '0';
            // $students->hexcessAmount = $hexcessAmount ?? '0';
            // return response()->json(['data' => $students]);
            $paymentInformation = DB::table("by_pay_informations")
                ->where("student_id", $students->student_id)
                ->where("type", $students->fees_cat)
                ->latest("id")
                ->first();
            $students->excessAmount =
                $paymentInformation->s_excess_amount ?? "0";
            $students->hexcessAmount =
                $paymentInformation->h_excess_amount ?? "0";
            $students->dueAmount = $paymentInformation->due_amount ?? "0";
            return response()->json(["data" => $students]);
        } else {
            return response()->json(["message" => "Record not found"], 404);
        }
    }

    public function listgenratefilter(Request $request)
    {
        //        return response()->json([
        //     'totals' => $request->all()
        // ]);

        $from = $request->input("from");
        $to = $request->input("to");
        $acad_year = $request->input("year");
        // Validate date format
        $dateFormat = "Y-m-d";
        if ($from !== null && $to !== null) {
            $fromDate = Carbon::createFromFormat($dateFormat, $from);
            $toDate = Carbon::createFromFormat($dateFormat, $to);
            $from = $fromDate->format("Y-m-d");
            $to = $toDate->format("Y-m-d");
        } else {
            $from = "";
            $to = "";
        }

        // if (!$fromDate || !$toDate) {
        //     return response()->json(['error' => 'Invalid date format. Please provide dates in the format DD/MM/YYYY.'], 400);
        // }
        //2023-06-20

        // Convert dates to the format used in the database

        $std = $request->input("std") ?? "";
        $org = $request->input("selectedGen") ?? "";

        if ($request->input("selectedGen") == "Organisation") {
            $org = 1;
        } else {
            $org = 0;
        }
        $query = GenerateInvoiceView::query();

        if ($from && $to && !$acad_year) {
            $query->whereBetween(DB::raw("DATE(created_at)"), [$from, $to]);

            if ($std) {
                $query->where("standard", $std);
            }

            if ($org == 1) {
                $query->where("sponser_id", "!=", "");
            }

            $query->orderByDesc("slno");
        } elseif (!$from && !$to && !$acad_year) {
            if ($std) {
                $query->where("standard", $std);
            }

            if ($org == 1) {
                $query->where("sponser_id", "!=", "");
            }

            $query->orderByDesc("slno");
        } elseif ($from && $to && $acad_year) {
            $query
                ->where("acad_year", $acad_year)
                ->whereBetween(DB::raw("DATE(created_at)"), [$from, $to])
                ->orderByDesc("slno");
        }

        $students = $query->get();
        // Calculate totals
        $totalActualAmount = $students->sum("actual_amount");
        $totalAmount = $students->sum("amount");
        $totalDiscountPercent = $students->sum("discount_percent");
        $totalPaidAmount = $students->sum("paid_amount");
        $totalInvoicePendingAmount = $students->sum("invoice_pending_amount");

        return response()->json([
            // 'data'=>$students,
            "totals" => [
                "total_actual_amount" => $totalActualAmount,
                "total_amount" => $totalAmount,
                "total_discount_percent" => $totalDiscountPercent,
                "total_paid_amount" => $totalPaidAmount,
                "total_invoice_pending_amount" => $totalInvoicePendingAmount,
            ],
        ]);
    }

    public function docGenerate(Request $request)
    {
        $cata = $request->input("cat");
        $fees_cat = $cata == "school fees" ? "school" : "other";
        $invoiceTypeString = $cata == "school fees" ? "S" : "O";

        $userDatas = User::where("status", "=", 1)
            ->where("standard", "=", $request->input("std"))
            ->where("user_type", "=", "student")
            ->when($request->has("stdid"), function ($query) use ($request) {
                $ids = $request->input("stdid");
                if (is_array($ids) && !empty($ids)) {
                    return $query->whereIn("id", $ids);
                }
                return $query;
            })
            ->get();
        // dd($userDatas);
        $invoiceData = [];

        foreach ($userDatas as $userData) {
            // dd($cata);

            unset($fees_items_details);
            $concatenatedHeadings = "";
            $fees_items_details = [];

            if ($cata == "school fees") {
                $records = StudentFeesMap::where("status", "=", 1)
                    ->where("invoice_generated", "=", 0)
                    ->where("student_id", "=", $userData->id)
                    ->where("standard", "=", $request->input("std"))
                    ->where("fee_heading", "=", $request->input("cat"))
                    ->orderBy("slno", "DESC")
                    ->get();
                // dd($userData->id,$records);

                if ($records) {
                    foreach ($records as $record) {
                        // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                        // $concatenatedHeadings .=  $record->fees_heading . '-' . $record->fees_sub_heading . '-' . $record->amount . '<br>';
                        $concatenatedHeadings .=
                            $record->fee_heading .
                            "[" .
                            $record->fee_sub_heading .
                            "]:  Rs." .
                            $record->amount .
                            "<br>";

                        $fees_items = (object) [];
                        $fees_items->fees_heading = $record->fee_heading;
                        $fees_items->fees_sub_heading =
                            $record->fee_sub_heading;
                        $fees_items->amount = $record->amount;
                        $fees_items->Priority = $record->Priority ?? "";
                        array_push($fees_items_details, $fees_items); // pusing each item to the array$userData->id
                    }
                }
            } else {
                $records = StudentFeesMap::where("status", "=", 1)
                    ->where("invoice_generated", "=", 0)
                    ->where("student_id", "=", $userData->id)
                    ->where("standard", "=", $request->input("std"))
                    ->where("fee_heading", "!=", "School Fees")
                    ->orderBy("slno", "DESC")
                    ->get();
        // dd($records,$userData->id,$request->input("std"));

                if ($records) {
                    foreach ($records as $record) {
                        // $concatenatedHeadings .= 'Fee Heading: ' . $record->fees_heading . ' - Subheading: ' . $record->fees_sub_heading . ' - Amount: ' . $record->amount . '<br>';
                        $concatenatedHeadings .=
                            $record->fee_heading .
                            "[" .
                            $record->fee_sub_heading .
                            "]:  Rs." .
                            $record->amount .
                            "<br>";
                        $fees_items = (object) [];
                        $fees_items->fees_heading = $record->fee_heading;
                        $fees_items->fees_sub_heading =
                            $record->fee_sub_heading;
                        $fees_items->amount = $record->amount;
                        $fees_items->Priority = $record->Priority ?? "";
                        array_push($fees_items_details, $fees_items); // pusing each item to the array
                    }
                }
            }
            //dd($fees_items_details);

            if (!empty($fees_items_details)) {
                $data = (object) [];
                $data->standard = $request->input("std");
                $data->twe_group = $request->input("group");
                $data->amount = $records->sum("amount");
                $data->fees_glance = $concatenatedHeadings;
                $data->fees_items_details = $fees_items_details;
                $data->fees_cat = $fees_cat;
                $data->date = date("d/m/Y");
                $data->acad_year = $record->acad_year;
                $data->due_date = $request->input("due_date");
                $data->payment_status = "Invoice generated";
                $data->created_by = $request->input("created_by");

                $invoice_lists = GenerateInvoiceView::select(
                    "invoice_no"
                )->get();
                $usedInvoiceIdsNos = [];
                $usedInvoiceIdsCount = $invoice_lists->count();
                if ($invoice_lists) {
                    foreach ($invoice_lists as $invoice_list) {
                        if ($invoice_list->invoice_no) {
                            array_push(
                                $usedInvoiceIdsNos,
                                strtoupper($invoice_list->invoice_no)
                            );
                        }
                    }
                }
                $currentYear = date("y");

                $year = date("y");
                $month = date("m");
                $rondomIdString = "GI" . $invoiceTypeString . $month . $year;

                $total_invoice_amount = 0;
                $pending_amount = 0;

                if ($cata == "school fees") {
                    $discountSum = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "school fees")
                        ->where("status", 1)
                        ->sum("dis_amount");
                    $discountSum_l = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "school fees")
                        ->where("status", 1)
                        ->get();
                } else {
                    $discountSum = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "!=", "school fees")
                        ->where("status", 1)
                        ->sum("dis_amount");
                    $discountSum_l = SchoolFeeDiscount::where(
                        "student_id",
                        $userData->id
                    )
                        ->where("invoicefeescat", "!=", "school fees")
                        ->where("status", 1)
                        ->get();
                }

                $dis_items_details = []; // Fees items details json for invoice print

                $amount =
                    null !== $discountSum
                        ? $data->amount - $discountSum
                        : $data->amount;

                $total_invoice_amount = $amount;

                foreach ($discountSum_l as $disrecord) {
                    $dis_items = (object) [];
                    $dis_items->discount_cat = $disrecord->discount_cat;
                    $dis_items->dis_amount = $disrecord->dis_amount;
                    array_push($dis_items_details, $dis_items);

                    $discountId = $disrecord->id;
                }

                do {
                    $randomNumber = rand(1111, 9999);
                    $invoice_no =
                        "SVS" . date("dMy") . $randomNumber . date("His");
                } while (in_array(strtoupper($invoice_no), $usedInvoiceIdsNos));

                $invoice_no = strtoupper($invoice_no);
                array_push($usedInvoiceIdsNos, $invoice_no);

                $dataFeeMaps = [
                    "student_id" => $userData->id,
                    // 'invoice_no' => strtoupper(randomId(1111, 9999, 'SVS' . date('dMy'), $usedInvoiceIdsNos)),
                    "invoice_no" => "INV" . date("my") . "....",
                    "roll_no" => $userData->roll_no,
                    "name" => $userData->name,
                    "standard" => $userData->standard,
                    "twe_group" => $userData->twe_group,
                    "sec" => $userData->sec,
                    "hostelOrDay" => $userData->hostelOrDay,
                    "sponser_name" => optional(
                        User::where("id", $userData->sponser_id)->first()
                    )->name,
                    "email" => $userData->email,
                    "fees_glance" => $data->fees_glance,
                    "fees_cat" => $data->fees_cat,
                    "fees_items_details" => json_encode(
                        $data->fees_items_details,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    ),
                    "discount_items_details" => implode(
                        ", ",
                        array_map(function ($item) {
                            // Convert object to array if necessary
                            $itemArray = is_object($item)
                                ? (array) $item
                                : $item;
                            return implode(
                                ", ",
                                array_map(
                                    fn($key, $value) => "$key: $value",
                                    array_keys($itemArray),
                                    $itemArray
                                )
                            );
                        }, $dis_items_details)
                    ),
                    "discount_items_details_org" => $dis_items_details,
                    "actual_amount" => $data->amount,
                    "discount_percent" => $discountSum,
                    "amount" => $amount,
                    "previous_pending_amount" => $pending_amount
                        ? $pending_amount
                        : 0,
                    "total_invoice_amount" => $total_invoice_amount,
                    "date" => $data->date,
                    "acad_year" => $data->acad_year,
                    "due_date" => $data->due_date,
                    "payment_status" => $data->payment_status,
                    "created_by" => $data->created_by,
                ];

                $invoiceData[] = $dataFeeMaps;
            }
        }
                // dd($invoiceData);

        return response()->json([
            "invoicedata" => $invoiceData,
        ]);
        //dd($invoiceData);

        //  return Excel::download(new InvoicesExport(collect($invoiceData)), 'invoices.xlsx');
    }
  
    public function cashgenratetwo(Request $request)
    {
        $amount = $request->amount;
        $sponsor = $request->sponsor;
        $mode = $request->mode;
        $additionalDetails = $request->additionalDetails;
        $id = $request->id;

        $invoiceDetails = GenerateInvoiceView::find($id);

        Log::info("1.invoiceDetails", ["invoiceDetails" => $invoiceDetails]);
        if (!$invoiceDetails) {
            return response()->json(
                ["message" => "Invoice details not found."],
                404
            );
        }

        // Generate a random transaction ID
        $transactionId = randomId();

        $student_excess_amount = 0;
        $sponsor_excess_amount = 0;
        $excess_amount_used = 0; // Variable to track excess amount used
        $advance_amount = 0; // Variable to track the advance amount
        $payed_amount = 0; // New variable to track actual amount paid towards the invoice
        $upexcess_amount = 0;
        $pending_amount = 0;
        $payment_status = "";
        $excess_source = "";

        $student = User::find($invoiceDetails->student_id);
        if (!$student) {
            return response()->json(["message" => "Student not found."], 404);
        }

        $paymentInformation = DB::table("by_pay_informations")
            ->where("student_id", $invoiceDetails->student_id)
            ->where("type", $invoiceDetails->fees_cat)
            ->latest("id")
            ->first();

        Log::info("1.2.paymentInformation", [
            "paymentInformation" => $paymentInformation,
        ]);
        Log::info("1.2.due_amount", [
            "due_amount" => $paymentInformation->due_amount,
        ]);

        $most_recent_dues = $paymentInformation->due_amount;
        $s_excess = $paymentInformation->s_excess_amount;
        $h_excess = $paymentInformation->h_excess_amount;
        // Handle the excess amount for student or sponsor
        if (!$sponsor) {

            //New Logic Start
            $most_recent_s_excess = 0;
            $most_recent_s_excess = 0;
            if ($paymentInformation->type === "school") {
                $most_recent_s_excess = $s_excess;
                $most_recent_h_excess = 0;
            } elseif ($paymentInformation->type === "other") {
                $most_recent_h_excess = $h_excess;
                $most_recent_s_excess = 0;
            } else {
                $most_recent_h_excess = 0;
                $most_recent_s_excess = 0;
            }

            $excessaddamount = (float)$amount + (float)$most_recent_s_excess + (float)$most_recent_h_excess;
            if ($excessaddamount < $most_recent_dues) {
                $dues = $most_recent_dues - $excessaddamount;
                $school_excess = 0;
                $hostel_excess = 0;
            } elseif ($excessaddamount > $most_recent_dues) {
                $dues = 0;
                // $excess = $excessaddamount - $most_recent_dues;
                if ($paymentInformation->type === "school") {
                    $school_excess = $excessaddamount - $most_recent_dues;
                    $hostel_excess = 0;
                } elseif ($paymentInformation->type === "other") {
                    $school_excess = 0;
                    $hostel_excess = $excessaddamount - $most_recent_dues;
                } else {
                    $school_excess = 0;
                    $hostel_excess = 0;
                }
            } else {
                $dues = 0;
                $school_excess = 0;
                $hostel_excess = 0;
            }

            // Student is paying
            if ($invoiceDetails->fees_cat === "school") {
                $student_excess_amount = (int) $student->excess_amount ?? 0;
                $excess_source = "excess_amount";
            } else {
                $student_excess_amount = (int) $student->h_excess_amount ?? 0;
                $excess_source = "h_excess_amount";
            }

            // Calculate the total amount after adding excess
            $totalInvoiceAmountActual = number_format(
                $invoiceDetails->total_invoice_amount,
                2,
                ".",
                ""
            );
            $amountWithExcess = number_format(
                $amount + $student_excess_amount,
                2,
                ".",
                ""
            );

            // If amount + excess exceeds the invoice amount
            if ($amountWithExcess > $totalInvoiceAmountActual) {
                // Excess amount used is 0
                $excess_amount_used = 0;

                // Advance amount will be the unused excess
                //  $advance_amount = $student_excess_amount;
                $advance_amount =
                    $amount - $invoiceDetails->invoice_pending_amount;
                // Payed amount is capped at totalInvoiceAmountActual
                $payed_amount = $totalInvoiceAmountActual;
            } else {
                // Use excess amount to cover the remaining amount
                $needed = $totalInvoiceAmountActual - $amount;
                $excess_amount_used = min($student_excess_amount, $needed);

                // Payed amount is the actual amount + the excess used
                $payed_amount = $amount + $excess_amount_used;

                $advance_amount = 0; // No advance if excess is used fully
            }
        } else {
            // Sponsor is paying (logic remains the same)
            $sponsorUser = User::find($sponsor);
            // dd($sponsorUser, "sponuser" , $sponsor,"sponser");
            Log::build([
                "driver" => "single",
                "path" => storage_path("logs/sponsor.log"),
                "level" => "info",
            ])->info("Sponsor Info:", [
                "sponsor initial" => $sponsorUser,
            ]);
            Log::info("2.sponsorUser", ["sponsorUser" => $sponsorUser]);

            if (!$sponsorUser) {
                return response()->json(
                    ["message" => "Sponsor not found."],
                    404
                );
            }
            Log::info("3.amount", [
                "amount" => $amount,
                "most_recent_dues" => $most_recent_dues,
            ]);
           
            if ($amount < $most_recent_dues) {
                // return response()->json(['amt' => $amount,'most' => $most_recent_dues,'jjj'=>($sponsorUser->excess_amount < $amount),'logic'=>'big'], 404);

                // $dues = $most_recent_dues - $amount;
                Log::info("4.AMOUNT < mOST RECENT");

                if ($invoiceDetails->fees_cat == "school") {
                    if (
                        $sponsorUser->excess_amount <= 0 ||
                        $sponsorUser->excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }

                    $student_excess_amount = $sponsorUser->excess_amount ?? 0;
                    $excess_source = "sponsor_excess_amount";
                    $sponsorUser->excess_amount -= $amount;
                } else {
                    if (
                        $sponsorUser->h_excess_amount <= 0 ||
                        $sponsorUser->h_excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }

                    $student_excess_amount = $sponsorUser->h_excess_amount ?? 0;
                    $excess_source = "sponsor_h_excess_amount";
                    $sponsorUser->h_excess_amount -= $amount;
                }
            } elseif ($amount > $most_recent_dues) {
                // $dues = $most_recent_dues - $amount;
                if ($invoiceDetails->fees_cat == "school") {
                    if (
                        $sponsorUser->excess_amount <= 0 ||
                        $sponsorUser->excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }
                    $excess_source = "sponsor_excess_amount";
                    $sponsorUser->excess_amount -= $amount;
                    $student_excess_amount = $student->excess_amount ?? 0;
                    // $studentupdate = User::find($invoiceDetails->student_id);
                    // if ($studentupdate) {
                    //     $remainingexcess = ($student_excess_amount ?? 0) + ($amount ?? 0) - ($most_recent_dues ?? 0);
                    //     $studentupdate->excess_amount = $remainingexcess;
                    //     $studentupdate->save();
                    // }
                    //  return response()->json(['message' =>   $student_excess_amount + $amount - $most_recent_dues], 201);
                    User::where("id", $invoiceDetails->student_id)->update([
                        "excess_amount" =>
                            $student_excess_amount +
                            $amount -
                            $most_recent_dues,
                    ]);
                } else {
                    if (
                        $sponsorUser->h_excess_amount <= 0 ||
                        $sponsorUser->excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }
                    $excess_source = "sponsor_h_excess_amount";
                    $sponsorUser->h_excess_amount -= $amount;
                    $student_excess_amount = $student->h_excess_amount ?? 0;
                    // $studentupdate = User::find($invoiceDetails->student_id);
                    // $remainingexcess = ($student_excess_amount ?? 0) + ($amount ?? 0) - ($most_recent_dues ?? 0);
                    // $studentupdate->h_excess_amount = $remainingexcess;
                    // $studentupdate->save();
                    User::where("id", $invoiceDetails->student_id)->update([
                        "h_excess_amount" =>
                            $student_excess_amount +
                            $amount -
                            $most_recent_dues,
                    ]);
                }
            } else {
                Log::info("5.AMOUNT == MOST RECENT AMOUNT = INVOICE AMOUNT ");

                if ($invoiceDetails->fees_cat == "school") {
                    if (
                        $sponsorUser->excess_amount <= 0 ||
                        $sponsorUser->excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }

                    $student_excess_amount = $sponsorUser->excess_amount ?? 0;
                    $excess_source = "sponsor_excess_amount";
                    $sponsorUser->excess_amount -= $amount;

                    // No extra excess to transfer to student, since it's exact match
                    // No update needed for student excess amount
                } else {
                    if (
                        $sponsorUser->h_excess_amount <= 0 ||
                        $sponsorUser->h_excess_amount < $amount
                    ) {
                        return response()->json(
                            ["message" => "Sponsor has insufficient funds."],
                            422
                        );
                    }

                    $student_excess_amount = $sponsorUser->h_excess_amount ?? 0;
                    $excess_source = "sponsor_h_excess_amount";
                    $sponsorUser->h_excess_amount -= $amount;

                    // No extra excess to transfer to student, since it's exact match
                    // No update needed for student h_excess_amount
                }
            }
            //  return response()->json(['message' =>   $student->excess_amount], 201);

            $excess_amount_used = $amount; // Track sponsor's excess used
            $payed_amount = $amount; // For sponsor, the payed amount is the full amount paid
            $sponsorUser->save();
            Log::build([
                "driver" => "single",
                "path" => storage_path("logs/sponsor.log"),
                "level" => "info",
            ])->info("Sponsor Info:", [
                "sponsor FINAL" => $sponsorUser,
            ]);
            if ($sponsor) {
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
                // $dues = $pending_amount;
            } else {
                // $dues = 0;
            }
            $excessaddamount = $amount;
            if ($excessaddamount < $most_recent_dues) {
                $dues = $most_recent_dues - $excessaddamount;
                // $school_excess = 0;
                // $hostel_excess = 0;
                Log::info("eXCESS<m_r_D");
            } elseif ($excessaddamount > $most_recent_dues) {
                $dues = 0;
                Log::info("eXCESS>m_r_D");

                // $excess = $excessaddamount - $most_recent_dues;
                //   $school_excess = 0;
                //     $hostel_excess = 0;
            } else {
                Log::info("eXCESS===m_r_D");
                $dues = 0;
                // $school_excess = 0;
                // $hostel_excess = 0;
            }
            Log::info("1.dues", ["dues" => $dues]);

            $school_excess = 0;
            $hostel_excess = 0;
            if ($invoiceDetails->fees_cat == "school") {
                if ($excessaddamount > $most_recent_dues) {
                    $school_excess = $excessaddamount - $most_recent_dues;
                    $hostel_excess = 0;
                    Log::info("2097.school_excess", [
                        "excessaddamount" => $excessaddamount,
                        "most_recent_dues" => $most_recent_dues,
                        "school_excess" => $school_excess,
                    ]);
                }
            } else {
                if ($excessaddamount > $most_recent_dues) {
                    $hostel_excess = $excessaddamount - $most_recent_dues;
                    $school_excess = 0;
                    Log::info("2105.hostel_excess", [
                        "excessaddamount" => $excessaddamount,
                        "most_recent_dues" => $most_recent_dues,
                        "hostel_excess" => $hostel_excess,
                    ]);
                }
            }
        }

        // Calculate pending amount by subtracting previous transactions
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
            $totalInvoiceAmountActual - ($previousTransactions + $payed_amount);


        if ($dues > 0) {
            // Partial payment
            $payment_status = "Partial Paid";
            if (!$sponsor) {
                if ($invoiceDetails->fees_cat == "school") {
                    $student->excess_amount =
                        $student_excess_amount -
                        $excess_amount_used +
                        $advance_amount;
                } else {
                    $student->h_excess_amount =
                        $student_excess_amount -
                        $excess_amount_used +
                        $advance_amount;
                }
                $student->save();
            }
        } elseif ($dues < 0) {
            // Overpayment, update advance amount
            $upexcess_amount = -$pending_amount;
            $payment_status = "Paid";
            if (!$sponsor) {
                if ($invoiceDetails->fees_cat == "school") {
                    $student->excess_amount =
                        $upexcess_amount + $advance_amount;
                } else {
                    $student->h_excess_amount =
                        $upexcess_amount + $advance_amount;
                }
                $student->save();
            }
        } else {
            // Full payment
            $payment_status = "Paid";
            if (!$sponsor) {
                if ($invoiceDetails->fees_cat == "school") {
                    $student->excess_amount = $advance_amount;
                } else {
                    $student->h_excess_amount = $advance_amount;
                }
                $student->save();
            }
        }

        // Create a new record in the Invoice_list table
        $dataInvoicePaymentMaps = [
            "user_uuid" => $invoiceDetails->student_id,
            "invoice_id" => $invoiceDetails->slno,
            "payment_transaction_id" => $transactionId,
            "status" => "success",
            "transaction_completed_status" => 1,
            "transaction_amount" => $payed_amount,
            "balance_amount" => $pending_amount,
        ];

        $GenerateInvoiceView = Invoice_list::create($dataInvoicePaymentMaps);
        GenerateInvoiceView::where(
            "invoice_no",
            $invoiceDetails->invoice_no
        )->update([
            "payment_status" => $payment_status,
            "invoice_status" => 4, // Assuming '4' means "Paid"
            "paid_amount" => number_format(
                ($invoiceDetails->paid_amount ?? 0) + $payed_amount,
                2,
                ".",
                ""
            ), // Correctly add paid amount
            // 'invoice_pending_amount' => number_format($pending_amount, 2, '.', ''), // Ensure pending amount is correctly formatted
            "invoice_pending_amount" => number_format($dues, 2, ".", ""), // Ensure pending amount is correctly formatted
            "additionalDetails" => $additionalDetails,
            "mode" => $mode,
            "due_amount" => $dues ?? null,
            "s_excess_amount" => $school_excess ?? null,
            "h_excess_amount" => $hostel_excess ?? null,
        ]);
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

        if ($invoiceDetails->fees_cat == "school") {
            $student_excess_amount = $student->excess_amount ?? 0;
            User::where("id", $invoiceDetails->student_id)->update([
                "excess_amount" => $student_excess_amount + $school_excess,
            ]);
        } else {
            $student_excess_amount = $student->h_excess_amount ?? 0;
            User::where("id", $invoiceDetails->student_id)->update([
                "h_excess_amount" => $student_excess_amount + $hostel_excess,
            ]);
        }


        /////////////////////////////////////////////////////////////////
        $LOG = [
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
        ];
        try {
            $payer = $request->sponsor ? "Sponsor" : "Parent";

            LifecycleLogger::log(
                "Receipt Generated for Invoice: {$invoiceDetails->invoice_no}",
                $invoiceDetails->student_id,
                "receipt_generated",
                [
                    "invoice_no" => $invoiceDetails->invoice_no,
                    "paid_amount" => $request->amount,
                    "payment_mode" => "Offline",
                    "payer" => $payer,
                    "transaction_id" => $transactionId ?? null,
                ]
            );
        } catch (\Exception $e) {
            \Log::error("Failed to log receipt generation lifecycle.", [
                "invoice_no" => $invoiceDetails->invoice_no,
                "student_id" => $invoiceDetails->student_id,
                "error" => $e->getMessage(),
            ]);
        }

        Log::info("Inserting into by_pay_informations table", $LOG);

        // Get the student's email
        $studentEmail = User::find($invoiceDetails->student_id)->value("email");

        // Generate the download link
        $downloadLink =
            "http://santhoshavidhyalaya.com/svsportaladmintest/PaymentReceipt12345678912345678" .
            "/$transactionId";

        // Queue the email with the download link
        //Mail::to($invoiceDetails->email)->queue(new PaymentReceiptMail($invoiceDetails, $downloadLink,$amount,$payment_status,$transactionId));
        //Mail::to('s.harikiran@eucto.com')->queue(new PaymentReceiptMail($invoiceDetails, $downloadLink,$amount,$payment_status,$transactionId));

        return response()->json([
            "message" => "Payment processed successfully.",
            "pending_amount" => $pending_amount,
            "payment_status" => $payment_status,
            "excess_used" => $excess_amount_used, // Return the amount of excess used
            "advance_amount" => $advance_amount, // Return advance amount if any
            "payed_amount" => $payed_amount, // Return actual amount paid towards the invoice
            "excess_source" => $excess_source,
        ]);
    }
    public function deletereciptview(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData["transactionId"])) {
            $transactionId = $requestData["transactionId"];

            // Retrieve the record from by_pay_informations table before deleting
            $invoiceDetails = DB::table("by_pay_informations")
                ->where("transactionId", $transactionId)
                ->first();
            return response()->json([
                "receipt" => $invoiceDetails,
            ]);
        }

        return response()->json(
            [
                "message" => "Transaction ID not provided.",
            ],
            400
        );
    }
    public function deleteinvoiceview(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData["slno"])) {
            $slno = $requestData["slno"];

            // Retrieve the record from by_pay_informations table before deleting
            $invoiceDetails = DB::table("by_pay_informations")
                ->where("invoice_id", $slno)
                ->get();

            return response()->json([
                "receipt" => $invoiceDetails,
            ]);
        }

        return response()->json(
            [
                "message" => "Transaction ID not provided.",
            ],
            400
        );
    }

    public function deleteinvoicetwo(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData["slno"])) {
            $slno = $requestData["slno"];

            // Retrieve the record from invoice_lists
            $invoiceDetails = Invoice_list::where("invoice_id", $slno)->first();

            // Retrieve all records from by_pay_informations
            $byPayInformations = DB::table("by_pay_informations")
                ->where("invoice_id", $slno)
                ->get();

            if ($byPayInformations->isEmpty()) {
                // If no payment information is found, delete only the invoice and the GenerateInvoiceView
                GenerateInvoiceView::where("slno", $slno)->delete();
                Invoice_list::where("invoice_id", $slno)->delete();

                return response()->json([
                    "status" => "success",
                    "message" => "Invoice deleted as no receipts were found.",
                ]);
            }

            // Initialize total amounts for summing across multiple receipts
            $totalAmount = 0;
            $totalStudentExcess = 0;
            $totalSponsorExcess = 0;

            // Sum the amounts for each by_pay_informations record
            foreach ($byPayInformations as $byPayInfo) {
                $totalAmount += $byPayInfo->amount ?? 0;
                $totalStudentExcess += $byPayInfo->student_excess_amount ?? 0;
                $advance_payments += $byPayInfo->advance_payment ?? 0;
                $totalSponsorExcess += $byPayInfo->sponsor_excess_amount ?? 0;
            }

            // Retrieve and update student or sponsor information
            $student = User::find($byPayInformations[0]->student_id); // Assuming all records have the same student_id
            if (!$student) {
                return response()->json(
                    ["message" => "Student not found."],
                    404
                );
            }

            // Handling school fees category + $totalAmount
            if ($Genref->fees_cat == "school") {
                if (!$byPayInformations[0]->sponsor) {
                    $student->excess_amount = number_format(
                        floatval($student->excess_amount ?? 0) +
                            $totalStudentExcess -
                            $advance_payments,
                        2,
                        ".",
                        ""
                    );
                } else {
                    $sponsor = User::find($byPayInformations[0]->sponsor);
                    if ($sponsor) {
                        $sponsor->excess_amount = number_format(
                            floatval($sponsor->excess_amount ?? 0) +
                                $totalSponsorExcess,
                            2,
                            ".",
                            ""
                        );
                        $sponsor->save();
                    }
                }
            } else {
                if (!$byPayInformations[0]->sponsor) {
                    $student->h_excess_amount = number_format(
                        floatval($student->h_excess_amount ?? 0) +
                            $totalStudentExcess -
                            $advance_payments,
                        2,
                        ".",
                        ""
                    );
                } else {
                    $sponsor = User::find($byPayInformations[0]->sponsor);
                    if ($sponsor) {
                        $sponsor->h_excess_amount = number_format(
                            floatval($sponsor->h_excess_amount ?? 0) +
                                $totalSponsorExcess,
                            2,
                            ".",
                            ""
                        );
                        $sponsor->save();
                    }
                }
            }

            // Save the updated student record
            $student->save();

            // Insert the record into deletedreceipts and delete related records
            foreach ($byPayInformations as $byPayInfo) {
                DB::table("deletedreceipts")->insert([
                    "user_uuid" => $invoiceDetails->user_uuid,
                    "invoice_id" => $invoiceDetails->invoice_id,
                    "payment_transaction_id" => $byPayInfo->transactionId,
                    "transaction_amount" => $byPayInfo->amount,
                    "balance_amount" => $invoiceDetails->balance_amount,
                    "status" => $invoiceDetails->status,
                    "transaction_completed_status" =>
                        $invoiceDetails->transaction_completed_status,
                    "created_at" => $invoiceDetails->created_at,
                    "updated_at" => $invoiceDetails->updated_at,
                ]);

                // Delete related records for each receipt
                DB::table("by_pay_informations")
                    ->where("transactionId", $byPayInfo->transactionId)
                    ->delete();
                PaymentOrdersDetails::where(
                    "internal_txn_id",
                    $byPayInfo->transactionId
                )->delete();
                PaymentOrdersStatuses::where(
                    "clnt_txn_ref",
                    $byPayInfo->transactionId
                )->delete();
                Invoice_list::where(
                    "payment_transaction_id",
                    $byPayInfo->transactionId
                )->delete();
            }

            // Finally, delete the invoice and GenerateInvoiceView
            GenerateInvoiceView::where("slno", $slno)->delete();

            return response()->json([
                "status" => "success",
                "message" =>
                    "Invoice and related receipts deleted successfully.",
            ]);
        }

        return response()->json(
            ["message" => "Transaction ID not provided."],
            400
        );
    }
    public function deleteinvoice(Request $request)
    {
        // Get all request data
        $requestData = $request->all();

        // Check if 'slno' is present in the request
        if (isset($requestData["slno"])) {
            $slno = $requestData["slno"];

            // Check the disable status for the invoice in GenerateInvoiceView
            $invoice = GenerateInvoiceView::where("slno", $slno)->first();

            if ($invoice && $invoice->disable == 0) {
                // Start a transaction to ensure both deletions occur together
                DB::beginTransaction();

                try {
                    $studentId = $invoice->student_id;
                    $feesCat = $invoice->fees_cat;

                    // Delete the record from GenerateInvoiceView
                    $invoice->delete();

                    // Delete corresponding records from ByPayInformation where 'invoice_id' matches 'slno'
                    ByPayInformation::where("invoice_id", $slno)->delete();

                    $latestInvoice = GenerateInvoiceView::where(
                        "student_id",
                        $studentId
                    )
                        ->where("fees_cat", $feesCat)
                        ->orderBy("updated_at", "desc")
                        ->first();

                    // If a latest invoice is found, update its disable status to 0
                    if ($latestInvoice) {
                        $latestInvoice->disable = 0;
                        $latestInvoice->save();
                    }

                    // Commit the transaction if successful
                    DB::commit();

                    return response()->json([
                        "status" => "success",
                        "message" =>
                            "Invoice and corresponding payment information deleted successfully.",
                    ]);
                } catch (\Exception $e) {
                    // Rollback the transaction if something went wrong
                    DB::rollBack();

                    return response()->json(
                        [
                            "status" => "error",
                            "message" =>
                                "An error occurred: " . $e->getMessage(),
                        ],
                        500
                    );
                }
            } else {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Invoice is disabled or does not exist.",
                    ],
                    400
                );
            }
        }

        return response()->json(
            [
                "status" => "error",
                "message" => "slno not provided.",
            ],
            400
        );
    }

    public function deleterecipt(Request $request)
    {
        // Get the request data
        $requestData = $request->all();

        // Check if the 'id' or 'transactionId' is provided
        if (isset($requestData["transactionId"])) {
            $receiptId = $requestData["transactionId"];

            // Find the receipt in the 'by_pay_informations' table
            $receipt = ByPayInformation::where(
                "transactionId",
                $receiptId
            )->first();

            // Check if the receipt exists
            if (!$receipt) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Receipt not found.",
                    ],
                    404
                );
            }

            // Check if it's a receipt by confirming that 'inv_amt' is null
            if (!is_null($receipt->inv_amt)) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "This is not a receipt record.",
                    ],
                    400
                );
            }

            // Check if this is the latest receipt
            $latestReceipt = ByPayInformation::where(
                "student_id",
                $receipt->student_id
            )
                ->where("type", $receipt->type)
                ->whereNull("inv_amt") // Ensure it's a receipt
                ->orderBy("created_at", "desc")
                ->first();

            // Ensure the receipt being deleted is the latest one
            if ($latestReceipt && $latestReceipt->id != $receipt->id) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Only the latest receipt can be deleted.",
                    ],
                    400
                );
            }

            // Proceed to delete the receipt and related records
            try {
                // Check if 'transactionId' is provided in the request
                if (isset($requestData["transactionId"])) {
                    $transactionId = $requestData["transactionId"];

                    // Delete related records from multiple tables based on the transactionId
                    DB::table("by_pay_informations")
                        ->where("transactionId", $transactionId)
                        ->delete();
                    if ($receipt->sponsor && $receipt->sponsor != null) {
                        $amt = $receipt->amount;
                        $sponsor = User::where(
                            "id",
                            $receipt->sponsor
                        )->first();

                        if ($receipt->type == "school") {
                            $sponsor["excess_amount"] += floatval($amt);
                            $sponsor->save();
                        } else {
                            $sponsor["h_excess_amount"] += floatval($amt);
                            $sponsor->save();
                        }
                    }
                    PaymentOrdersDetails::where(
                        "internal_txn_id",
                        $transactionId
                    )->delete();

                    PaymentOrdersStatuses::where(
                        "clnt_txn_ref",
                        $transactionId
                    )->delete();

                    Invoice_list::where(
                        "payment_transaction_id",
                        $transactionId
                    )->delete();

                    $ByPayResult = DB::table("by_pay_informations")
                        ->whereNull("inv_amt")
                        ->where("invoice_id", $receipt->invoice_id)
                        ->latest("id")
                        ->first();

                    if ($ByPayResult != "") {
                        if (
                            $ByPayResult->due_amount != "" ||
                            $ByPayResult->due_amount != 0
                        ) {
                            $payment_status = "Partial Paid";
                            $invoice_status = 4;
                        } else {
                            $payment_status = "Paid";
                            $invoice_status = 4;
                        }
                    } else {
                        $payment_status = "Invoice generated";
                        $invoice_status = 1;
                    }
                    $updateResult = GenerateInvoiceView::where(
                        "slno",
                        $latestReceipt->invoice_id
                    )->update([
                        "payment_status" => $payment_status,
                        "invoice_status" => $invoice_status,
                    ]);
                } else {
                    // Only delete the receipt if no transactionId is provided
                    $receipt->delete();
                }

                return response()->json([
                    "status" => "success",
                    "message" =>
                        "Receipt and related records deleted successfully.",
                    "sponsor" => $sponsor,
                    "amt" => $amt,
                ]);
            } catch (\Exception $e) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "An error occurred: " . $e->getMessage(),
                    ],
                    500
                );
            }
        }

        return response()->json(
            [
                "status" => "error",
                "message" => "Receipt ID not provided.",
            ],
            400
        );
    }

    public function deleterecipttwo(Request $request)
    {
        $requestData = $request->all();

        if (isset($requestData["transactionId"])) {
            $transactionId = $requestData["transactionId"];

            // Retrieve the record from invoice_lists
            $invoiceDetails = Invoice_list::where(
                "payment_transaction_id",
                $transactionId
            )->first();
            if (!$invoiceDetails) {
                return response()->json(
                    ["message" => "Invoice not found."],
                    404
                );
            }

            // Retrieve the record from by_pay_informations
            $byPayInformations = DB::table("by_pay_informations")
                ->where("transactionId", $transactionId)
                ->first();
            if (!$byPayInformations) {
                return response()->json(
                    ["message" => "Payment information not found."],
                    404
                );
            }

            // Retrieve GenerateInvoiceView record
            $Genref = GenerateInvoiceView::where(
                "slno",
                $invoiceDetails->invoice_id
            )->first();
            if (!$Genref) {
                return response()->json(
                    ["message" => "GenerateInvoiceView not found."],
                    404
                );
            }

            // Calculate updated amounts
            $updatedPaidAmount = number_format(
                ($Genref->paid_amount ?? 0) - ($byPayInformations->amount ?? 0),
                2,
                ".",
                ""
            );
            $updatedPendingAmount = number_format(
                ($Genref->invoice_pending_amount ?? 0) +
                    ($byPayInformations->amount ?? 0),
                2,
                ".",
                ""
            );

            // Update the GenerateInvoiceView record
            $updateResult = GenerateInvoiceView::where(
                "slno",
                $invoiceDetails->invoice_id
            )->update([
                "payment_status" => "Partial Paid",
                "invoice_status" => 4, // Assuming '4' means "Paid"
                "paid_amount" => $updatedPaidAmount,
                "invoice_pending_amount" => $updatedPendingAmount,
            ]);

            if (!$updateResult) {
                return response()->json(
                    ["message" => "Failed to update invoice details."],
                    500
                );
            }

            // Retrieve and update student or sponsor
            $student = User::find($byPayInformations->student_id);
            if (!$student) {
                return response()->json(
                    ["message" => "Student not found."],
                    404
                );
            }

            if ($Genref->fees_cat == "school") {
                if (!$byPayInformations->sponsor) {
                    // Update student excess amount
                    $student->excess_amount = number_format(
                        ($student->excess_amount ?? 0) +
                            ($byPayInformations->student_excess_amount ?? 0) -
                            ($byPayInformations->advance_payment ?? 0),
                        2,
                        ".",
                        ""
                    );
                } else {
                    // Update sponsor excess amount
                    $sponsor = User::find($byPayInformations->sponsor);
                    if ($sponsor) {
                        $sponsor->excess_amount = number_format(
                            ($sponsor->excess_amount ?? 0) +
                                ($byPayInformations->sponsor_excess_amount ??
                                    0),
                            2,
                            ".",
                            ""
                        );
                        $sponsor->save();
                    }
                }
            } else {
                if (!$byPayInformations->sponsor) {
                    // Update student h_excess amount
                    $student->h_excess_amount = number_format(
                        ($student->h_excess_amount ?? 0) +
                            ($byPayInformations->student_excess_amount ?? 0) -
                            ($byPayInformations->advance_payment ?? 0),
                        2,
                        ".",
                        ""
                    );
                } else {
                    // Update sponsor h_excess amount
                    $sponsor = User::find($byPayInformations->sponsor);
                    if ($sponsor) {
                        $sponsor->h_excess_amount = number_format(
                            ($sponsor->h_excess_amount ?? 0) +
                                ($byPayInformations->sponsor_excess_amount ??
                                    0),
                            2,
                            ".",
                            ""
                        );
                        $sponsor->save();
                    }
                }
            }

            // Save the student record
            $student->save();

            // Insert the record into deletedreceipts
            DB::table("deletedreceipts")->insert([
                "user_uuid" => $invoiceDetails->user_uuid,
                "invoice_id" => $invoiceDetails->invoice_id,
                "payment_transaction_id" =>
                    $invoiceDetails->payment_transaction_id,
                "transaction_amount" => $invoiceDetails->transaction_amount,
                "balance_amount" => $invoiceDetails->balance_amount,
                "status" => $invoiceDetails->status,
                "transaction_completed_status" =>
                    $invoiceDetails->transaction_completed_status,
                "created_at" => $invoiceDetails->created_at,
                "updated_at" => $invoiceDetails->updated_at,
            ]);

            // Delete related records from other tables
            DB::table("by_pay_informations")
                ->where("transactionId", $transactionId)
                ->delete();

            PaymentOrdersDetails::where(
                "internal_txn_id",
                $transactionId
            )->delete();

            PaymentOrdersStatuses::where(
                "clnt_txn_ref",
                $transactionId
            )->delete();

            Invoice_list::where(
                "payment_transaction_id",
                $transactionId
            )->delete();

            return response()->json([
                "transactionId" => $transactionId,
                "status" => "success",
            ]);
        }

        return response()->json(
            ["message" => "Transaction ID not provided."],
            400
        );
    }
}
