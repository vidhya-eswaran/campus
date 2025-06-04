<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SponserMaster;
use App\Models\Invoice_list;

use Illuminate\Support\Facades\Validator;
use App\Models\GenerateInvoiceView;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentOrdersDetails;
use App\Models\ByPayInformation;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;

use App\Mail\InvoiceGenerated;

use Illuminate\Support\Facades\Log; // Add this import statement
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Else_;
use App\Models\PaymentOrdersStatuses;
use Carbon\Carbon;

class ReportsController extends Controller
{

    public function StudentLedger(Request $request)
    {
        // Check if FromDate and ToDate are present in the request
        if (!$request->has("FromDate") || !$request->has("ToDate")) {
            // If any of them is missing, return an error response
            return response()->json(
                ["error" => "FromDate and ToDate are required."],
                400
            );
        }
        $requestData = $request->all();
        $responseData = ["H" => [], "S" => []];

        //  return response()->json(['error' => $requestData], 500);
        if (
            $request->has("AdmissionNo") &&
            $request->input("AdmissionNo") !== null
        ) {
            $admissionNo = $request->input("AdmissionNo");
            $user = User::where("admission_no", $admissionNo)->first();
            $student = Student::where("roll_no", $user->roll_no)
                ->orderBy("created_at", "desc")
                ->first();

            if (
                !$user &&
                (!$request->has("Recieptsid") ||
                    empty($request->input("Recieptsid"))) &&
                (!$request->has("Invoiceid") ||
                    empty($request->input("Invoiceid")))
            ) {
                return response()->json(["error" => "User not found."], 404);
            }

            $userId = $user->id;
        }
        if ($request->has("FromDate") && $request->has("ToDate")) {
            $fromDate = Carbon::parse(
                $request->input("FromDate")
            )->startOfDay();
            $toDate = Carbon::parse($request->input("ToDate"))->endOfDay();

            if (
                $request->has("ReceiptorInvoice") &&
                !empty($request->input("ReceiptorInvoice")) &&
                $request->input("ReceiptorInvoice") === "invoice" &&
                !empty($userId)
            ) {
                $invoiceRecordsO = GenerateInvoiceView::where(
                    "created_at",
                    ">=",
                    $fromDate
                )
                    ->where("created_at", "<=", $toDate)
                    ->where("student_id", $userId)
                    ->where("fees_cat", "other")
                    ->get();

                foreach ($invoiceRecordsO as $invoice) {
                    // Initialize $byPayInformations to avoid undefined variable issues
                    $byPayInformations = [];

                    // Check if any associated byPayInformations exist for this invoice
                    if ($invoice->byPayInformations()->exists()) {
                        $byPayInformations = $invoice->byPayInformations;

                        // Loop through each ByPayInformation object
                        foreach ($byPayInformations as $byPayInformation) {
                            // Get the sponsor value from the current ByPayInformation record
                            $sponsorValue = $byPayInformation->sponsor;

                            // If the sponsor value is not null, find the corresponding user
                            if (isset($sponsorValue) && !empty($sponsorValue)) {
                                $sponsorUser = User::where(
                                    "id",
                                    $sponsorValue
                                )->first();

                                // If a user with the sponsor value is found, add the sponsor information directly to the ByPayInformation object
                                if ($sponsorUser) {
                                    $byPayInformation->sponsor_info = $sponsorUser;
                                }
                            }
                        }
                    }

                    // Add each invoice and its associated receipts to the responseData array
                    $responseData["H"][] = [
                        "invoice" => $invoice,
                        "receipts" => $byPayInformations, // If no byPayInformations exist, this will be an empty array
                        "student" => $user,
                        "studentmaster" => $student,
                    ];
                }

                $invoiceRecordsS = GenerateInvoiceView::where(
                    "created_at",
                    ">=",
                    $fromDate
                )
                    ->where("created_at", "<=", $toDate)
                    ->where("student_id", $userId)
                    ->where("fees_cat", "school")
                    ->get();

                foreach ($invoiceRecordsS as $invoice) {
                    // Initialize $byPayInformations to avoid undefined variable issues
                    $byPayInformations = [];

                    // Check if any associated byPayInformations exist for this invoice
                    if ($invoice->byPayInformations()->exists()) {
                        $byPayInformations = $invoice->byPayInformations;

                        // Loop through each ByPayInformation object
                        foreach ($byPayInformations as $byPayInformation) {
                            // Get the sponsor value from the current ByPayInformation record
                            $sponsorValue = $byPayInformation->sponsor;

                            // If the sponsor value is not null, find the corresponding user
                            if ($sponsorValue !== null) {
                                $sponsorUser = User::where(
                                    "id",
                                    $sponsorValue
                                )->first();

                                // If a user with the sponsor value is found, add the sponsor information directly to the ByPayInformation object
                                if ($sponsorUser) {
                                    $byPayInformation->sponsor_info = $sponsorUser;
                                }
                            }
                        }
                    }

                    // Add each invoice and its associated receipts to the responseData array
                    $responseData["S"][] = [
                        "invoice" => $invoice,
                        "receipts" => $byPayInformations, // If no byPayInformations exist, this will be an empty array
                        "student" => $user,
                        "studentmaster" => $student,
                    ];
                }

                // Return the response after processing all the invoice records
                return response()->json(["data" => $responseData], 200);
            } elseif (
                $request->has("ReceiptorInvoice") &&
                $request->input("ReceiptorInvoice") === "receipt" &&
                !empty($userId)
            ) {
                // Filter ByPayInformation records based on the date range and student_id
                $receipts = ByPayInformation::where(
                    "created_at",
                    ">=",
                    $fromDate
                )
                    ->where("created_at", "<=", $toDate)
                    ->where("student_id", $userId)
                    ->get();

                // Get unique invoice IDs from the filtered receipts
                $invoiceIds = $receipts
                    ->pluck("invoice_id")
                    ->unique()
                    ->toArray();

                // Fetch GenerateInvoiceView records associated with the invoice IDs
                $invoiceRecordsS = GenerateInvoiceView::whereIn(
                    "slno",
                    $invoiceIds
                )
                    ->where("fees_cat", "school")
                    ->get();

                // Initialize an empty array to store the response data
                $responseData = [];

                // Iterate over each invoice record
                foreach ($invoiceRecordsS as $invoice) {
                    // Filter receipts associated with the current invoice
                    $filteredReceipts = $receipts
                        ->where("invoice_id", $invoice->slno)
                        ->values()
                        ->all();

                    // Retrieve sponsor information for each receipt
                    foreach ($filteredReceipts as $byPayInformation) {
                        // Get the sponsor value from the current ByPayInformation record
                        $sponsorValue = $byPayInformation->sponsor;

                        // If the sponsor value is not null, find the corresponding user
                        if ($sponsorValue !== null) {
                            $sponsorUser = User::find($sponsorValue);

                            // If a user with the sponsor value is found, add the sponsor information to the ByPayInformation object
                            if ($sponsorUser) {
                                $byPayInformation->sponsor_info = $sponsorUser;
                            }
                        }
                    }

                    // Add the invoice and its associated receipts to the response data
                    $responseData["S"][] = [
                        // Changed from $responseData['S'] = to $responseData['S'][] = to ensure multiple entries
                        "invoice" => $invoice,
                        "receipts" => $filteredReceipts, // If no receipts exist, this will be an empty array
                        "student" => $user,
                        "studentmaster" => $student,
                    ];
                }

                $invoiceRecordsO = GenerateInvoiceView::whereIn(
                    "slno",
                    $invoiceIds
                )
                    ->where("fees_cat", "other")
                    ->get();

                foreach ($invoiceRecordsO as $invoice) {
                    // Filter receipts associated with the current invoice
                    $filteredReceipts = $receipts
                        ->where("invoice_id", $invoice->slno)
                        ->values()
                        ->all();

                    // Retrieve sponsor information for each receipt
                    foreach ($filteredReceipts as $byPayInformation) {
                        // Get the sponsor value from the current ByPayInformation record
                        $sponsorValue = $byPayInformation->sponsor;

                        // If the sponsor value is not null, find the corresponding user
                        if ($sponsorValue !== null) {
                            $sponsorUser = User::find($sponsorValue);

                            // If a user with the sponsor value is found, add the sponsor information to the ByPayInformation object
                            if ($sponsorUser) {
                                $byPayInformation->sponsor_info = $sponsorUser;
                            }
                        }
                    }

                    // Add the invoice and its associated receipts to the response data
                    $responseData["H"][] = [
                        // Use [] to append instead of overwriting
                        "invoice" => $invoice,
                        "receipts" => $filteredReceipts, // If no receipts exist, this will be an empty array
                        "student" => $user,
                        "studentmaster" => $student,
                    ];
                }

                // Return the response
                return response()->json(["data" => $responseData], 200);
            } elseif ($request->has("ReceiptorInvoice") && empty($userId)) {
                // AdmissionNo is not provided, check for standard
                $standard = $request->input("Std");
                // Determine whether to fetch invoices, receipts, or both
                $receiptOrInvoice = $request->input("ReceiptorInvoice");
                // If standard is provided, fetch all students of that standard
                if ($standard) {
                    // Fetch all students for the provided standard
                    $students = User::where("standard", $standard)->get();
                    // Initialize a response array to accumulate results
                    $responseData = ["H" => [], "S" => []];

                    foreach ($students as $student) {
                        // Initialize temporary data for the current student
                        $tempData = ["H" => [], "S" => []];

                        if ($receiptOrInvoice === "invoice") {
                            // Fetch invoice records for 'other' fees
                            $invoiceRecordsh = GenerateInvoiceView::query()
                                ->where("student_id", $student->id)
                                ->whereBetween("created_at", [
                                    $fromDate,
                                    $toDate,
                                ])
                                ->where("fees_cat", "other")
                                ->distinct()
                                ->get(["*"]); // Use '*' to select all columns or specify the necessary ones

                            // Optionally filter by SponsorID if provided
                            if ($request->filled("SponsorID")) {
                                $sponsorId = $request->input("SponsorID", null);

                                // Optionally filter the distinct invoices based on sponsor in related by_pay_informations table
                                $invoiceRecordsh->load([
                                    "byPayInformations" => function (
                                        $query
                                    ) use ($sponsorId) {
                                        $query->where("sponsor", $sponsorId);
                                    },
                                ]);
                            }
                            foreach ($invoiceRecordsh as $invoice) {
                                $byPayInformations =
                                    $invoice->byPayInformations;

                                // Add the student name to the invoice object
                                $invoice->name = $student->name;

                                // Filter byPayInformations based on SponsorID
                                //   $filteredByPayInformations = $byPayInformations->filter(function ($byPayInformation) use ($sponsorId) {
                                //       return $sponsorId ? $byPayInformation->sponsor !== null : true; // Exclude null sponsors if SponsorID is provided
                                //   });
                                // Filter byPayInformations based on SponsorID and inv_amt

                                if ($request->filled("SponsorID")) {
                                    $filteredByPayInformations = $byPayInformations->filter(
                                        function ($byPayInformation) use (
                                            $sponsorId
                                        ) {
                                            // Check if sponsorId is provided
                                            if ($sponsorId) {
                                                // Exclude null sponsors if SponsorID is provided, but include if inv_amt is not null
                                                return $byPayInformation->sponsor !==
                                                    null ||
                                                    $byPayInformation->inv_amt !==
                                                        null;
                                            }
                                            // If no SponsorID is provided, include all records
                                            return true;
                                        }
                                    );
                                } else {
                                    $filteredByPayInformations = $byPayInformations;
                                }
                                foreach (
                                    $filteredByPayInformations
                                    as $byPayInformation
                                ) {
                                    $sponsorValue = $byPayInformation->sponsor;
                                    $byPayInformation->student = $student;

                                    if ($sponsorValue) {
                                        $sponsorUser = User::find(
                                            $sponsorValue
                                        );
                                        if ($sponsorUser) {
                                            $byPayInformation->sponsor_info = $sponsorUser;
                                        }
                                    }
                                }

                                $tempData["H"][] = [
                                    "invoice" => $invoice,
                                    "receipts" => $filteredByPayInformations
                                        ->values()
                                        ->all(), // Ensure this is an array
                                    "student" => $student,
                                ];
                            }

                            // Fetch invoice records for 'school' fees /////////////////////////////////////////////////////////////////////////////////////
                            $invoiceRecordsS = GenerateInvoiceView::query()
                                ->where(
                                    "generate_invoice_views.student_id",
                                    $student->id
                                )
                                ->whereBetween(
                                    "generate_invoice_views.created_at",
                                    [$fromDate, $toDate]
                                )
                                ->where(
                                    "generate_invoice_views.fees_cat",
                                    "school"
                                );

                            // Apply sponsor filter if provided
                            if ($request->filled("SponsorID")) {
                                $invoiceRecordsS->whereHas(
                                    "byPayInformations",
                                    function ($query) use ($request) {
                                        $query->where(
                                            "sponsor",
                                            $request->input("SponsorID")
                                        );
                                    }
                                );
                            }

                            $invoiceRecordsS = $invoiceRecordsS
                                ->select("generate_invoice_views.*")
                                ->distinct()
                                ->get();

                            foreach ($invoiceRecordsS as $invoice) {
                                $byPayInformations =
                                    $invoice->byPayInformations;
                                $byPayInformations->student = $student;
                                // Filter byPayInformations based on SponsorID
                                //  $filteredPayInformations = $byPayInformations->filter(function ($payInfo) use ($sponsorId) {
                                //      return $sponsorId ? $payInfo->sponsor !== null : true; // Exclude null sponsors if SponsorID is provided
                                // });
                                if ($request->filled("SponsorID")) {
                                    $filteredPayInformations = $byPayInformations->filter(
                                        function ($payInfo) use ($sponsorId) {
                                            // Check if sponsorId is provided
                                            if ($sponsorId) {
                                                // Exclude null sponsors if SponsorID is provided
                                                return $payInfo->sponsor !==
                                                    null ||
                                                    $payInfo->inv_amt !== null;
                                            }
                                            // If no SponsorID is provided, include all records
                                            return true;
                                        }
                                    );
                                } else {
                                    $byPayInformations->student = $student;

                                    $filteredPayInformations = $byPayInformations;
                                }

                                // Only proceed if there are filtered entries
                                if ($filteredPayInformations->isNotEmpty()) {
                                    $sponsorIds = $filteredPayInformations
                                        ->pluck("sponsor")
                                        ->filter()
                                        ->unique();

                                    // Fetch all sponsors at once
                                    $sponsorUsers = User::whereIn(
                                        "id",
                                        $sponsorIds
                                    )
                                        ->get()
                                        ->keyBy("id");

                                    // Add sponsor information to each pay information
                                    foreach (
                                        $filteredPayInformations
                                        as $byPayInformation
                                    ) {
                                        $byPayInformation->student = $student;

                                        if (
                                            $byPayInformation->sponsor &&
                                            isset(
                                                $sponsorUsers[
                                                    $byPayInformation->sponsor
                                                ]
                                            )
                                        ) {
                                            $byPayInformation->sponsor_info =
                                                $sponsorUsers[
                                                    $byPayInformation->sponsor
                                                ];
                                        }
                                    }
                                }

                                // Prepare the response data
                                $tempData["S"][] = [
                                    "invoice" => $invoiceRecordsS,
                                    "receipts" => $filteredPayInformations
                                        ->values()
                                        ->all(), // Ensure this is an array
                                    "student" => $student,
                                ];
                            }
                        }

                        if ($receiptOrInvoice === "receipt") {
                            // Fetch receipt records for 'other' fees
                            $receiptRecordsO = ByPayInformation::query()
                                ->where("student_id", $student->id)
                                ->whereBetween("created_at", [
                                    $fromDate,
                                    $toDate,
                                ])
                                ->where("type", "other");

                            // Check if SponsorID is provided and not empty
                            if ($request->filled("SponsorID")) {
                                $receiptRecordsO->where(
                                    "sponsor",
                                    $request->input("SponsorID")
                                );
                            }

                            // Fetch all 'other' receipts and attach student and sponsor info
                            $receiptRecordsO
                                ->get()
                                ->each(function ($receipt) use (
                                    $student,
                                    &$tempData
                                ) {
                                    $receipt->student = $student; // Attach student info to the receipt
                                    if ($receipt->sponsor) {
                                        $receipt->sponsor_info = User::find(
                                            $receipt->sponsor
                                        ); // Fetch sponsor info
                                    }
                                    $invoice = GenerateInvoiceView::where(
                                        "slno",
                                        $receipt->invoice_id
                                    )->first();

                                    // Ensure receipts are wrapped in an array
                                    $tempData["H"][] = [
                                        "receipts" => [$receipt->toArray()], // Wrap receipt in an array
                                        "student" => $student,
                                        "invoice" => $invoice,
                                    ];
                                });

                            // Fetch receipt records for 'school' fees
                            $receiptRecordsS = ByPayInformation::query()
                                ->where("student_id", $student->id)
                                ->whereBetween("created_at", [
                                    $fromDate,
                                    $toDate,
                                ])
                                ->where("type", "school");

                            // Apply SponsorID filter if provided
                            if ($request->filled("SponsorID")) {
                                $receiptRecordsS->where(
                                    "sponsor",
                                    $request->input("SponsorID")
                                );
                            }

                            // Fetch all 'school' receipts and attach student and sponsor info
                            $receiptRecordsS
                                ->get()
                                ->each(function ($receipt) use (
                                    $student,
                                    &$tempData
                                ) {
                                    $receipt->student = $student; // Attach student info to the receipt
                                    if ($receipt->sponsor) {
                                        $receipt->sponsor_info = User::find(
                                            $receipt->sponsor
                                        ); // Fetch sponsor info
                                    }
                                    $invoice = GenerateInvoiceView::where(
                                        "slno",
                                        $receipt->invoice_id
                                    )->first();

                                    // Ensure receipts are wrapped in an array
                                    $tempData["S"][] = [
                                        "receipts" => [$receipt->toArray()], // Wrap receipt in an array
                                        "student" => $student,
                                        "invoice" => $invoice,
                                    ];
                                });
                        }

                        // Merge current student's data into the main response
                        // Convert to array if not already an array
                        $responseData["H"] = is_array($responseData["H"])
                            ? $responseData["H"]
                            : (array) $responseData["H"];
                        $responseData["S"] = is_array($responseData["S"])
                            ? $responseData["S"]
                            : (array) $responseData["S"];

                        // Merge with $tempData
                        $responseData["H"] = array_merge(
                            $responseData["H"],
                            is_array($tempData["H"])
                                ? $tempData["H"]
                                : (array) $tempData["H"]
                        );
                        $responseData["S"] = array_merge(
                            $responseData["S"],
                            is_array($tempData["S"])
                                ? $tempData["S"]
                                : (array) $tempData["S"]
                        );
                    }

                    return response()->json(
                        ["data" => $responseData, "ddf" => 33],
                        200
                    );
                } else {
                    // If no standard is provided, return invoices and receipts for all students
                    $students = User::where("user_type", "student")->all();

                    foreach ($students as $student) {
                        if (
                            $receiptOrInvoice === "invoice" ||
                            $receiptOrInvoice === "both"
                        ) {
                            // Fetch invoice records for 'other' fees
                            $invoiceRecordsO = GenerateInvoiceView::where(
                                "student_id",
                                $student->id
                            )->where("fees_cat", "other");
                            // Check if SponsorID is provided and not empty
                            if (
                                $request->has("SponsorID") &&
                                !empty($request->input("SponsorID"))
                            ) {
                                // Add sponsor filter to the query
                                $invoiceRecordsO->whereHas(
                                    "byPayInformations",
                                    function ($query) use ($request) {
                                        $query->where(
                                            "sponsor",
                                            $request->input("SponsorID")
                                        );
                                    }
                                );
                            }

                            $invoiceRecordsO = $invoiceRecordsO->get();

                            foreach ($invoiceRecordsO as $invoice) {
                                $byPayInformations =
                                    $invoice->byPayInformations;

                                foreach (
                                    $byPayInformations
                                    as $byPayInformation
                                ) {
                                    $sponsorValue = $byPayInformation->sponsor;

                                    if ($sponsorValue) {
                                        $sponsorUser = User::where(
                                            "id",
                                            $sponsorValue
                                        )->first();
                                        if ($sponsorUser) {
                                            $byPayInformation->sponsor_info = $sponsorUser;
                                        }
                                    }
                                }

                                $responseData["H"][] = [
                                    "invoice" => $invoice,
                                    "receipts" => $byPayInformations,
                                    "student" => $student,
                                ];
                            }

                            // Fetch invoice records for 'school' fees
                            $invoiceRecordsS = GenerateInvoiceView::where(
                                "student_id",
                                $student->id
                            )
                                ->where("fees_cat", "school")
                                ->get();

                            foreach ($invoiceRecordsS as $invoice) {
                                $byPayInformations =
                                    $invoice->byPayInformations;

                                foreach (
                                    $byPayInformations
                                    as $byPayInformation
                                ) {
                                    $sponsorValue = $byPayInformation->sponsor;

                                    if ($sponsorValue) {
                                        $sponsorUser = User::where(
                                            "id",
                                            $sponsorValue
                                        )->first();
                                        if ($sponsorUser) {
                                            $byPayInformation->sponsor_info = $sponsorUser;
                                        }
                                    }
                                }

                                $responseData["S"][] = [
                                    "invoice" => $invoice,
                                    "receipts" => $byPayInformations,
                                    "student" => $student,
                                ];
                            }
                        }

                        if (
                            $receiptOrInvoice === "receipt" ||
                            $receiptOrInvoice === "both"
                        ) {
                            // Fetch receipt records for 'other' fees
                            $receiptRecordsO = ByPayInformation::where(
                                "student_id",
                                $student->id
                            )->where("fees_cat", "other");
                            // Check if SponsorID is provided and not empty
                            if (
                                $request->has("SponsorID") &&
                                !empty($request->input("SponsorID"))
                            ) {
                                // Add sponsor filter to the query
                                $receiptRecordsO->where(
                                    "sponsor",
                                    $request->input("SponsorID")
                                );
                            }
                            $receiptRecordsO = $receiptRecordsO->get();

                            foreach ($receiptRecordsO as $receipt) {
                                $sponsorValue = $receipt->sponsor;

                                if ($sponsorValue) {
                                    $sponsorUser = User::where(
                                        "id",
                                        $sponsorValue
                                    )->first();
                                    if ($sponsorUser) {
                                        $receipt->sponsor_info = $sponsorUser;
                                    }
                                }

                                $responseData["H"][] = [
                                    "receipts" => $receipt,
                                    "student" => $student,
                                ];
                            }

                            // Fetch receipt records for 'school' fees
                            $receiptRecordsS = ByPayInformation::where(
                                "student_id",
                                $student->id
                            )
                                ->where("fees_cat", "school")
                                ->get();

                            foreach ($receiptRecordsS as $receipt) {
                                $sponsorValue = $receipt->sponsor;

                                if ($sponsorValue) {
                                    $sponsorUser = User::where(
                                        "id",
                                        $sponsorValue
                                    )->first();
                                    if ($sponsorUser) {
                                        $receipt->sponsor_info = $sponsorUser;
                                    }
                                }

                                $responseData["S"][] = [
                                    "receipts" => $receipt,
                                    "student" => $student,
                                ];
                            }
                        }
                    }

                    // Return the list of invoices and receipts
                    return response()->json(
                        ["data" => $responseData, "ddf" => 33],
                        200
                    );
                }
            } elseif (
                $request->has("Recieptsid") &&
                !empty($request->input("Recieptsid"))
            ) {
                // Filter ByPayInformation records based on the date range and student_id
                $receipts = ByPayInformation::where(
                    "transactionId",
                    $request->input("Recieptsid")
                )->first();

                // Get unique invoice IDs from the filtered receipts
                $invoiceIds = $receipts->invoice_id;

                // Fetch GenerateInvoiceView records associated with the invoice IDs
                $invoiceRecords = GenerateInvoiceView::where(
                    "slno",
                    $invoiceIds
                )->first();
                // return response()->json(['data' => $invoiceRecords], 200);

                // Initialize an empty array to store the response data
                $responseData = [];

                // Iterate over each invoice record
                // foreach ($invoiceRecords as $invoice) {
                // Filter receipts associated with the current invoice
                // $filteredReceipts = $receipts->where('invoice_id', $invoiceRecords->slno)->value();

                // Retrieve sponsor information for each receipt
                // foreach ($filteredReceipts as $byPayInformation) {
                // Get the sponsor value from the current ByPayInformation record
                $sponsorValue = $receipts->sponsor;

                // If the sponsor value is not null, find the corresponding user
                if ($sponsorValue !== null) {
                    $sponsorUser = User::find($sponsorValue);

                    // If a user with the sponsor value is found, add the sponsor information to the ByPayInformation object
                    if ($sponsorUser) {
                        $receipts->sponsor_info = $sponsorUser;
                    }
                }
                // }
                $studentinfos = User::where(
                    "id",
                    $invoiceRecords->student_id
                )->first();

                // Add the invoice and its associated receipts to the response data
                $responseData[] = [
                    "invoice" => $invoiceRecords,
                    "receipts" => $receipts,
                    "student" => $studentinfos ?? [],
                ];
                // }

                // Return the response
                return response()->json(["data" => $responseData], 200);
            } elseif (
                $request->has("Invoiceid") &&
                !empty($request->input("Invoiceid"))
            ) {
                $invoiceRecords = GenerateInvoiceView::where(
                    "invoice_no",
                    $request->input("Invoiceid")
                )->get();

                foreach ($invoiceRecords as $invoice) {
                    if ($invoice->byPayInformations()->exists()) {
                        // If any associated byPayInformations exist for this invoice
                        $byPayInformations = $invoice->byPayInformations;

                        // Loop through each ByPayInformation object
                        foreach ($byPayInformations as $byPayInformation) {
                            // Get the sponsor value from the current ByPayInformation record
                            $sponsorValue = $byPayInformation->sponsor;

                            // If the sponsor value is not null, find the corresponding user
                            if ($sponsorValue !== null) {
                                $sponsorUser = User::where(
                                    "id",
                                    $sponsorValue
                                )->first();

                                // If a user with the sponsor value is found, add the sponsor information directly to the ByPayInformation object
                                if ($sponsorUser) {
                                    $byPayInformation->sponsor_info = $sponsorUser;
                                }
                            }
                        }
                    }
                    $studentinfos = User::where(
                        "id",
                        $invoice->student_id
                    )->first();

                    // Add each invoice and its associated receipts to the responseData array
                    $responseData[] = [
                        "invoice" => $invoice,
                        "receipts" => $invoice->byPayInformations,
                        "student" => $studentinfos ?? [],
                    ];
                }

                // Return the response after processing all the invoice records
                return response()->json(["data" => $responseData], 200);
            } elseif (
                $request->has("SponsorIDtwo") &&
                !empty($request->input("SponsorIDtwo"))
            ) {
                // Filter ByPayInformation records based on the date range and student_id
                $query = ByPayInformation::where(
                    "sponsor",
                    $request->input("SponsorID")
                );

                if ($request->has("FromDate") && $request->has("ToDate")) {
                    $fromDate = $request->input("FromDate");
                    $toDate = $request->input("ToDate");
                    $query
                        ->where("created_at", ">=", $fromDate)
                        ->where("created_at", "<=", $toDate);
                }

                $receipts = $query->get();

                // Get unique invoice IDs from the filtered receipts
                $invoiceIds = $receipts
                    ->pluck("invoice_id")
                    ->unique()
                    ->toArray();

                // Fetch GenerateInvoiceView records associated with the invoice IDs
                $invoiceRecords = GenerateInvoiceView::whereIn(
                    "slno",
                    $invoiceIds
                )->get();

                // Fetch GenerateInvoiceView records based on sponsor ID and date range
                $invoiceRecordsforspons = GenerateInvoiceView::where(
                    "sponser_id",
                    $request->input("SponsorID")
                )
                    ->where("created_at", ">=", $fromDate) // Include fromDate
                    ->where("created_at", "<=", $toDate)
                    ->get();

                // Merge the invoices from both queries
                $mergedInvoices = $invoiceRecords
                    ->merge($invoiceRecordsforspons)
                    ->unique("slno");

                // Initialize an empty array to store the merged invoice and receipt data
                $responseData = [];

                // Loop through the merged invoices
                foreach ($mergedInvoices as $invoice) {
                    // Filter receipts associated with the current invoice
                    $filteredReceipts = $receipts
                        ->where("invoice_id", $invoice->slno)
                        ->values();

                    // Retrieve sponsor information for each receipt
                    foreach ($filteredReceipts as $byPayInformation) {
                        // Get the sponsor value from the current ByPayInformation record
                        $sponsorValue = $byPayInformation->sponsor;

                        // If the sponsor value is not null, find the corresponding user
                        if ($sponsorValue !== null) {
                            $sponsorUser = User::find($sponsorValue);

                            // If a user with the sponsor value is found, add the sponsor information to the ByPayInformation object
                            if ($sponsorUser) {
                                $byPayInformation->sponsor_info = $sponsorUser;
                            }
                        }
                    }

                    // Add the invoice and its associated receipts into a single array in the response data
                    $responseData[] = [
                        "invoice" => $invoice,
                        "receipts" => $filteredReceipts->toArray(), // Convert to array for the response
                    ];
                }

                // Return the response with merged invoice and receipt data
                return response()->json(["data" => $responseData], 200);
            } elseif (
                $request->has("StudentID") &&
                !empty($request->input("StudentID"))
            ) {
                $studentID = $request->input("StudentID");

                //     FromDate": "2024-01-01",
                // "ToDate": "2024-01-03",
                // "StudentID": "67"
                $totalInvoiceAmounts = GenerateInvoiceView::where(
                    "student_id",
                    $studentID
                )
                    ->whereBetween("created_at", [
                        $request->input("FromDate"),
                        $request->input("ToDate"),
                    ])
                    ->get();
                // dd($totalInvoiceAmounts,$studentID);

                $schoolslnos = GenerateInvoiceView::where(
                    "student_id",
                    $studentID
                )
                    ->where("fees_cat", "school")
                    ->whereBetween("created_at", [
                        $request->input("FromDate"),
                        $request->input("ToDate"),
                    ])
                    ->select("slno")
                    ->get()
                    ->pluck("slno");
                // dd($schoolslnos,$studentID);
                $hostelslnos = GenerateInvoiceView::where(
                    "student_id",
                    $studentID
                )
                    ->where("fees_cat", "other")
                    ->whereBetween("created_at", [
                        $request->input("FromDate"),
                        $request->input("ToDate"),
                    ])
                    ->select("slno")
                    ->get()
                    ->pluck("slno");

                // Filter ByPayInformation based on invoice_id
                $schoolbyPayInformation = ByPayInformation::whereNull("inv_amt")
                    ->whereIn("invoice_id", $schoolslnos)
                    ->get();
                $schooltotalByPayAmount = $schoolbyPayInformation->sum(
                    "amount"
                );
                $hostelbyPayInformation = ByPayInformation::whereNull("inv_amt")
                    ->whereIn("invoice_id", $hostelslnos)
                    ->get();
                $hosteltotalByPayAmount = $hostelbyPayInformation->sum(
                    "amount"
                );
                $schooltotals = [];
                $hosteltotals = [];
                $distotals = [];
                $schooltotalInvoiceAmount = 0;
                $hosteltotalInvoiceAmount = 0;
                $schooltotaldues = 0;
                $schooltotalexcess = 0;
                $hosteltotaldues = 0;
                $hosteltotalexcess = 0;
                $schooltotaDiscountAmount = 0;
                $hosteltotaDiscountAmount = 0;
                foreach ($schoolbyPayInformation as $payment) {
                    $invoiceId = $payment->invoice_id;
                    $amount = $payment->amount;

                    // If the invoice ID doesn't exist in totalPayments array, initialize it
                    if (!isset($schooltotalPayments[$invoiceId])) {
                        $schooltotalPayments[$invoiceId] = 0;
                    }

                    // Add the payment amount to the total payments for this invoice
                    $schooltotalPayments[$invoiceId] += $amount;
                }
                foreach ($hostelbyPayInformation as $payment) {
                    $invoiceId = $payment->invoice_id;
                    $amount = $payment->amount;

                    // If the invoice ID doesn't exist in totalPayments array, initialize it
                    if (!isset($schooltotalPayments[$invoiceId])) {
                        $hosteltotalPayments[$invoiceId] = 0;
                    }

                    // Add the payment amount to the total payments for this invoice
                    $hosteltotalPayments[$invoiceId] += $amount;
                }
                foreach ($totalInvoiceAmounts as $invoice) {
                    $feesItemsDetails = json_decode(
                        $invoice->fees_items_details,
                        true
                    );
                    $discountItemsDetails = json_decode(
                        $invoice->discount_items_details,
                        true
                    );
                    $schooltotalPaymentAmount =
                        $schooltotalPayments[$invoice->slno] ?? 0;
                    $hosteltotalPaymentAmount =
                        $hosteltotalPayments[$invoice->slno] ?? 0;
                    if ($invoice->fees_cat == "school") {
                        foreach ($feesItemsDetails as $item) {
                            $feesSubHeading = $item["fees_sub_heading"];
                            $feesAmount = $item["amount"];
                            $feesHeading = $item["fees_heading"];
                            $schooltotals["invoice"][$feesHeading][
                                $feesSubHeading
                            ] = isset(
                                $schooltotals["invoice"][$feesHeading][
                                    $feesSubHeading
                                ]
                            )
                                ? $schooltotals["invoice"][$feesHeading][
                                        $feesSubHeading
                                    ] + $feesAmount
                                : $feesAmount;

                            // If the total payment amount is greater than or equal to the fees amount,
                            // allocate the fees amount to the corresponding subheading
                            if ($schooltotalPaymentAmount >= $feesAmount) {
                                // If the fees heading doesn't exist in the recpt array, initialize it
                                if (
                                    !isset($schooltotals["recpt"][$feesHeading])
                                ) {
                                    $schooltotals["recpt"][$feesHeading] = [];
                                }

                                // Add the fees amount to the fees subheading under the fees heading
                                $schooltotals["recpt"][$feesHeading][
                                    $feesSubHeading
                                ] =
                                    ($schooltotals["recpt"][$feesHeading][
                                        $feesSubHeading
                                    ] ??
                                        0) +
                                    $feesAmount;

                                // Deduct the allocated fees amount from the total payment amount
                                $schooltotalPaymentAmount -= $feesAmount;
                            } else {
                                // If the total payment amount is less than the fees amount,
                                // allocate the total payment amount to the corresponding subheading
                                // If the fees heading doesn't exist in the recpt array, initialize it
                                if (
                                    !isset($schooltotals["recpt"][$feesHeading])
                                ) {
                                    $schooltotals["recpt"][$feesHeading] = [];
                                }

                                // Add the remaining payment amount to the fees subheading under the fees heading
                                $schooltotals["recpt"][$feesHeading][
                                    $feesSubHeading
                                ] =
                                    ($schooltotals["recpt"][$feesHeading][
                                        $feesSubHeading
                                    ] ??
                                        0) +
                                    $schooltotalPaymentAmount;

                                // Since the total payment amount is fully allocated now, break the loop
                                break;
                            }
                        }
                    } else {
                        foreach ($feesItemsDetails as $item) {
                            $feesSubHeading = $item["fees_sub_heading"];
                            $feesAmount = $item["amount"];
                            $feesHeading = $item["fees_heading"];
                            $hosteltotals["invoice"][$feesHeading][
                                $feesSubHeading
                            ] = isset(
                                $hosteltotals["invoice"][$feesHeading][
                                    $feesSubHeading
                                ]
                            )
                                ? $hosteltotals["invoice"][$feesHeading][
                                        $feesSubHeading
                                    ] + $feesAmount
                                : $feesAmount;

                            // If the total payment amount is greater than or equal to the fees amount,
                            // allocate the fees amount to the corresponding subheading
                            if ($hosteltotalPaymentAmount >= $feesAmount) {
                                // If the fees heading doesn't exist in the recpt array, initialize it
                                if (
                                    !isset($hosteltotals["recpt"][$feesHeading])
                                ) {
                                    $hosteltotals["recpt"][$feesHeading] = [];
                                }

                                // Add the fees amount to the fees subheading under the fees heading
                                $hosteltotals["recpt"][$feesHeading][
                                    $feesSubHeading
                                ] =
                                    ($hosteltotals["recpt"][$feesHeading][
                                        $feesSubHeading
                                    ] ??
                                        0) +
                                    $feesAmount;

                                // Deduct the allocated fees amount from the total payment amount
                                $hosteltotalPaymentAmount -= $feesAmount;
                            } else {
                                // If the total payment amount is less than the fees amount,
                                // allocate the total payment amount to the corresponding subheading
                                // If the fees heading doesn't exist in the recpt array, initialize it
                                if (
                                    !isset($hosteltotals["recpt"][$feesHeading])
                                ) {
                                    $hosteltotals["recpt"][$feesHeading] = [];
                                }

                                // Add the remaining payment amount to the fees subheading under the fees heading
                                $hosteltotals["recpt"][$feesHeading][
                                    $feesSubHeading
                                ] =
                                    ($hosteltotals["recpt"][$feesHeading][
                                        $feesSubHeading
                                    ] ??
                                        0) +
                                    $hosteltotalPaymentAmount;

                                // Since the total payment amount is fully allocated now, break the loop
                                break;
                            }
                        }
                    }
                    // Decode fees items details JSON string into an associative array

                    if ($invoice->fees_cat == "school") {
                        foreach ($discountItemsDetails as $disitem) {
                            $discountCat = $disitem["discount_cat"];
                            $discountAmount = $disitem["dis_amount"];
                            //  $distotals['disrecpt'][$discountCat] = [];
                            //  $distotals['disrecpt'][$discountCat] = ($distotals['disrecpt'][$discountCat] ?? 0) + $discountAmount;
                            $distotals["schooldiscount"][$discountCat] =
                                ($distotals["schooldiscount"][$discountCat] ??
                                    0) +
                                $discountAmount;
                            $schooltotaDiscountAmount += $discountAmount;
                        }
                        $schooltotalInvoiceAmount +=
                            $invoice->total_invoice_amount ?? 0;
                        $schoolpaymentInformation = DB::table(
                            "by_pay_informations"
                        )
                            ->where("invoice_id", $invoice->slno)
                            ->where("type", $invoice->fees_cat)
                            ->latest("id")
                            ->first();
                        $schooltotaldues +=
                            $schoolpaymentInformation->due_amount ?? 0;
                        $schooltotalexcess +=
                            $schoolpaymentInformation->s_excess_amount ?? 0;
                    } else {
                        foreach ($discountItemsDetails as $disitem) {
                            $discountCat = $disitem["discount_cat"];
                            $discountAmount = $disitem["dis_amount"];
                            //  $distotals['disrecpt'][$discountCat] = [];
                            //  $distotals['disrecpt'][$discountCat] = ($distotals['disrecpt'][$discountCat] ?? 0) + $discountAmount;
                            $distotals["hosteldiscount"][$discountCat] =
                                ($distotals["hosteldiscount"][$discountCat] ??
                                    0) +
                                $discountAmount;
                            $hosteltotaDiscountAmount += $discountAmount;
                        }
                        $hosteltotalInvoiceAmount +=
                            $invoice->total_invoice_amount ?? 0;
                        $hostelpaymentInformation = DB::table(
                            "by_pay_informations"
                        )
                            ->where("invoice_id", $invoice->slno)
                            ->where("type", $invoice->fees_cat)
                            ->latest("id")
                            ->first();
                        $hosteltotaldues +=
                            $hostelpaymentInformation->due_amount ?? 0;
                        $hosteltotalexcess +=
                            $hostelpaymentInformation->h_excess_amount ?? 0;
                    }
                }

                // Logic to calculate total invoice amount
                $totalInvoiceAmount = GenerateInvoiceView::where(
                    "student_id",
                    $studentID
                )
                    ->whereBetween("created_at", [
                        $request->input("FromDate"),
                        $request->input("ToDate"),
                    ])
                    ->sum("total_invoice_amount");

                // Logic to calculate total amount fromDate ByPayInformation

                // Get user data
                $userData = User::where("id", $studentID)->first();
                $userDataMaster = Student::where(
                    "admission_no",
                    $userData->admission_no
                )->first();

                return response()->json(
                    [
                        "userData" => $userData,
                        "userDataMaster" => $userDataMaster,
                        "schooltotalInvoiceAmount" => $schooltotalInvoiceAmount,
                        "hosteltotalInvoiceAmount" => $hosteltotalInvoiceAmount,
                        "schooltotalByPayAmount" => $schooltotalByPayAmount,
                        "hosteltotalByPayAmount" => $hosteltotalByPayAmount,
                        "schoolbyPayInformation" => $schoolbyPayInformation,
                        "hostelbyPayInformation" => $hostelbyPayInformation,
                        "distotals" => $distotals,
                        "schooltotals" => $schooltotals,
                        "hosteltotals" => $hosteltotals,
                        "schooltotaldues" => $schooltotaldues,
                        "schooltotalexcess" => $schooltotalexcess,
                        "hosteltotaldues" => $hosteltotaldues,
                        "hosteltotalexcess" => $hosteltotalexcess,
                        "schooltotaDiscountAmount" => $schooltotaDiscountAmount,
                        "hosteltotaDiscountAmount" => $hosteltotaDiscountAmount,
                    ],
                    200
                );
            } else {
                return response()->json(
                    ["error" => "StudentID not provided or empty."],
                    400
                );
            }
        } else {
            // Handle case where either fromDateDate or ToDate is missing
            return response()->json(
                ["error" => "Both FromDate and ToDate are required."],
                400
            );
        }

        // {  https://santhoshavidhyalaya.com/SVSTEST/api/StudentLedger
        //     // "ReceiptorInvoice": "receipt",
        //      "FromDate": "2024-01-01",
        //      "ToDate": "2024-03-01",
        //   //   "AdmissionNo": "181",
        //     // "Invoiceid":"SVS03JAN246932"
        //    "Recieptsid":"20240112141728365"
        //  }
    }

