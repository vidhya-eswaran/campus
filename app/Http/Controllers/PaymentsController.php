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
use App\Helpers\FastInvoiceHelper;

date_default_timezone_set('Asia/Kolkata');


require_once app_path('Helpers/TransactionRequestBean.php');
require_once app_path('Helpers/TransactionResponseBean.php');

class PaymentsController extends Controller
{
   
    protected $logLabel = 'InitedPayment'; // Define a label for your logs

    protected function logInitedPayment($message, array $context = [])
    {
        Log::channel('daily')->info("[{$this->logLabel}] $message", $context);
    }

    protected function logPaymentData($description, array $data)
    {
        $logMessage = "$description at " . now()->format('Y-m-d H:i:s');
        Log::channel('daily')->info("[{$this->logLabel}] $logMessage", is_array($data) ? $data : ['data' => $data]);
    }

    
    
     public function intiatePayment(Request $request)
    {       
        $appUrl = env('APP_URL');
        $urlEncodedJson = $request->query('data');
        $jsonString = urldecode($urlEncodedJson);
        $data = json_decode($jsonString, true);
      $this->logInitedPayment('Initiate Payment Request Received', ['received_data' => $data, 'raw_query' => $request->getQueryString()]);

        
        $userAccessToken =  $data['reqData']['accessToken'];
        $userId = $data['reqData']['userId'];
    
        //$request = json_decode($data);
        
        //Url Routing retrun Data for front end
        $payment_order_data['user_return_Url'] =$data['returnUrl'];
        $payment_order_data['user_retrun_req_data'] = $jsonString;
        $payment_order_data['user_access_key'] =json_encode($userAccessToken);
        
        // Get invoice Records for amount callculation and payment init
        $transactionId =  randomId(); // randomId(1000,10000,'STU',['STU12345'])  use for string randomId
        
        $payment_order_data['internal_txn_id'] = $transactionId ;
        $payment_order_data['user_id'] = $userId ;
      
        $invoice_list = $data['invoiceIds']; // get from front end
        $partPayment = $data['partPayment'];
        $partPayAmount = $data['payAmount'];
        $paidAmount = $data['payAmount'];
    
$this->logPaymentData('Processed Data - Initial', [
            'user_id' => $userId,
            'invoice_list' => $invoice_list,
            'partPayment' => $partPayment,
            'partPayAmount' => $partPayAmount,
            'paidAmount' => $paidAmount,
            'internal_txn_id' => $transactionId,
        ]);
        $invoice_count = count($invoice_list);
        $invoice_records = GenerateInvoiceView::whereIn('slno',$invoice_list)
                                                ->orderBy('total_invoice_amount', 'desc')
                                                ->get();
        
        $total_invoice_amount = 0; // Initialize total invoice amount

        
        //total amount of records
      //  $total_invoice_amount = $invoice_records->sum('total_invoice_amount');
        
        // total amount for seprate account
        $account1_amount = 0;
        $account2_amount = 0;

        $payment_order_data['amount'] = $data['payAmount'];
        if($invoice_records->isNotEmpty())
        {
          foreach ($invoice_records as $record) {
        // Get student ID and fee category from the current record
        $student_id = $record->student_id;
        $fees_cat = $record->fees_cat;
        
        // Get the latest payment information for the student based on the fee category
        $due = DB::table('by_pay_informations')
                    ->where('student_id', $student_id)
                    ->where('type', $fees_cat)
                    ->latest('id')
                    ->first();

        if ($due) {
            // Add the due amount for this record to the total
            $total_invoice_amount += $due->due_amount;
        }
    }    
     $this->logPaymentData('Processed Data - Invoice Details', [
            'total_invoice_amount' => $total_invoice_amount,
            'invoice_records_count' => $invoice_records->count(),
            'invoice_records' => $invoice_records->toArray(),
        ]);
            
        if ($paidAmount < $total_invoice_amount) {

            // Calculate the split amount for each invoice
            $splitAmount = $paidAmount / count($invoice_records);

            foreach ($invoice_records as  $index => $invoice) {
                    $duequery = DB::table('by_pay_informations')
                    ->where('student_id', $invoice->student_id)
                    ->where('type', $invoice->fees_cat)
                    ->latest('id')
                    ->first();

                   $due1= $duequery->due_amount;
                  // Generate the base transaction ID (root ID)
                $baseTransactionId =  $transactionId;

                // For each invoice, append a unique suffix to the transaction ID
                //  $transactionIdWithSuffix =(string)$baseTransactionId . str_pad(($index + 1), 2, '0', STR_PAD_LEFT);

               $transactionIdWithSuffix=  FastInvoiceHelper::generateReceiptWithPrefix($invoice->fees_cat);
                // Check if the split amount is greater than the invoice amount
                if ($splitAmount > $due1) {
                    // Deduct the full invoice amount from the paid amount
                    $paidAmount -= $due1;
                    // Set the credited amount for the invoice to its total amount
                    // Set the balance for the invoice to zero
                    $dataInvoicePaymentMaps = [
                        'user_uuid' => $userId,
                        'invoice_id' => $invoice->slno,
                        // 'payment_transaction_id' => $transactionId,
                        'payment_transaction_id' => $transactionIdWithSuffix, // Unique transaction ID for each invoice
                        'unique_payment_transaction_id' => $baseTransactionId, // Root transaction ID for all invoices
                        'status' => 'intiated',
                        'transaction_amount'=>$due1,
                        'balance_amount'=> 0];

                        $GenerateInvoiceView = Invoice_list::create($dataInvoicePaymentMaps);

                        if($invoice->fees_cat=="other")
                        {
                            $account2_amount = $account2_amount+$due1;
                        }
                        else
                        {
                            $account1_amount = $account1_amount+$due1;
                        }

                } else {
                    // Set the credited amount for the invoice to the split amount
                    // Calculate the balance for the invoice
                    // Deduct the split amount from the paid amount
                    $dataInvoicePaymentMaps = [
                        'user_uuid' => $userId,
                        'invoice_id' => $invoice->slno,
                        'payment_transaction_id' => $transactionIdWithSuffix, // Unique transaction ID for each invoice
                        'unique_payment_transaction_id' => $baseTransactionId, // Root transaction ID for all invoices
                        'status' => 'intiated',
                        'transaction_amount'=> $splitAmount,
                        'balance_amount'=> $due1 - $splitAmount];
                        
                        $GenerateInvoiceView = Invoice_list::create($dataInvoicePaymentMaps);
                        
                        if($invoice->fees_cat=="other")
                        {
                            $account2_amount = $account2_amount+$splitAmount;
                        }
                        else
                        {
                            $account1_amount = $account1_amount+$splitAmount;
                        }

                    $paidAmount -= $splitAmount;
                }
            }
        } else {
            // If the paid amount is greater than or equal to the total invoice amount,
          
            
            foreach ($invoice_records as  $index => $invoice) {
                   // set the credited amount to the total amount for each invoice
                $baseTransactionId = (int)($transactionId);
            
                // For each invoice, append a unique suffix to the transaction ID 
                // $transactionIdWithSuffix =  (string)$baseTransactionId . str_pad(($index + 1), 2, '0', STR_PAD_LEFT);
                              $transactionIdWithSuffix=  FastInvoiceHelper::generateReceiptWithPrefix($invoice->fees_cat);

                    $due1query = DB::table('by_pay_informations')
                    ->where('student_id', $invoice->student_id)
                    ->where('type', $invoice->fees_cat)
                    ->latest('id')
                    ->first();
                $due2=$due1query->due_amount;
                $dataInvoicePaymentMaps = [
                    'user_uuid' => $userId,
                    'invoice_id' => $invoice->slno,
                    'payment_transaction_id' => $transactionIdWithSuffix, // Unique transaction ID for each invoice
                    'unique_payment_transaction_id' => $baseTransactionId, // Root transaction ID for all invoices
                    'status' => 'intiated',
                    'transaction_amount'=> $due2,
                    'balance_amount'=> 0];
                    
                    $GenerateInvoiceView = Invoice_list::create($dataInvoicePaymentMaps);
                 $this->logPaymentData('Inserted into Invoice_list (Partial)', $GenerateInvoiceView->toArray());

                    if($invoice->fees_cat=="other")
                    {
                        $account2_amount = $account2_amount+$due2;
                    }
                    else
                    {
                        $account1_amount = $account1_amount+$due2;
                    }
            }
        }

        
            $payment_order_data['maxAmount'] = null;

            $user_record = User::where('id',$userId)->first();
            if(empty($user_record))
                {
                                $this->logInitedPayment('User details not found.', ['user_id' => $userId]);
                    $data['status'] = false;
                    $data['msg'] = "Geting user details faild";
                    $returnData = json_encode($data);
                    $urlString = urlencode($returnData);
                    return redirect($data['returnUrl'].$urlString);
                }
                
            if($user_record)
            {
                //Payment user details
                $payment_order_data['name'] = $user_record['name'];
                $payment_order_data['custID']  = $user_record['uuid'];
                $payment_order_data['mobNo'] =$user_record['mobile_no'];
                
                //Payment 
                $payment_order_data['paymentMode'] = 'Online';
                $payment_order_data['accNo'] = null;
                $payment_order_data['debitStartDate']=null;
                $payment_order_data['debitEndDate'] = null;
                $payment_order_data['amountType'] =null;
                $payment_order_data['currency'] = 'INR';
                $payment_order_data['frequency'] =null;
                $payment_order_data['cardNumber'] =null;
                $payment_order_data['expMonth'] =null;
                $payment_order_data['expYear'] =null;
                $payment_order_data['cvvCode'] =null;
                $payment_order_data['scheme'] ='FIRST';
                $payment_order_data['accountName'] =null;
                $payment_order_data['ifscCode'] =null;
                $payment_order_data['accountType'] =null;
                $payment_order_data['payment_status'] = null;
                $payment_order_data['order_hash_value'] =null;

                $PaymentOrderDetails = PaymentOrdersDetails::create($payment_order_data);
            $this->logPaymentData('Inserted into PaymentOrdersDetails', $PaymentOrderDetails->toArray());
                $admin_Model_data = PaymentGatewayAdmin::where('id', 1)->first();
                if($admin_Model_data){
                    $mer_array = $admin_Model_data->toArray();
                }
                // https://www.santhoshavidhyalaya.com/SVS:8000/redirect
                $returnUrl = $appUrl.'/redirect';
                
                //New 
                if($PaymentOrderDetails->id)
                { 
                    ob_start();
                    error_reporting(E_ALL);
                    $strNo = rand(1, 1000000);
                    date_default_timezone_set('Asia/Calcutta');
                    $strCurDate = date('Y-m-d');
                    $transactionRequestBean = new TransactionRequestBean();
                    $transactionRequestBean->merchantCode = env('MERCHANT_CODE');
                    $transactionRequestBean->ITC = $user_record['email'];
                    $transactionRequestBean->customerName = $user_record['name'];
                    $transactionRequestBean->requestType = "T";
                    $transactionRequestBean->merchantTxnRefNumber =  $transactionId;
                    $transactionRequestBean->amount = $payment_order_data['amount'];
                    $transactionRequestBean->currencyCode = 'INR';
                    $transactionRequestBean->returnURL = $returnUrl;
                  $school_fee = env('SCHOOL_FEE_SCHEME_CDOE') . "_" . number_format($account1_amount, 2, '.', '') . "_0.0"; 
                  $hostel_fee = env('HOSTEL_FEE_SCHEME_CDOE') . "_" . number_format($account2_amount, 2, '.', '') . "_0.0"; 
                  $payment_splitout = $school_fee . "~" . $hostel_fee;

                    $transactionRequestBean->shoppingCartDetails = $payment_splitout ;
                    
                    // $transactionRequestBean->shoppingCartDetails = env('SCHOOL_FEE_SCHEME_CDOE')."_".number_format($account2_amount,2,'.', '')."_".number_format($account1_amount,2,'.', '')."";
                                                //  “test_10.0_0.0~test1_15.0_0.0”
                    $transactionRequestBean->TPSLTxnID = '';
                    $transactionRequestBean->mobileNumber = $user_record['mobile_no'];
                    $transactionRequestBean->txnDate = date('Y-m-d');
                    $transactionRequestBean->bankCode = 470;
                    $transactionRequestBean->custId = $user_record['id'];
                    $transactionRequestBean->key = env('ENCRYPTION_KEY');
                    $transactionRequestBean->iv = env('ENCRYPTION_IV');
                    $transactionRequestBean->accountNo = '';
                    $transactionRequestBean->webServiceLocator = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';
                    $transactionRequestBean->timeOut = 30;


                    $payment_req_data['payment_req_customerName'] = $transactionRequestBean->customerName;
                    $payment_req_data['payment_req_merchantCode'] = $transactionRequestBean->merchantCode;
                    $payment_req_data['payment_req_ITC'] = $transactionRequestBean->ITC;
                    $payment_req_data['payment_req_requestType']=$transactionRequestBean->requestType;
                    $payment_req_data['payment_req_merchantTxnRefNumber'] = $transactionRequestBean->merchantTxnRefNumber;
                    $payment_req_data['payment_req_amount'] =$transactionRequestBean->amount;
                    $payment_req_data['payment_req_currencyCode'] = $transactionRequestBean->currencyCode;
                    $payment_req_data['payment_req_returnURL'] = $transactionRequestBean->returnURL;
                    $payment_req_data['payment_req_shoppingCartDetails'] = $transactionRequestBean->shoppingCartDetails;
                    $payment_req_data['payment_req_TPSLTxnID'] = $transactionRequestBean->TPSLTxnID;
                    $payment_req_data['payment_req_mobileNumber'] = $transactionRequestBean->mobileNumber;
                    $payment_req_data['payment_req_txnDate'] = $transactionRequestBean->txnDate;
                    $payment_req_data['payment_req_bankCode'] = 'FIRST';
                    $payment_req_data['payment_req_custId'] = $transactionRequestBean->custId;
                    $payment_req_data['payment_req_key'] = $transactionRequestBean->key;
                    $payment_req_data['payment_req_iv'] = $transactionRequestBean->iv;
                    $payment_req_data['payment_req_accountNo'] = $transactionRequestBean->accountNo;
                    $payment_req_data['payment_req_webServiceLocator_PHP_EOL'] = $transactionRequestBean->webServiceLocator.PHP_EOL;
                    
                    $PaymentOrderDetails = PaymentReqData::create($payment_req_data);
                $this->logPaymentData('Inserted into PaymentReqData', $PaymentOrderDetails->toArray());

                    $responseDetails = $transactionRequestBean->getTransactionToken();
                    $responseDetails = (array)$responseDetails;
                    $response = $responseDetails[0];
                    echo "<script>window.location = '" . $response . "'</script>";
                    ob_flush();
                }
                else
                {
                $this->logInitedPayment('Failed to create PaymentOrdersDetails record---->1.');

                    $data['status'] = false;
                    $data['msg'] = "Geting user details faild";
                    $returnData = json_encode($data);
                    $urlString = urlencode($returnData);
                    return redirect($data['returnUrl'].$urlString);

                }
            }
            }
            else
            {   $this->logInitedPayment('Failed to create PaymentOrdersDetails record ->>>2.');

                $data['status'] = false;
                $data['msg'] = "Geting invoice details faild";
                $returnData = json_encode($data);
                $urlString = urlencode($returnData);
                return redirect($data['returnUrl'].$urlString);
            }
            
    }


