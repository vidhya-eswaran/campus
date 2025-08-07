<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use APP\Models\InvoicePaymentMaps;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invoice_list;
use App\Models\PaymentGatewayAdmin;
use App\Models\PaymentOrdersDetails;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\PaymentOrdersStatuses;

use GrahamCampbell\ResultType\Success;
use App\Models\GenerateInvoiceView;
use App\Models\PaymentNotificationData;
use App\Mail\PaymentNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log; // Add this import statement
use Illuminate\Support\Facades\Http;

use TransactionRequestBean;
use TransactionResponseBean;
use App\Models\PaymentReqData;
use App\Helpers\TwilioHelper;
use App\Mail\PaymentReceiptMail;
use Razorpay\Api\Api;
use App\Helpers\SchoolLogger;
use Illuminate\Support\Str;

date_default_timezone_set('Asia/Kolkata');



class PaymentsController extends Controller
{

    public function intiatePayment(Request $request)
    {
        $key = config('razorpay.key');
        $secret = config('razorpay.secret');

        $schoolNameRaw = config('school.name') ?? 'unknown-school';
        $schoolName = Str::slug($schoolNameRaw);

        SchoolLogger::log("➡️ [START] Initiating payment");

        if (!$key || !$secret) {
            SchoolLogger::log("Razorpay credentials not configured.");
            Log::error('Missing Razorpay credentials.');
            $data['status'] = false;
            $data['msg'] = "Payment gateway not configured.";
            $returnData = json_encode($data);
            $urlString = urlencode($returnData);
            return redirect($data['returnUrl'] . $urlString);
        }

        $appUrl = env('APP_URL');

        $urlEncodedJson = $request->query('data');
        $jsonString = urldecode($urlEncodedJson);
        $data = json_decode($jsonString, true);
        SchoolLogger::log("📥 Request decoded: " . json_encode($data));

        $userAccessToken = $data['reqData']['accessToken'];
        $userId = $data['reqData']['userId'];
        $platform = $data['platform'] ?? 'web';

        $transactionId = randomId();
        SchoolLogger::log("🧾 Transaction ID: $transactionId");

        $invoice_list = $data['invoiceIds'];
        $paidAmount = $data['payAmount'];

        $invoice_records = GenerateInvoiceView::whereIn('slno', $invoice_list)
            ->orderBy('total_invoice_amount', 'desc')
            ->get();

        $payment_order_data = [
            'user_return_Url' => $data['returnUrl'],
            'user_retrun_req_data' => $jsonString,
            'user_access_key' => json_encode($userAccessToken),
            'internal_txn_id' => $transactionId,
            'user_id' => $userId,
            'amount' => $paidAmount,
        ];

        if ($invoice_records->isNotEmpty()) {
            SchoolLogger::log("🧾 Processing " . count($invoice_records) . " invoice(s)");

            $total_invoice_amount = 0;
            $total_fee_amount = 0;

            foreach ($invoice_records as $record) {
                $due = DB::table('by_pay_informations')
                    ->where('student_id', $record->student_id)
                    ->where('type', $record->fees_cat)
                    ->latest('id')->first();

                if ($due) {
                    $total_invoice_amount += $due->due_amount;
                }
            }

            if ($paidAmount < $total_invoice_amount) {
                $splitAmount = $paidAmount / count($invoice_records);
                SchoolLogger::log("💸 Splitting ₹$paidAmount among invoices");

                foreach ($invoice_records as $index => $invoice) {
                    $duequery = DB::table('by_pay_informations')
                        ->where('student_id', $invoice->student_id)
                        ->where('type', $invoice->fees_cat)
                        ->latest('id')->first();

                    $due1 = $duequery->due_amount;
                    $baseTransactionId = (int)$transactionId;
                    $transactionIdWithSuffix = $baseTransactionId . str_pad(($index + 1), 2, '0', STR_PAD_LEFT);

                    $txnAmount = min($splitAmount, $due1);
                    $balance = $due1 - $txnAmount;

                    Invoice_list::create([
                        'user_uuid' => $userId,
                        'invoice_id' => $invoice->slno,
                        'payment_transaction_id' => $transactionIdWithSuffix,
                        'unique_payment_transaction_id' => $baseTransactionId,
                        'status' => 'intiated',
                        'transaction_amount' => $txnAmount,
                        'balance_amount' => $balance
                    ]);

                    SchoolLogger::log("🧾 Invoice {$invoice->slno}: Txn: $transactionIdWithSuffix, Amt: ₹$txnAmount, Balance: ₹$balance");

                    $total_fee_amount += $txnAmount;
                    $paidAmount -= $txnAmount;
                }
            } else {
                foreach ($invoice_records as $index => $invoice) {
                    $due1query = DB::table('by_pay_informations')
                        ->where('student_id', $invoice->student_id)
                        ->where('type', $invoice->fees_cat)
                        ->latest('id')->first();
                    $due2 = $due1query->due_amount;

                    $baseTransactionId = (int)$transactionId;
                    $transactionIdWithSuffix = $baseTransactionId . str_pad(($index + 1), 2, '0', STR_PAD_LEFT);

                    Invoice_list::create([
                        'user_uuid' => $userId,
                        'invoice_id' => $invoice->slno,
                        'payment_transaction_id' => $transactionIdWithSuffix,
                        'unique_payment_transaction_id' => $baseTransactionId,
                        'status' => 'intiated',
                        'transaction_amount' => $due2,
                        'balance_amount' => 0
                    ]);

                    SchoolLogger::log("🧾 Invoice {$invoice->slno}: Txn: $transactionIdWithSuffix, Full paid: ₹$due2");

                    $total_fee_amount += $due2;
                }
            }

            $user_record = User::find($userId);

            if (!$user_record) {
                SchoolLogger::log("User not found: $userId");
                return redirect($data['returnUrl'] . urlencode(json_encode([
                    'status' => false,
                    'msg' => "Getting user details failed"
                ])));
            }

            $payment_order_data += [
                'name' => $user_record->name,
                'custID' => $user_record->uuid,
                'mobNo' => $user_record->mobile_no,
                'paymentMode' => 'Online',
                'currency' => 'INR',
                'scheme' => 'FIRST',
            ];

            $PaymentOrderDetails = PaymentOrdersDetails::create($payment_order_data);

            SchoolLogger::log(" Payment order created. ID: {$PaymentOrderDetails->id}, Txn: $transactionId");

            try {
                $api = new Api(config('razorpay.key'), config('razorpay.secret'));

                $razorpayOrder = $api->order->create([
                    'receipt' => (string)$transactionId,
                    'amount' => $payment_order_data['amount'] * 100,
                    'currency' => 'INR',
                    // 'notes' => [
                    //     'school_fee' => (string)$total_fee_amount,
                    //     'customer_name' => $user_record->name,
                    //     'customer_email' => $user_record->email,
                    //     'customer_phone' => $user_record->mobile_no,
                    //     'customer_id' => (string)$user_record->id
                    // ]

                    'notes' => [
                        'transaction_id'   => $transactionId,
                        'user_id'          => (string) $user_record->id,
                        'user_uuid'        => $user_record->uuid,
                        'user_name'        => $user_record->name,
                        'user_email'       => $user_record->email,
                        'user_phone'       => $user_record->mobile_no,
                        'invoice_ids'      => implode(',', $invoice_list),
                        'pay_amount'       => $payment_order_data['amount'],
                        'school_name'      => $schoolName,
                        'return_url'       => $data['returnUrl'],
                        'access_token'     => substr($userAccessToken, 0, 50), // truncate to fit Razorpay limit
                        'invoice_id' => $invoice->slno,
                        'payment_transaction_id' => $transactionIdWithSuffix,
                        'unique_payment_transaction_id' => $baseTransactionId,
                        'status' => 'intiated',
                    ]
                ]);

                PaymentReqData::create([
                    'payment_req_customerName' => $user_record->name,
                    'payment_req_merchantCode' => config('razorpay.key'),
                    'payment_req_ITC' => $user_record->email,
                    'payment_req_requestType' => 'T',
                    'payment_req_merchantTxnRefNumber' => $transactionId,
                    'payment_req_amount' => $payment_order_data['amount'],
                    'payment_req_currencyCode' => 'INR',
                    'payment_req_returnURL' => $appUrl . '/redirect',
                    'payment_req_shoppingCartDetails' => json_encode(['school_fee' => $total_fee_amount]),
                    'payment_req_TPSLTxnID' => $razorpayOrder->id,
                    'payment_req_mobileNumber' => $user_record->mobile_no,
                    'payment_req_txnDate' => date('Y-m-d'),
                    'payment_req_bankCode' => 'RAZORPAY',
                    'payment_req_custId' => $user_record->id
                ]);

                SchoolLogger::log(" Razorpay order created. ID: {$razorpayOrder->id}, Amount: ₹" . $payment_order_data['amount']);
                if ($platform === 'mobile') {
                    return response()->json([
                        'status' => true,
                        'data' => [
                            'key' => config('razorpay.key'),
                            'amount' => $payment_order_data['amount'] * 100,
                            'currency' => 'INR',
                            'name' => 'EUCTO CAMPUS',
                            'description' => 'Fee Payment',
                            'order_id' => $razorpayOrder->id,
                            'image' => asset('images/CampusLogo.png'),
                            // 'callback_url' => $appUrl . '/redirect',
                            // 'callback_url' => route('razorpay.callback'),
                            'prefill' => [
                                'name' => $user_record->name,
                                'email' => $user_record->email,
                                'contact' => $user_record->mobile_no
                            ],
                            'notes' => [
                                'transaction_id'   => $transactionId,
                                'user_id'          => (string) $user_record->id,
                                'user_uuid'        => $user_record->uuid,
                                'user_name'        => $user_record->name,
                                'user_email'       => $user_record->email,
                                'user_phone'       => $user_record->mobile_no,
                                'invoice_ids'      => implode(',', $invoice_list),
                                'pay_amount'       => $payment_order_data['amount'],
                                'school_name'      => $schoolName,
                                'return_url'       => $data['returnUrl'],
                                'access_token'     => substr($userAccessToken, 0, 50), // truncate to fit Razorpay limit
                                'invoice_id' => $invoice->slno,
                                'payment_transaction_id' => $transactionIdWithSuffix,
                                'unique_payment_transaction_id' => $baseTransactionId,
                                'status' => 'intiated',
                            ]
                        ]
                    ]);
                }
                return view('razorpay-checkout', [
                    'checkoutData' => [
                        'key' => config('razorpay.key'),
                        'amount' => $payment_order_data['amount'] * 100,
                        'currency' => 'INR',
                        'name' => 'EUCTO CAMPUS',
                        'description' =>  'Fee Payment',
                        'order_id' => $razorpayOrder->id,
                        'callback_url' => $appUrl . '/redirect',
                        'prefill' => [
                            'name' => $user_record->name,
                            'email' => $user_record->email,
                            'contact' => $user_record->mobile_no
                        ],
                        'notes' => [
                            'transaction_id'   => $transactionId,
                            'user_id'          => (string) $user_record->id,
                            'user_uuid'        => $user_record->uuid,
                            'user_name'        => $user_record->name,
                            'user_email'       => $user_record->email,
                            'user_phone'       => $user_record->mobile_no,
                            'invoice_ids'      => implode(',', $invoice_list),
                            'pay_amount'       => $payment_order_data['amount'],
                            'school_name'      => $schoolName,
                            'return_url'       => $data['returnUrl'],
                            'access_token'     => substr($userAccessToken, 0, 50), // truncate to fit Razorpay limit
                            'invoice_id' => $invoice->slno,
                            'payment_transaction_id' => $transactionIdWithSuffix,
                            'unique_payment_transaction_id' => $baseTransactionId,
                            'status' => 'intiated',
                        ]
                    ]
                ]);
            } catch (\Exception $e) {
                SchoolLogger::log("Razorpay error: " . $e->getMessage());
                return redirect($data['returnUrl'] . urlencode(json_encode([
                    'status' => false,
                    'msg' => "Payment gateway error: " . $e->getMessage()
                ])));
            }
        } else {
            SchoolLogger::log("No invoice records found.");
            return redirect($data['returnUrl'] . urlencode(json_encode([
                'status' => false,
                'msg' => "Getting invoice details failed"
            ])));
        }
    }