    public function LedgerSummary(Request $request)
    {
        if (!$request->has("FromDate") || !$request->has("ToDate")) {
            return response()->json(
                ["error" => "FromDate and ToDate are required."],
                400
            );
        }

        $requestData = $request->all();

        if ($request->has("FromDate") && $request->has("ToDate")) {
            $fromDate = Carbon::parse(
                $request->input("FromDate")
            )->startOfDay(); // '00:00:00' of the start day
            $toDate = Carbon::parse($request->input("ToDate"))->endOfDay(); // '23:59:59' of the end day

            /////////////////////////////////////////////////////////////////////////////////////////////////////
            //   dd($request->Std);

            // if ($request->has('Std') && !empty($request->input('Std'))) {
            $Std = $request->input("Std");
            // Fetch total invoice amounts
            if (
                $request->Std != "All" &&
                $request->has("Std") &&
                !empty($request->input("Std"))
            ) {
                //   if ($request->has('Std') && !empty($request->input('Std'))) {
                $totalInvoiceAmounts = GenerateInvoiceView::where(
                    "standard",
                    $Std
                )
                    ->where("created_at", ">=", $fromDate)
                    ->where("created_at", "<=", $toDate)
                    ->get();
            } else {
                $totalInvoiceAmounts = GenerateInvoiceView::where(
                    "created_at",
                    ">=",
                    $fromDate
                )
                    ->where("created_at", "<=", $toDate)
                    ->get();
            }
            // Initialize the array to store total invoice amounts
            $totals = ["invoice" => [], "recpt" => []];

            // Calculate total invoice amounts
            foreach ($totalInvoiceAmounts as $invoiceAmounts) {
                $feesItemsDetails = json_decode(
                    $invoiceAmounts->fees_items_details,
                    true
                );

                // Sort fees items details based on priority
                usort($feesItemsDetails, function ($a, $b) {
                    return $a["Priority"] - $b["Priority"];
                });

                foreach ($feesItemsDetails as $item) {
                    $feesHeading = $item["fees_heading"];
                    $feesSubHeading = $item["fees_sub_heading"];
                    $amount = $item["amount"];

                    // Update the total invoice amount
                    $totals["invoice"][$feesHeading][$feesSubHeading] = isset(
                        $totals["invoice"][$feesHeading][$feesSubHeading]
                    )
                        ? $totals["invoice"][$feesHeading][$feesSubHeading] +
                            $amount
                        : $amount;
                }
            }

            // Fetch payments from ByPayInformation
            if (
                $request->has("SponsorID") &&
                !empty($request->input("SponsorID"))
            ) {
                $payments = ByPayInformation::whereIn(
                    "invoice_id",
                    $totalInvoiceAmounts->pluck("slno")
                )
                    ->where("sponsor", $request->input("SponsorID"))
                    ->get();
            } else {
                $payments = ByPayInformation::whereIn(
                    "invoice_id",
                    $totalInvoiceAmounts->pluck("slno")
                )->get();
            }
            $totalPayments = [];
            $latestPayments = ByPayInformation::whereIn(
                "invoice_id",
                $totalInvoiceAmounts->pluck("slno")
            )
                ->select("invoice_id", DB::raw("MAX(id) as latest_id"))
                ->groupBy("invoice_id");

            // Now fetch the full records for the latest IDs
            $latestpayments = ByPayInformation::whereIn(
                "id",
                $latestPayments->pluck("latest_id")
            )
                ->orderBy("id", "desc")
                ->get();

            // return response()->json([
            //         'payments' => $latestpayments
            //     ], 200);
            $schoolTotals = [
                "school_due_amount" => 0,
                "school_excess_amount" => 0,
            ];

            $hostelTotals = [
                "hostel_due_amount" => 0,
                "hostel_excess_amount" => 0,
            ];

            foreach ($latestpayments as $payment) {
                // Convert values to float (in case they are stored as strings)
                $dueAmount = (float) $payment["due_amount"];
                $sExcessAmount = (float) $payment["s_excess_amount"];
                $hExcessAmount = (float) $payment["h_excess_amount"];

                // Add to the respective totals based on the type
                if ($payment["type"] === "school") {
                    $schoolTotals["school_due_amount"] += $dueAmount;
                    $schoolTotals["school_excess_amount"] += $sExcessAmount;
                } elseif ($payment["type"] === "other") {
                    $hostelTotals["hostel_due_amount"] += $dueAmount;
                    $hostelTotals["hostel_excess_amount"] += $sExcessAmount;
                }
            }
            // return response()->json([
            //         'schoolTotals' => $schoolTotals,
            //         'hostelTotals' => $hostelTotals
            //     ], 200);
            // Loop through payments to calculate total payments for each invoice
            foreach ($payments as $payment) {
                $invoiceId = $payment->invoice_id;
                $amount = $payment->amount;

                // If the invoice ID doesn't exist in totalPayments array, initialize it
                if (!isset($totalPayments[$invoiceId])) {
                    $totalPayments[$invoiceId] = 0;
                }

                // Add the payment amount to the total payments for this invoice
                $totalPayments[$invoiceId] += $amount;
            }

            // Initialize an empty array to store the totals
            $discountTotal = 0;
            $InvoiceAmountTotal = 0;
            $PaidAmountTotal = 0;
            $InvoicePendingAmountTotal = 0;
            $totals = [];
            $distotals = [];
            // Now loop through each invoice and compare total payments to fees amounts
            foreach ($totalInvoiceAmounts as $invoice) {
                // Decode fees items details JSON string into an associative array
                $feesItemsDetails = json_decode(
                    $invoice->fees_items_details,
                    true
                );
                $discountItemsDetails = json_decode(
                    $invoice->discount_items_details,
                    true
                );
                // Get discount amount for this invoice
                $discountAmt = $invoice->discount_percent ?? 0;
                $InvoiceAmt = $invoice->total_invoice_amount ?? 0;
                $InvoicependingAmt = $invoice->invoice_pending_amount ?? 0;
                $paidAmt = $invoice->paid_amount ?? 0;
                $Excesscalc = $InvoiceAmt - $paidAmt;

                // Add discount amount to the discount total
                $discountTotal += $discountAmt;
                $InvoiceAmountTotal += $InvoiceAmt;
                $InvoicePendingAmountTotal += $InvoicependingAmt;
                $PaidAmountTotal += $paidAmt;
                // Initialize total payment amount for this invoice
                $totalPaymentAmount = $totalPayments[$invoice->slno] ?? 0;
                usort($feesItemsDetails, function ($a, $b) {
                    return $a["Priority"] - $b["Priority"];
                });
                // Now iterate over each fees item for this invoice and allocate the payments accordingly
                foreach ($feesItemsDetails as $item) {
                    $feesSubHeading = $item["fees_sub_heading"];
                    $feesAmount = $item["amount"];
                    $feesHeading = $item["fees_heading"];
                    $totals["invoice"][$feesHeading][$feesSubHeading] = isset(
                        $totals["invoice"][$feesHeading][$feesSubHeading]
                    )
                        ? $totals["invoice"][$feesHeading][$feesSubHeading] +
                            $feesAmount
                        : $feesAmount;

                    // If the total payment amount is greater than or equal to the fees amount,
                    // allocate the fees amount to the corresponding subheading
                    if ($totalPaymentAmount >= $feesAmount) {
                        // If the fees heading doesn't exist in the recpt array, initialize it
                        if (!isset($totals["recpt"][$feesHeading])) {
                            $totals["recpt"][$feesHeading] = [];
                        }

                        // Add the fees amount to the fees subheading under the fees heading
                        $totals["recpt"][$feesHeading][$feesSubHeading] =
                            ($totals["recpt"][$feesHeading][$feesSubHeading] ??
                                0) +
                            $feesAmount;

                        // Deduct the allocated fees amount from the total payment amount
                        $totalPaymentAmount -= $feesAmount;
                    } else {
                        // If the total payment amount is less than the fees amount,
                        // allocate the total payment amount to the corresponding subheading
                        // If the fees heading doesn't exist in the recpt array, initialize it
                        if (!isset($totals["recpt"][$feesHeading])) {
                            $totals["recpt"][$feesHeading] = [];
                        }

                        // Add the remaining payment amount to the fees subheading under the fees heading
                        $totals["recpt"][$feesHeading][$feesSubHeading] =
                            ($totals["recpt"][$feesHeading][$feesSubHeading] ??
                                0) +
                            $totalPaymentAmount;

                        // Since the total payment amount is fully allocated now, break the loop
                        break;
                    }
                }
                //  $distotals = [];
                //  $distotals = ['disrecpt' => []];
                if ($invoice->fees_cat == "school") {
                    foreach ($discountItemsDetails as $disitem) {
                        $discountCat = $disitem["discount_cat"];
                        $discountAmount = $disitem["dis_amount"];
                        //  $distotals['disrecpt'][$discountCat] = [];
                        //  $distotals['disrecpt'][$discountCat] = ($distotals['disrecpt'][$discountCat] ?? 0) + $discountAmount;
                        $distotals["schooldiscount"][$discountCat] =
                            ($distotals["schooldiscount"][$discountCat] ?? 0) +
                            $discountAmount;
                        //   $totaDiscountAmount += $discountAmount;
                    }
                } else {
                    foreach ($discountItemsDetails as $disitem) {
                        $discountCat = $disitem["discount_cat"];
                        $discountAmount = $disitem["dis_amount"];
                        //  $distotals['disrecpt'][$discountCat] = [];
                        //  $distotals['disrecpt'][$discountCat] = ($distotals['disrecpt'][$discountCat] ?? 0) + $discountAmount;
                        $distotals["hosteldiscount"][$discountCat] =
                            ($distotals["hosteldiscount"][$discountCat] ?? 0) +
                            $discountAmount;
                        //   $totaDiscountAmount += $discountAmount;
                    }
                }
            }
            // }
            // if ($totalInvoiceAmounts->isNotEmpty()) {
            return response()->json(
                [
                    "totals" => $totals,
                    "totalPayments" => $totalPayments,
                    "discountTotal " => $discountTotal,
                    "distotals" => $distotals,
                    "InvoiceAmountTotal " => $InvoiceAmountTotal,
                    "InvoicePendingAmountTotal " => $InvoicePendingAmountTotal,
                    "PaidAmountTotal " => $PaidAmountTotal,
                    "schoolTotals" => $schoolTotals,
                    "hostelTotals" => $hostelTotals,
                ],
                200
            );
            // } else {
            //     return response()->json([
            //             'data' => 'No Data'
            //         ], 200);
            // }
        }
    }
    public function getPaymentReport(Request $request)
    {
        $class = $request->input("class");
        $studentIds = $request->input("students"); // Array of student IDs
        $fromDate = $request->input("fromDate");
        $sponsor = $request->input("sponsor");
        $toDate = $request->input("toDate");
        $page = $request->input("page", 1);
        $limit = $request->input("limit", 10);
        $search = $request->input("search");

        $query = DB::table("by_pay_informations")->select(
            "id",
            "student_id",
            "invoice_id",
            "transactionId",
            "inv_amt",
            "amount",
            "payment_status",
            "additional_details",
            "mode",
            "sponsor",
            "due_amount",
            "s_excess_amount",
            "h_excess_amount",
            "type",
            "created_at",
            "updated_at"
        );
        $query->whereNull("inv_amt");
        // Filter by class using the 'users' table
        if ($class) {
            $query->whereIn("student_id", function ($q) use ($class) {
                $q->select("id")
                    ->from("users")
                    ->where("standard", $class)
                    ->where("user_type", "student"); // Ensure we're only selecting students
            });
        }

        // Filter by student IDs
        if ($studentIds && is_array($studentIds) && count($studentIds) > 0) {
            $query->whereIn("student_id", $studentIds);
        }

        // Filter by date range
        if ($fromDate) {
            $query->where("created_at", ">=", Carbon::parse($fromDate));
        }

        if ($toDate) {
            $query->where(
                "created_at",
                "<=",
                Carbon::parse($toDate)->endOfDay()
            );
        }
        if ($sponsor) {
            $query->where("sponsor", $sponsor);
        }
        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where("transactionId", "like", "%$search%")
                    ->orWhere("mode", "like", "%$search%")
                    ->orWhere("sponsor", "like", "%$search%")
                    ->orWhere("type", "like", "%$search%")
                    ->orWhereIn("student_id", function ($q) use ($search) {
                        $q->select("id")
                            ->from("users")
                            ->where("name", "like", "%$search%");
                    });
            });
        }

        $totalItems = $query->count();

        $results = $query
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        // Calculate excess amounts for each result and add required keys
        $results = $results->map(function ($result) {
            $student = DB::table("users")
                ->where("id", $result->student_id)
                ->first(); // Fetch student data

            $dateOfReceipt = null;
            if ($result->created_at) {
                // Check if created_at is not null
                try {
                    $dateOfReceipt = Carbon::parse($result->created_at)->format(
                        "Y-m-d"
                    );
                } catch (\Exception $e) {
                    // Log the error or handle it as needed
                    \Log::error(
                        "Error parsing date: " .
                            $result->created_at .
                            " - " .
                            $e->getMessage()
                    );
                    $dateOfReceipt = null; // Set to null on error
                }
            }
            return [
                "dateOfReceipt" => $dateOfReceipt, // Assuming created_at is the receipt date
                "receiptNo" => $result->transactionId, // Using transactionId as receiptNo
                "rollNo" => $student->roll_no ?? null, // Get rollNo from users table
                "name" => $student->name ?? null, // Get name from users table
                "standard" => $student->standard ?? null, // Get standard from users table
                "section" => $student->sec ?? null, // Get section from users table
                "receiptAmount" => $result->amount, // Amount from by_pay_informations
                "paymentMode" => $result->mode, // Payment mode from by_pay_informations
                "sponsorName" => optional(
                    User::where("id", $result->sponsor)->first()
                )->name, // Sponsor name from by_pay_informations
                "feesCategory" => $result->type, // Type from by_pay_informations, assuming it's feesCategory
                "academicYear" => optional(
                    GenerateInvoiceView::where(
                        "slno",
                        $result->invoice_id
                    )->first()
                )->acad_year, // Hardcoded academic year, adjust as needed
                "s_excess_amount" => $this->getMostRecentExcess(
                    $result->student_id,
                    "school"
                ),
                "h_excess_amount" => $this->getMostRecentExcess(
                    $result->student_id,
                    "other"
                ),
            ];
        });

        return response()->json([
            "results" => $results,
            "totalItems" => $totalItems,
        ]);
    }
    public function getExcessDuesReport(Request $request)
    {
        $sponsor = $request->input("sponsor", []);

        $class = $request->input("class");
        $studentIds = $request->input("students"); // Array of student IDs
        $fromDate = $request->input("fromDate");
        $toDate = $request->input("toDate");
        $page = $request->input("page", 1);
        $limit = $request->input("limit", 10);
        $search = $request->input("search");

        $userDatas = User::where("status", 1)
            ->where("user_type", "student")

            ->when(!empty($class), function ($query) use ($class) {
                if (is_array($class)) {
                    return $query->whereIn("standard", $class);
                }
                return $query->where("standard", $class);
            })

            ->when(!empty($sponsor), function ($query) use ($sponsor) {
                // Fixed function argument
                if (is_array($sponsor)) {
                    return $query->whereIn("sponser_id", $sponsor);
                }
                return $query->where("sponser_id", $sponsor);
            })

            ->when(!empty($studentIds), function ($query) use ($studentIds) {
                // Fixed `students` condition
                return $query->whereIn("id", (array) $studentIds);
            });

        $totalItems = $userDatas->count(); // Changed $query to $userDatas

        $userDatasResults = $userDatas
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get(); // Removed duplicate .get()

        // return $userDatasResults ;
        $reportData = $userDatasResults->map(function ($student) use (
            $sponsor
        ) {
            $latestSchool = DB::table("by_pay_informations")
                ->where("student_id", $student->id)
                ->where("type", "school")
                ->latest("id");
            $latestSchool = $latestSchool->first();
            // return $latestSchool;
            $latestHostel = DB::table("by_pay_informations")
                ->where("student_id", $student->id)
                ->where("type", "other")
                ->latest("id")
                ->first();

            return [
                "Roll No" => $student->roll_no,
                "Name" => $student->name,
                "Standard" => $student->standard,
                "Section" => $student->sec,
                "School Dues" => $latestSchool ? $latestSchool->due_amount : 0,
                "School Excess" => $latestSchool
                    ? $latestSchool->s_excess_amount
                    : 0,
                "Hostel Dues" => $latestHostel ? $latestHostel->due_amount : 0,
                "Hostel Excess" => $latestHostel
                    ? $latestHostel->h_excess_amount
                    : 0,
                "Sponsor Name" => $sponsor
                    ? optional(User::find($sponsor))->name
                    : null,
                "Fees Category" => $latestSchool ? $latestSchool->type : null,
                "Academic Year" => $latestSchool
                    ? optional(
                        GenerateInvoiceView::where(
                            "slno",
                            $latestSchool->invoice_id
                        )->first()
                    )->acad_year
                    : null,
            ];
        });

        return response()->json([
            "results" => $reportData,
            "totalItems" => $totalItems,
        ]);
    }
    private function getMostRecentExcess($studentId, $feecat)
    {
        $record = DB::table("by_pay_informations")
            ->where("student_id", $studentId)
            ->where("type", $feecat)
            ->orderBy("created_at", "desc")
            ->first();

        if ($record) {
            return $feecat === "school"
                ? $record->s_excess_amount
                : $record->h_excess_amount;
        }

        return 0;
    }
}