    public function processRetrunResponse(Request $request)
    {        //    return response()->json(['message' => $request->all()], 200);

        $response = $_POST;
        if (is_array($response)) {
            $str = $response['msg'];
        } else if (is_string($response) && strstr($response, 'msg=')) {
            $outputstr = str_replace('msg=', '', $response);
            $outputArr = explode('&', $outputstr);
            $str = $outputArr[0];
        } else {
            $str = $response;
        }
        $transactionResponseBean = new TransactionResponseBean();
        $transactionResponseBean->setResponsePayload($str);
        $transactionResponseBean->key = env('ENCRYPTION_KEY');
        $transactionResponseBean->iv = env('ENCRYPTION_IV');
        $response = $transactionResponseBean->getResponsePayload();

        // Read Response
        $msg = $response; 
        $res_msg = explode("|",$msg);

        // Internal transaction id
        $save_payment_orders_status_data['clnt_txn_ref'] = explodeArray($res_msg[3])->value;
        $internal_trasactionid = explodeArray($res_msg[3])->value;
        $paymentOrdersDetails_model_data = PaymentOrdersDetails::where('internal_txn_id', $internal_trasactionid)->first();
        $paymentOrdersDetails_id = $paymentOrdersDetails_model_data['id'];
        $return_res_data['return_data'] =  $paymentOrdersDetails_model_data['user_return_req_data'];
        $front_end_retrun_url = $paymentOrdersDetails_model_data['user_return_Url'];


        $invoice_lists =  Invoice_list::where('unique_payment_transaction_id', $internal_trasactionid)->get();

        $save_payment_orders_status_data['txn_status'] = explodeArray($res_msg[0])->value;
        $save_payment_orders_status_data['txn_msg'] =  explodeArray($res_msg[1])->value;
        $save_payment_orders_status_data['txn_err_msg'] = explodeArray($res_msg[2])->value;
        $save_payment_orders_status_data['tpsl_bank_cd'] = explodeArray($res_msg[4])->value;
        $save_payment_orders_status_data['tpsl_txn_id'] = explodeArray($res_msg[5])->value;
        $save_payment_orders_status_data['txn_amt'] = explodeArray($res_msg[6])->value;
        $save_payment_orders_status_data['clnt_rqst_meta'] = explodeArray($res_msg[7])->value;
        $save_payment_orders_status_data['tpsl_txn_time'] = explodeArray($res_msg[8])->value;
        $save_payment_orders_status_data['bal_amt'] = explodeArray($res_msg[10])->value;
        $save_payment_orders_status_data['card_id'] = null;
        $save_payment_orders_status_data['alias_name'] = null;
        $save_payment_orders_status_data['BankTransactionID'] = null;
        $save_payment_orders_status_data['mandate_reg_no'] = null;
        $save_payment_orders_status_data['token'] = explodeArray($res_msg[11])->value;
        $save_payment_orders_status_data['hash'] = explodeArray($res_msg[12])->value;
        $save_payment_orders_status_data['payment_gatway_response'] = $msg;
        $save_payment_orders_status_data['pay_res_updatedAt'] = date('Y-m-d H:i:s');

        $PaymentOrdersStatus = PaymentOrdersStatuses::create($save_payment_orders_status_data);

        // Merchant details / credentials
        $admin_data['merchantCode'] = env('MERCHANT_CODE');
        $admin_data['currency'] = 'INR';
        $strCurDate = date('d-m-Y');

        $arr_req = array(
            "merchant" => [
                "identifier" => $admin_data['merchantCode']
            ],
            "transaction" => ["deviceIdentifier" => "S", "currency" => $admin_data['currency'], "dateTime" => $strCurDate, "token" => explodeArray($res_msg[5])->value, "requestType" => "S"]
        );

        $finalJsonReq = json_encode($arr_req);

        // Function for dual varification response 
        function callAPI($method, $url, $finalJsonReq)
        {
            $curl = curl_init();
            switch ($method) {
                case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                    if ($finalJsonReq)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($finalJsonReq)
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $finalJsonReq);
                    break;
                default:
                    if ($finalJsonReq)
                        $url = sprintf("%s?%s", $url, http_build_query($finalJsonReq));
            }
            // OPTIONS:
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            // EXECUTE:
            $result = curl_exec($curl);
            if (!$result) {
                die("Connection Failure !! Try after some time.");
            }
            curl_close($curl);
            return $result;
        }