    public function processRetrunResponse(Request $request)
    {
        SchoolLogger::log(' Received payment response', $request->all());

        try {
            $api = new Api(config('razorpay.key'), config('razorpay.secret'));

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            $payment = $api->payment->fetch($request->razorpay_payment_id);
            $internal_trasactionid = $payment->notes['transaction_id'];

            $paymentOrdersDetails_model_data = PaymentOrdersDetails::where('internal_txn_id', $internal_trasactionid)->first();
            $paymentOrdersDetails_id = $paymentOrdersDetails_model_data['id'];
            $return_res_data['return_data'] = $paymentOrdersDetails_model_data['user_return_req_data'];
            $front_end_retrun_url = $paymentOrdersDetails_model_data['user_return_Url'];
            $invoice_lists = Invoice_list::where('unique_payment_transaction_id', $internal_trasactionid)->get();

            // Save payment status
            $statusData = [
                'clnt_txn_ref' => $internal_trasactionid,
                'txn_status' => $payment->status,
                'txn_msg' => 'Payment ' . $payment->status,
                'txn_err_msg' => $payment->error_description ?? '',
                'tpsl_bank_cd' => 'RAZORPAY',
                'tpsl_txn_id' => $payment->id,
                'txn_amt' => $payment->amount / 100,
                'clnt_rqst_meta' => json_encode($payment->notes),
                'tpsl_txn_time' => date('Y-m-d H:i:s', $payment->created_at),
                'bal_amt' => 0,
                'card_id' => $payment->card_id ?? null,
                'alias_name' => null,
                'BankTransactionID' => $payment->acquirer_data['bank_transaction_id'] ?? null,
                'mandate_reg_no' => null,
                'token' => null,
                'hash' => $request->razorpay_signature,
                'payment_gatway_response' => json_encode($payment->toArray()),
                'pay_res_updatedAt' => now()
            ];

            $PaymentOrdersStatus = PaymentOrdersStatuses::create($statusData);

            // Update additional status fields
            $PaymentOrdersStatus->update([
                'merchantTransactionIdentifier' => $payment->id,
                'dual_veri_statusCode' => $payment->status,
                'dual_veri_statusMessage' => 'Payment ' . $payment->status,
                'paymentModeBy' => $payment->method,
                'dual_veri_response' => json_encode($payment->toArray()),
                'dual_veri_updatedAt' => now(),
            ]);

            if ($payment->status === 'captured') {
                PaymentOrdersDetails::find($paymentOrdersDetails_id)->update([
                    'payment_status' => 'success',
                    'payment_code' => 'SUCCESS'
                ]);

                foreach ($invoice_lists as $invoice_list) {
                    $invoice_list->update([
                        'status' => 'success',
                        'transaction_completed_status' => 1
                    ]);

                    $invoiceDetails = GenerateInvoiceView::find($invoice_list->invoice_id);
                    $status = $invoice_list->balance_amount != 0 ? "Partial Paid" : "Paid";

                    $invoiceDetails->update([
                        'payment_status' => $status,
                        'invoice_status' => 4,
                        'paid_amount' => $invoice_list->transaction_amount,
                        'invoice_pending_amount' => $invoice_list->balance_amount,
                    ]);

                    $user = User::find($paymentOrdersDetails_model_data->user_id);
                    $student_id = $invoiceDetails->student_id;

                    $latestDue = DB::table('by_pay_informations')
                        ->where('student_id', $student_id)
                        ->where('type', $invoiceDetails->fees_cat)
                        ->latest('id')
                        ->value('due_amount') ?? 0;

                    $new_due = $latestDue > $invoice_list->transaction_amount
                        ? $latestDue - $invoice_list->transaction_amount
                        : 0;

                    DB::table('by_pay_informations')->insert([
                        'transactionId' => $invoice_list->payment_transaction_id,
                        'sponsor' => $user->user_type === 'sponser' ? $user->id : '',
                        'student_id' => $student_id,
                        'invoice_id' => $invoice_list->invoice_id,
                        'amount' => $invoice_list->transaction_amount,
                        'payment_status' => $status,
                        'mode' => 'online',
                        'type' => $invoiceDetails->fees_cat,
                        's_excess_amount'=>0,
                        'h_excess_amount'=>0,
                        'due_amount' => $new_due,
                        'created_at' => now(),
                    ]);

                    // Receipt email
                    $transactionId = $invoice_list->payment_transaction_id;
                    $downloadLink = "https://anandniketanschool.com/anparenthub/PaymentReceipt12345678912345678/$transactionId";

                    Mail::to('s.harikiran@eucto.com')
                        ->queue(new PaymentReceiptMail($invoiceDetails, $downloadLink, $invoice_list->transaction_amount, 'success', $transactionId));

                    SchoolLogger::log("📧 Receipt email queued", ['txn_id' => $transactionId]);

                    // Handle pending
                    if ($invoice_list->balance_amount !== 0) {
                        DB::table('invoice_pendings')
                            ->where('student_id', $student_id)
                            ->update(['closed_status' => 1, 'updated_at' => now()]);

                        DB::table('invoice_pendings')->insert([
                            'fees_cat' => $invoiceDetails->fees_cat,
                            'student_id' => $student_id,
                            'invoice_no' => $invoiceDetails->invoice_no,
                            'pending_amount' => $invoiceDetails->invoice_pending_amount,
                        ]);
                    }

                    // Notification
                    DB::table('payment_notification_datas')->insert([
                        'student_id' => $student_id,
                        'email' => $user->email,
                        'status' => 'success',
                        'txnId' => $transactionId,
                        'paidAmount' => $invoice_list->transaction_amount,
                        'invoice_nos' => $invoiceDetails->invoice_no,
                    ]);

                    SchoolLogger::log(" Invoice processed successfully", [
                        'invoice_id' => $invoice_list->invoice_id,
                        'student_id' => $student_id,
                        'txn_id' => $transactionId
                    ]);
                }

                $return_res_data['status'] = 200;
                $return_res_data['msg'] = 'Payment successful';
            } else {
                PaymentOrdersDetails::find($paymentOrdersDetails_id)->update([
                    'payment_status' => 'failed',
                    'payment_code' => $payment->status
                ]);

                foreach ($invoice_lists as $invoice) {
                    $invoice->update(['status' => 'failed']);
                }

                SchoolLogger::error("Payment failed", [
                    'txn_id' => $payment->id,
                    'reason' => $payment->error_description ?? 'Unknown'
                ]);

                $return_res_data['status'] = 401;
                $return_res_data['msg'] = 'Payment failed';
            }
        } catch (\Exception $e) {
            SchoolLogger::error('Payment processing exception', ['error' => $e->getMessage()]);
            $return_res_data['status'] = 401;
            $return_res_data['msg'] = 'Payment verification failed';
        }
        return response()->json($return_res_data);
        // $urlString = urlencode(json_encode($return_res_data));
        // return redirect($front_end_retrun_url . $urlString);
    }
    public function getTransactionLogs($studentId)
    {
        // Step 1: Get invoice IDs from generate_invoice_view
        $invoiceIds = DB::table('generate_invoice_views')
            ->where('student_id', $studentId)
            ->pluck('slno')
            ->toArray();

        if (empty($invoiceIds)) {
            return response()->json(['data' => [], 'message' => 'No transactions found'], 200);
        }

        // Step 2: Get invoice_lists related to those invoice IDs
        $invoiceLists = DB::table('invoice_lists')
            ->whereIn('invoice_id', $invoiceIds)
            ->get();

        // Step 3: Attach payment status to each invoice_list row
        $transactionLogs = $invoiceLists->map(function ($item) {
            $paymentStatus = DB::table('payment_orders_statuses')
                ->where('clnt_txn_ref', $item->payment_transaction_id)
                ->select('txn_status') // Only select what is needed
                ->first();

            return [
                'id' => $item->id,
                'invoice_id' => $item->invoice_id,
                'payment_transaction_id' => $item->payment_transaction_id,
                'payment_unique_id' => $item->unique_payment_transaction_id,
                'amount' => $item->amount,
                'txn_status' => $paymentStatus->txn_status ?? 'N/A',
                'created_at' => $item->created_at,
            ];
        });

        return response()->json(['data' => $transactionLogs], 200);
    }
}