        $method = 'POST';
        $url = "https://www.paynimo.com/api/paynimoV2.req";
        $res_result = callAPI($method, $url, $finalJsonReq);
        $dualVerifyData = json_decode($res_result, true);

        $update_payment_orders_status_data = PaymentOrdersStatuses::find($PaymentOrdersStatus->id);
        if ($update_payment_orders_status_data) {
            $update_payment_orders_status_data['merchantTransactionIdentifier'] = $dualVerifyData['merchantTransactionIdentifier'];
            $update_payment_orders_status_data['dual_veri_statusCode'] =  $dualVerifyData['paymentMethod']['paymentTransaction']['statusCode'];
            $update_payment_orders_status_data['dual_veri_statusMessage'] = $dualVerifyData['paymentMethod']['paymentTransaction']['statusMessage'];
            $update_payment_orders_status_data['paymentModeBy'] = $dualVerifyData['paymentMethod']['paymentMode'];
            $update_payment_orders_status_data['dual_veri_response'] =  $res_result;
            $update_payment_orders_status_data['dual_veri_updatedAt'] =  date('Y-m-d H:i:s');
            $update_payment_orders_status_data->save();
        }

        if ($dualVerifyData['paymentMethod']['paymentTransaction']['statusMessage'] == 'SUCCESS') {
            $update_payment_orders_details_data = PaymentOrdersDetails::find($paymentOrdersDetails_id);
            $update_payment_orders_details_data['payment_status'] = 'success';
            $update_payment_orders_details_data['payment_code'] = $dualVerifyData['paymentMethod']['paymentTransaction']['statusCode'];
            $update_payment_orders_details_data->save();

     
            if ($invoice_lists) {
                foreach ($invoice_lists as $invoice_list) {

                    $invoice_list_update_data = Invoice_list::find($invoice_list->id);
                    $invoice_list_update_data['status'] = 'success';
                    $invoice_list_update_data['transaction_completed_status'] = 1;
                    $invoice_list_update_data->save();
                        $uuid=$invoice_list_update_data->user_uuid;
                    GenerateInvoiceView::where('slno', $invoice_list->invoice_id)
                        ->update([
                            'payment_status' => (float)$invoice_list->balance_amount != 0 ? "Partial Paid" : "Paid",
                            'invoice_status' => 4,
                            'paid_amount' => $invoice_list->transaction_amount,
                            'invoice_pending_amount' => $invoice_list->balance_amount
                        ]);

//$sponsor = GenerateInvoiceView::find($invoice_list_update_data->invoice_id)->student_id ==$uuid ? '' : $uuid;
 $sponsor = ''; 

    if ($paymentOrdersDetails_model_data) {
         $user = User::where('id', $paymentOrdersDetails_model_data['user_id'])->first();
        if ($user && $user->user_type == 'sponser') {
            // If user exists and is a sponsor, set $sponsor to user's ID
            $sponsor = $user->id;
        }
    }

    $pay_amount = $invoice_list->transaction_amount;
    $invoiceDetails = DB::table('generate_invoice_views')
                    ->where('slno', $invoice_list->invoice_id)
                    ->first(); 
            $paymentInformation = DB::table('by_pay_informations')->where('student_id', $invoiceDetails->student_id)->where('type', $invoiceDetails->fees_cat)->latest('id')->first();
            $most_recent_dues = $paymentInformation->due_amount;
            $s_excess = $paymentInformation->s_excess_amount;
            $h_excess = $paymentInformation->h_excess_amount;
    if($most_recent_dues > $pay_amount){
        $DueAmnt = $most_recent_dues - $pay_amount;
    } else {
        $DueAmnt = 0;
    }
   DB::table('by_pay_informations')->insert([
                    'transactionId' => $invoice_list_update_data->payment_transaction_id,
                    'sponsor' => $sponsor ??'',
                    'student_id' =>GenerateInvoiceView::find($invoice_list_update_data->invoice_id)->student_id,
                    'invoice_id' => $invoice_list->invoice_id,
                    'amount' =>  $invoice_list->transaction_amount,
                    'payment_status' =>  (float)$DueAmnt != 0 ? "Partial Paid" : "Paid",
                    'mode' =>  'online',
                    'type' =>  $invoiceDetails->fees_cat,
                    'due_amount' =>  $DueAmnt,
                ]);
                
                //Receipt Details Mail Send
                    $amount = $pay_amount;
                    $payment_status = 'success';
                    $transactionId = $invoice_list_update_data->payment_transaction_id;
                    $invoiceDetails = GenerateInvoiceView::select('*')
                        ->where('slno', $invoice_list->invoice_id)
                        ->first();
                    $downloadLink = "http://santhoshavidhyalaya.com/svsportaladmintest/PaymentReceipt12345678912345678" . "/$transactionId";
                    Mail::to('s.harikiran@eucto.com')->queue(new PaymentReceiptMail($invoiceDetails, $downloadLink,$amount,$payment_status,$transactionId));  
                    //End
                    
                    if ($invoiceDetails) {
                        $student_id = $invoiceDetails->student_id;

                        if ($invoice_list->balance_amount !== 0) {
                            DB::table('invoice_pendings')
                                ->where('student_id', $invoiceDetails->student_id)
                                ->update([
                                    'closed_status' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ]);

                            DB::table('invoice_pendings')->insert([
                                'fees_cat' => $invoiceDetails->fees_cat,
                                'student_id' => $invoiceDetails->student_id,
                                'invoice_no' => $invoiceDetails->invoice_no,
                                'pending_amount' => $invoiceDetails->invoice_pending_amount,
                            ]);
                        }
                    }

// for notification and mail
                    $paymentNotificationData = (object)[];
                    $paymentNotificationData->invoice_nos = '';
                    // $email_datas  = DB::table('payment_orders_details')
                    //     ->select("payment_orders_details.amount", "payment_orders_details.internal_txn_id", "generate_invoice_views.invoice_no", "generate_invoice_views.student_id")
                    //     ->leftJoin('invoice_lists', 'invoice_lists.unique_payment_transaction_id', '=', 'payment_orders_details.internal_txn_id')
                    //     ->leftJoin('generate_invoice_views', 'generate_invoice_views.slno', '=', 'invoice_lists.invoice_id')
                    //     ->where('payment_orders_details.internal_txn_id', $internal_trasactionid)->get();
    //                     $invoiceListsA = DB::table('invoice_lists')
    // ->select('user_uuid', 'invoice_id', 'payment_transaction_id', 'unique_payment_transaction_id')
    // ->where('invoice_id', $invoice_list->invoice_id)
    // ->get();
    // return $invoice_list;

$email_datas = [];

// foreach ($invoiceListsA as $invoice) {
    // Get the invoice number
    $invoiceNo = DB::table('generate_invoice_views')
        ->where('slno', $invoice_list->invoice_id)
        ->value('invoice_no');

    // // Get the amount
    // $amount = DB::table('by_pay_informations')
    //     ->where('transactionId', $invoice->payment_transaction_id)
    //     ->value('amount');

    $email_datas[] = [
        'student_id' => $invoice_list->user_uuid,
        'invoice_no' => $invoiceNo,
        'internal_txn_id' => $invoice_list->payment_transaction_id,
        'unique_payment_transaction_id' => $invoice_list->unique_payment_transaction_id,
        'invoice_no' => $invoiceNo,
        'amount' => $invoice_list->transaction_amount,
    ];
// }

                        // return $email_datas;
                    // if ($email_datas->count() > 0) {
                        if (count($email_datas) > 0) {

                        $increement = 1;
                        foreach ($email_datas as $email_data) {
                            $paymentNotificationData->txnId =  $email_data['internal_txn_id']; 
                            $paymentNotificationData->paidAmount = $email_data['amount']; 
                            $paymentNotificationData->student_id = $email_data['student_id'];   

                            if ($increement == 1) {
                                $paymentNotificationData->invoice_nos = $paymentNotificationData->invoice_nos . $email_data['invoice_no']  ;
                            } else {
                                $paymentNotificationData->invoice_nos = $paymentNotificationData->invoice_nos . ' , ' .  $email_data['invoice_no']   ;
                            }

                            $increement++;
                        }
                        // Build SQL query
                        $user = User::find($paymentNotificationData->student_id);

                        // $sql = DB::table('payment_notification_datas')->insert([
                        //     'student_id' =>  $paymentNotificationData->student_id,
                        //     'email' =>  $user->email,
                        //     'status' =>  'status',
                        //     'txnId' => $paymentNotificationData->txnId,
                        //     'paidAmount' => $paymentNotificationData->paidAmount,
                        //     'invoice_nos' => $paymentNotificationData->invoice_nos
                        // ]);
                        $sql = [
                            'student_id' => $paymentNotificationData->student_id,
                            'email' => $user->email,
                            'status' => 'success',
                            'txnId' => $paymentNotificationData->txnId,
                            'paidAmount' => $paymentNotificationData->paidAmount,
                            'invoice_nos' => $paymentNotificationData->invoice_nos
                        ];
                        // Execute the SQL query
                    //    DB::statement($sql);
DB::table('payment_notification_datas')->insert($sql);
 $mailData = [
    'user' => $user,
    'paymentNotificationData' => $paymentNotificationData,
];
//Mail::queue(new PaymentNotificationMail($user, $paymentNotificationData))->to($user->email);
// Mail::to($user->email)->queue(new PaymentNotificationMail($user, $paymentNotificationData));
try {
    $phone_no = Student::where('admission_no', $user->admission_no)->value('MOBILE_NUMBER');

    // Add Indian country code if the phone number is not empty
    if (!empty($phone_no)) {
        $phone_no = $phone_no;
    
        $message = "Dear  " . $user->name . ", Payment of against invoice number" .  $paymentNotificationData->invoice_nos . " is successful. If you have any questions or require support, please feel free to contact the Santhosha Vidhyalaya administrator. Thank you for your prompt attention to this matter. Please download the receipt and invoice from the portal. https://santhoshavidhyalaya.com/Payfeeportal/ - Santhosha Vidhyalaya";
            
        // Send SMS using Laravel HTTP client
        $smsResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==', // Replace with your BASIC AUTH string
        ])->post('https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/', [
            "Text" => $message,
            "Number" => $phone_no,
            "SenderId" => "SVHSTL",
            "DRNotifyUrl" => "https://www.domainname.com/notifyurl",
            "DRNotifyHttpMethod" => "POST",
            "Tool" => "API",
        ]);

        // Handle the SMS response (you can customize this as needed)
        $smsStatusCode = $smsResponse->status();
        $smsResponseBody = $smsResponse->body();

        if ($smsStatusCode == 200) {
            // SMS sent successfully
            Log::info('SMS sent successfully. Response: ' . $smsResponseBody);
        } else {
            // SMS sending failed
            Log::error('SMS sending failed. Response: ' . $smsResponseBody);
        }
    }
} catch (\Exception $e) {
    Log::error('Exception: ' . $e->getMessage());
}


                    }
                }
            }

            //Mail::to($email)->send(new SendMail($email));



            $return_res_data['status'] = 200;
            $return_res_data['msg'] = $dualVerifyData['paymentMethod']['paymentTransaction']['statusMessage'];
        } else {

            $update_payment_orders_details_data = PaymentOrdersDetails::find($paymentOrdersDetails_id);
            $update_payment_orders_details_data['payment_status'] = $res_msg[2];
            $update_payment_orders_details_data['payment_code'] = $dualVerifyData['paymentMethod']['paymentTransaction']['statusCode'];
            $update_payment_orders_details_data->save();

            if ($invoice_lists) {
                foreach ($invoice_lists as $invoice_list) {
                    $invoice_list_update_data = Invoice_list::find($invoice_list->id);
                    $invoice_list_update_data['status'] = $res_msg[2];
                    $invoice_list_update_data->save();
                }
            }

            $return_res_data['status'] = 401;
            $return_res_data['msg'] = $res_msg[2];
        }

        $json_endcode_data = json_encode($return_res_data);
        $urlString = urlencode($json_endcode_data);

        return redirect($front_end_retrun_url . $urlString);
    }
}
