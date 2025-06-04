<?php


namespace App\Http\Controllers;

use PDF;
use App\Models\Admission;
use App\Models\AdmissionForm;
use App\Mail\AdmissionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\PaymentGatewayAdmin;
use App\Models\PaymentOrdersDetails;
use App\Models\PaymentOrdersStatuses;
use TransactionRequestBean;
use TransactionResponseBean;
use App\Models\PaymentReqData;
use App\Helpers\TwilioHelper;
use App\Models\DonationList;
use App\Models\DonarList;
use App\Models\DonationStatementTrans;
use Illuminate\Support\Str;
use DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use Carbon\Carbon;

date_default_timezone_set('Asia/Kolkata');


require_once app_path('Helpers/TransactionRequestBean.php');
require_once app_path('Helpers/TransactionResponseBean.php');

class DonationController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donations = DonationList::all(); // Fetch all donation cards
        return view('donations.index', compact('donations'));
    }
     public function indexdemo()
    {
        // dd("hi");
        return view('admission.demo_test');
    }
    public function Data(){
        // dd("a");
   $result = AdmissionForm::get();
    $data = $result->toArray();
   // dd($data);
   // dd($data);
    
        
// $result = Admission::get();
// $names = $result->pluck('name')->all();
// $data = $names;

        return view('admission.data',['data'=>$data]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function offline(Request $request){
         
                 return view('admission.offline');

        //  dd("hello");
         
     }
      
  
     public function processPayment(Request $request)
     {
        // dd($request);
   // dd('TXN' . Str::random(10));
   $existingDonor = DonarList::where('email', $request->email)->first();
   $transactionId = Carbon::now()->format('YmdHis'); 
   //dd($transactionId);
   if ($existingDonor) {
    // Use existing donor ID
    $donarId = $existingDonor->id;
} else {
   $newDonor= DonarList::create([
    'name'=> $request->name,
    'email'=> $request->email,
    'phone_number'=> $request->phone_number,
    'address_1'=> $request->address_1,
    'city_1'=> $request->city_1,
    'state_1'=> $request->state_1,
    'country_1'=> $request->country_1,
    'pincode_1'=> $request->pincode_1,
    'address_2'=> $request->address_2,
    'city_2'=> $request->city_2,
    'state_2'=> $request->state_2,
    'country_2'=> $request->country_2,
    'pincode_2'=> $request->pincode_2,
    'pan_aadhar'=> $request->pan_card,

]);
$donarId = $newDonor->id;

}

// dd($transactionId);
    $transaction = DonationStatementTrans::create([
        'donation_id' => $request->donation_id,
        'donar_id' => $donarId, // You can replace this with authenticated user ID if needed
        'donar_name' => $request->name,
        'donation_name' => $request->donation_heading,
        'amount' => $request->amount,
        'transection_id' => $transactionId ,// Dummy transaction ID
        'status' => 'pending',
    ]);
         // Generate unique Transaction ID
        //  $transactionId = randomId(); // Generate Unique Transaction ID
$paymentUrl = env('PAYMENT_GATEWAY_URL') . "?amount={$request->amount}&txn_id={$transactionId}";
//  dd( $paymentUrl);
         // Create Payment Order Details
         $PaymentOrderDetails = PaymentOrdersDetails::create([
             'user_return_Url' => route('donation.redirect'), // Laravel Route instead of hardcoded URL
             'user_id'         => 0,
             'amount'          => $request->amount,
             'name'            => $request->name,
             'mobNo'           => $request->phone_number,
             'currency'        => 'INR',
             'paymentMode'     => 'all',
             'scheme'          => 'FIRST',
             'internal_txn_id' => $transactionId,
         ]);
 //dd($PaymentOrderDetails);
          if ($PaymentOrderDetails->id) {
         $strNo = rand(1, 1000000);
                    date_default_timezone_set('Asia/Calcutta');
                    $strCurDate = date('Y-m-d');
                    $transactionRequestBean = new TransactionRequestBean();
                    $transactionRequestBean->merchantCode = env('MERCHANT_CODE_1');
                    $transactionRequestBean->ITC = "admin@santhoshavidhyalaya.com";
                    $transactionRequestBean->customerName = $request->name;
                    $transactionRequestBean->requestType = "T";
                    $transactionRequestBean->merchantTxnRefNumber =  $transactionId;
                    $transactionRequestBean->amount = number_format($request->amount, 2, '.', '');
                    $transactionRequestBean->currencyCode = 'INR';
                    $transactionRequestBean->returnURL = route('donation.redirect');
                      $school_fee = env('SCHOOL_FEE_SCHEME_CDOE_1') . "_" . number_format(10.00, 2, '.', '') . "_0.0"; 

                   // $transactionRequestBean->shoppingCartDetails = env('SCHOOL_FEE_SCHEME_CDOE')."_".number_format(10.00,2,'.', '')."_".number_format(0.00,2,'.', '')."";
                    $transactionRequestBean->shoppingCartDetails = $school_fee;
                    
                //   $hostel_fee = env('HOSTEL_FEE_SCHEME_CDOE') . "_" . number_format($account2_amount, 2, '.', '') . "_0.0"; 
                     
                    $transactionRequestBean->TPSLTxnID = '';
                    $transactionRequestBean->mobileNumber = 0;
                    $transactionRequestBean->txnDate = date('Y-m-d');
                    $transactionRequestBean->bankCode = 470;
                    $transactionRequestBean->custId = 12;
                    $transactionRequestBean->key = env('ENCRYPTION_KEY_1');
                    $transactionRequestBean->iv = env('ENCRYPTION_IV_1');
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
             //     dd($payment_req_data);

                    $PaymentOrderDetails = PaymentReqData::create($payment_req_data);
 
             // Get transaction token
             $responseDetails = (array) $transactionRequestBean->getTransactionToken();
             $response = $responseDetails[0];
 
             // Redirect to Payment Gateway
             return response()->json([
                 'message' => 'Redirecting to Payment Gateway...',
                 'payment_url' => $response
             ]);
         } else {
             return redirect(route('donations.redirect'))->with('error', 'Payment processing failed.');
         }
     }
     public function donationRedirect()
     {
      
         return view('donations.success')->with('success', 'Admission Form Send Successfully!'); // Create a success page in resources/views/donation/success.blade.php
     }
     public function paymentSuccess(Request $request)
{
    // Get transaction ID from the request
    $transactionId = $request->input('txn_id');

    // Validate and process the payment
    $payment = PaymentOrdersDetails::where('internal_txn_id', $transactionId)->first();
    
    if ($payment && $payment->status == 'Success') {
        // Payment verified, redirect to Thank You page
        return redirect()->route('thank.you')->with('success', 'Payment Successful!');
    } else {
        return redirect()->route('home')->with('error', 'Payment Failed or Invalid Transaction');
    }
}

        //profile photo upload
        // $profile_path = 'profile' . $admission->id . '.' . $request->profile_photo->extension();
        // $request->profile_photo->storeAs('profile_photos', $profile_path);
        // $admission->profile_photo = $profile_path;

        //admission photo upload
        // $admission_path = 'admission' . $admission->id . '.' . $request->admission_photo->extension();
        // $request->admission_photo->storeAs('admission_photos', $admission_path);
        //$admission->admission_photo = $admission_path;

        // Admission::where('id', $admission->id)
        //     ->update([
        //         'profile_photo' => $profile_path,
        //         'admission_photo' => $admission_path,
        //     ]);
// Mail::send('emails.admissionMail', $admissionData, function($message) use ($admissionData, $pdfContent) {
//     $message->to('admissions@santhoshavidhyalaya.com')
//             ->subject('Admission PDF');
//     $message->attachData($pdfContent, 'admission.pdf', [
//         'mime' => 'application/pdf',
//     ]);
// });

// if (Mail::failures()) {
//     return redirect('/error')->with('error', 'Failed to send email!');
// }


//     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function show($id)
{
    $donation = DonationList::findOrFail($id);
    return view('donations.details', compact('donation'));
}

public function confirm(Request $request)
{
    // Determine final amount (either predefined or user-entered)
    $finalAmount = ($request->amount == 'other') ? $request->other_amount : $request->amount;

    // Validate required fields
    $request->validate([
        'donation_id' => 'required',
        'donation_heading' => 'required|string',
        'name' => 'required|string',
        'email' => 'required|email',
        'phone_number' => 'required|string',
        'pan_card' => 'required|string',
        'address_1' => 'required|string',
        'city_1' => 'required|string',
        'state_1' => 'required|string',
        'pincode_1' => 'required|string',
        'amount' => 'required',
    ]);

    // Pass all form data including final amount to confirmation page
    $data = $request->all();
    $data['final_amount'] = $finalAmount;

    return view('donations.confirm', compact('data'));
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
      public function admissionRetrunResponse(Request $request)
    {
        // dd($request);
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
        // dd($transactionResponseBean->setResponsePayload($str));
        $transactionResponseBean->setResponsePayload($str);
        $transactionResponseBean->key = env('ENCRYPTION_KEY');
        $transactionResponseBean->iv = env('ENCRYPTION_IV');
        $response = $transactionResponseBean->getResponsePayload();

        // Read Response
        $msg = $response; 
        $res_msg = explode("|",$msg);
        
        // dd($res_msg);
     //   die;
        // Internal transaction id
        $save_payment_orders_status_data['clnt_txn_ref'] = explodeArray($res_msg[3])->value;
        $internal_trasactionid = explodeArray($res_msg[3])->value;
        $paymentOrdersDetails_model_data = PaymentOrdersDetails::where('internal_txn_id', $internal_trasactionid)->first(); //order id
        $paymentOrdersDetails_id = $paymentOrdersDetails_model_data['id'];
        $return_res_data['return_data'] =  $paymentOrdersDetails_model_data['user_return_req_data'];
        $front_end_retrun_url = $paymentOrdersDetails_model_data['user_return_Url'];
        
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
             
        //  dd($PaymentOrdersStatus);

$result = Admission::
    where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
    ->orderBy('payment_order_id', 'asc')
    ->first();
    //dd($result);
    if ($result) {
     $HrtPage2TemplateData = array(
     "name"=>isset($result->name)?$result->name:null,
   "date_form"=>isset($result->date_form)?$result->date_form:null,
   "language"=>isset($result->language)?$result->language:null,
   "state_student"=>isset($result->state_student)?$result->state_student:null,
   "date_of_birth"=>isset($result->date_of_birth)?$result->date_of_birth:null,
   "gender"=>isset($result->gender)?$result->gender:null,
   "blood_group"=>isset($result->blood_group)?$result->blood_group:null,
   "nationality"=>isset($result->nationality)?$result->nationality:null,
   "religion"=>isset($result->religion)?$result->religion:null,
   "church_denomination"=>isset($result->church_denomination)?$result->church_denomination:null,
   "caste"=>isset($result->caste)?$result->caste:null,
   "caste_type"=>isset($result->caste_type)?$result->caste_type:null,
   "aadhar_card_no"=>isset($result->aadhar_card_no)?$result->aadhar_card_no:null,
   "ration_card_no"=>isset($result->ration_card_no)?$result->ration_card_no:null,
   "emis_no"=>isset($result->emis_no)?$result->emis_no:null,
   "veg_or_non"=>isset($result->veg_or_non)?$result->veg_or_non:null,
   "chronic_des"=>isset($result->chronic_des)?$result->chronic_des:null,
   "medicine_taken"=>isset($result->medicine_taken)?$result->medicine_taken:null,
      "father_title"=>isset($result->father_title)?$result->father_title:null,
   "father_name"=>isset($result->father_name)?$result->father_name:null,
   "father_occupation"=>isset($result->father_occupation)?$result->father_occupation:null,
    "mother_title"=>isset($result->mother_title)?$result->mother_title:null,
   "mother_name"=>isset($result->mother_name)?$result->mother_name:null,
   "mother_occupation"=>isset($result->mother_occupation)?$result->mother_occupation:null,
   "guardian_title"=>isset($result->guardian_title)?$result->guardian_title:null,
   "guardian_name"=>isset($result->guardian_name)?$result->guardian_name:null,
   "guardian_occupation"=>isset($result->guardian_occupation)?$result->guardian_occupation:null,
   "father_contact_no"=>isset($result->father_contact_no)?$result->father_contact_no:null,
   "father_email_id"=>isset($result->father_email_id)?$result->father_email_id:null,
   "mother_contact_no"=>isset($result->mother_contact_no)?$result->mother_contact_no:null,
   "mother_email_id"=>isset($result->mother_email_id)?$result->mother_email_id:null,
   "guardian_contact_no"=>isset($result->guardian_contact_no)?$result->guardian_contact_no:null,
   "guardian_email_id"=>isset($result->guardian_email_id)?$result->guardian_email_id:null,
   "house_no"=>isset($result->house_no)?$result->house_no:null,
   "street"=>isset($result->street)?$result->street:null,
   "city"=>isset($result->city)?$result->city:null,
   "district"=>isset($result->district)?$result->district:null,
   "state"=>isset($result->state)?$result->state:null,
   "pincode"=>isset($result->pincode)?$result->pincode:null,
   "house_no_1"=>isset($result->house_no_1)?$result->house_no_1:null,
   "street_1"=>isset($result->street_1)?$result->street_1:null,
   "city_1"=>isset($result->city_1)?$result->city_1:null,
   "district_1"=>isset($result->district_1)?$result->district_1:null,
   "state_1"=>isset($result->state_1)?$result->state_1:null,
   "pincode_1"=>isset($result->pincode_1)?$result->pincode_1:null,
   "last_class_std"=>isset($result->last_class_std)?$result->last_class_std:null,
   "last_school"=>isset($result->last_school)?$result->last_school:null,
   "admission_for_class"=>isset($result->admission_for_class)?$result->admission_for_class:null,
   "syllabus"=>isset($result->syllabus)?$result->syllabus:null,
   "group_no"=>isset($result->group_no)?$result->group_no:null,
   "second_group_no"=>isset($result->second_group_no)?$result->second_group_no:null,
   "second_language"=>isset($result->second_language)?$result->second_language:null,
    "profile_photo" => isset($result->profile_photo)?$result->profile_photo:null,
       "admission_photo" => isset($result->admission_photo)?$result->admission_photo:null,
     "birth_certificate_photo" => isset($result->birth_certificate_photo)?$result->birth_certificate_photo:null,
     "aadhar_card_photo" => isset($result->aadhar_card_photo)?$result->aadhar_card_photo:null,
     "ration_card_photo" => isset($result->ration_card_photo)?$result->ration_card_photo:null,
     "community_certificate" => isset($result->community_certificate)?$result->community_certificate:null,
     "slip_photo" => isset($result->slip_photo)?$result->slip_photo:null,
     "medical_certificate_photo" => isset($result->medical_certificate_photo)?$result->medical_certificate_photo:null,
     "reference_letter_photo" => isset($result->reference_letter_photo)?$result->reference_letter_photo:null,
     "church_certificate_photo" => isset($result->church_certificate_photo)?$result->church_certificate_photo:null,
     "transfer_certificate_photo" => isset($result->transfer_certificate_photo)?$result->transfer_certificate_photo:null,
     "father_organization" => isset($result->father_organization)?$result->father_organization:null,
     "mother_organization" => isset($result->mother_organization)?$result->mother_organization:null,
     "guardian_organization" => isset($result->guardian_organization)?$result->guardian_organization:null,
     "status"=>"Applied"

     
            
            );
       $admissionform = AdmissionForm::create($HrtPage2TemplateData);

    $HrtPage2TemplateDataToAdmiited = array(
        "admission_id"=>$admissionform->id,
     "STUDENT_NAME"=>isset($result->name)?$result->name:null,
   "date_form"=>isset($result->date_form)?$result->date_form:null,
   "MOTHERTONGUE"=>isset($result->language)?$result->language:null,
   "STATE"=>isset($result->state_student)?$result->state_student:null,
   "DOB_DD_MM_YYYY"=>isset($result->date_of_birth)?$result->date_of_birth:null,
   "SEX"=>isset($result->gender)?$result->gender:null,
   "BLOOD_GROUP"=>isset($result->blood_group)?$result->blood_group:null,
   "NATIONALITY"=>isset($result->nationality)?$result->nationality:null,
   "RELIGION"=>isset($result->religion)?$result->religion:null,
   "DENOMINATION"=>isset($result->church_denomination)?$result->church_denomination:null,
   "CASTE"=>isset($result->caste)?$result->caste:null,
   "CASTE_CLASSIFICATION"=>isset($result->caste_type)?$result->caste_type:null,
   "AADHAAR_CARD_NO"=>isset($result->aadhar_card_no)?$result->aadhar_card_no:null,
   "RATIONCARDNO"=>isset($result->ration_card_no)?$result->ration_card_no:null,
   "EMIS_NO"=>isset($result->emis_no)?$result->emis_no:null,
   "FOOD"=>isset($result->veg_or_non)?$result->veg_or_non:null,
   "chronic_des"=>isset($result->chronic_des)?$result->chronic_des:null,
   "medicine_taken"=>isset($result->medicine_taken)?$result->medicine_taken:null,
        "father_title"=>isset($result->father_title)?$result->father_title:null,

   "FATHER"=>isset($result->father_name)?$result->father_name:null,
   "OCCUPATION"=>isset($result->father_occupation)?$result->father_occupation:null,
      "mother_title"=>isset($result->mother_title)?$result->mother_title:null,
   "MOTHER"=>isset($result->mother_name)?$result->mother_name:null,
   "mother_occupation"=>isset($result->mother_occupation)?$result->mother_occupation:null,
   "guardian_title"=>isset($result->guardian_title)?$result->guardian_title:null,
   "GUARDIAN"=>isset($result->guardian_name)?$result->guardian_name:null,
   "guardian_occupation"=>isset($result->guardian_occupation)?$result->guardian_occupation:null,
   "MOBILE_NUMBER"=>isset($result->father_contact_no)?$result->father_contact_no:null,
   "EMAIL_ID"=>isset($result->father_email_id)?$result->father_email_id:null,
   "WHATS_APP_NO"=>isset($result->mother_contact_no)?$result->mother_contact_no:null,
   "mother_email_id"=>isset($result->mother_email_id)?$result->mother_email_id:null,
   "guardian_contact_no"=>isset($result->guardian_contact_no)?$result->guardian_contact_no:null,
   "guardian_email_id"=>isset($result->guardian_email_id)?$result->guardian_email_id:null,
   "PERMANENT_HOUSENUMBER"=>isset($result->house_no)?$result->house_no:null,
   "P_STREETNAME"=>isset($result->street)?$result->street:null,
   "P_VILLAGE_TOWN_NAME"=>isset($result->city)?$result->city:null,
   "P_DISTRICT"=>isset($result->district)?$result->district:null,
   "P_STATE"=>isset($result->state)?$result->state:null,
   "P_PINCODE"=>isset($result->pincode)?$result->pincode:null,
   "COMMUNICATION_HOUSE_NO"=>isset($result->house_no_1)?$result->house_no_1:null,
   "C_STREET_NAME"=>isset($result->street_1)?$result->street_1:null,
   "C_VILLAGE_TOWN_NAME"=>isset($result->city_1)?$result->city_1:null,
   "C_DISTRICT"=>isset($result->district_1)?$result->district_1:null,
   "C_STATE"=>isset($result->state_1)?$result->state_1:null,
   "C_PINCODE"=>isset($result->pincode_1)?$result->pincode_1:null,
   "CLASS_LAST_STUDIED"=>isset($result->last_class_std)?$result->last_class_std:null,
   "NAME_OF_SCHOOL"=>isset($result->last_school)?$result->last_school:null,
   "SOUGHT_STD"=>isset($result->admission_for_class)?$result->admission_for_class:null,
   "syllabus"=>isset($result->syllabus)?$result->syllabus:null,
   "GROUP_12"=>isset($result->group_no)?$result->group_no:null,
   "second_group_no"=>isset($result->second_group_no)?$result->second_group_no:null,
   "LANG_PART_I"=>isset($result->second_language)?$result->second_language:null,
    "profile_photo" => isset($result->profile_photo)?$result->profile_photo:null,
       "admission_photo" => isset($result->admission_photo)?$result->admission_photo:null,
     "birth_certificate_photo" => isset($result->birth_certificate_photo)?$result->birth_certificate_photo:null,
     "aadhar_card_photo" => isset($result->aadhar_card_photo)?$result->aadhar_card_photo:null,
     "ration_card_photo" => isset($result->ration_card_photo)?$result->ration_card_photo:null,
     "community_certificate" => isset($result->community_certificate)?$result->community_certificate:null,
     "slip_photo" => isset($result->slip_photo)?$result->slip_photo:null,
     "medical_certificate_photo" => isset($result->medical_certificate_photo)?$result->medical_certificate_photo:null,
     "reference_letter_photo" => isset($result->reference_letter_photo)?$result->reference_letter_photo:null,
     "church_certificate_photo" => isset($result->church_certificate_photo)?$result->church_certificate_photo:null,
     "transfer_certificate_photo" => isset($result->transfer_certificate_photo)?$result->transfer_certificate_photo:null,
     "ORGANISATION" => isset($result->father_organization)?$result->father_organization:null,
     "mother_organization" => isset($result->mother_organization)?$result->mother_organization:null,
     "guardian_organization" => isset($result->guardian_organization)?$result->guardian_organization:null,
     
   
     "reference_phone_2"=> isset($result->reference_phone_2)?$result->reference_phone_2:null,
     "reference_phone_1"=> isset($result->reference_phone_1)?$result->reference_phone_1:null,
     "reference_name_2"=> isset($result->reference_name_2)?$result->reference_name_2:null,
     "reference_name_1"=> isset($result->reference_name_1)?$result->reference_name_1:null,
     "second_language"=> isset($result->second_language)?$result->second_language:null,
     "second_language_school"=> isset($result->second_language_school)?$result->second_language_school:null,
     "last_school_state"=> isset($result->last_school_state)?$result->last_school_state:null,
     "class_3"=> isset($result->class_3)?$result->class_3:null,
     "gender_3"=> isset($result->gender_3)?$result->gender_3:null,
     "brother_3"=> isset($result->brother_3)?$result->brother_3:null,
     "class_2"=> isset($result->class_2)?$result->class_2:null,
     "class_1"=> isset($result->class_1)?$result->class_1:null,
     "gender_2"=> isset($result->gender_2)?$result->gender_2:null,
     "gender_1"=> isset($result->gender_1)?$result->gender_1:null,
     "brother_2"=> isset($result->brother_2)?$result->brother_2:null,
     "brother_1"=> isset($result->brother_1)?$result->brother_1:null,
     "MONTHLY_INCOME"=> isset($result->father_income)?$result->father_income:null,
     "mother_income"  => isset($result->mother_income)?$result->mother_income:null,
     "guardian_income"  => isset($result->guardian_income)?$result->guardian_income:null, 
     "status"=>"Applied"
            );
      $admissionformadmitted =   Student::create($HrtPage2TemplateDataToAdmiited);   
            // dd( $HrtPage2TemplateData);
      $result = Admission::
    where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
    ->orderBy('payment_order_id', 'asc')
    ->first();
//   dd($result);
            $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
            $profile_pic =  $result->profile_photo;
             $profile_pic1 =  $result->admission_photo;
              $profile_pic2 =  $result->birth_certificate_photo;
               $profile_pic3 =  $result->aadhar_card_photo;
                $profile_pic4 =  $result->ration_card_photo;
                 $profile_pic5 =  $result->community_certificate_photo;
                  $profile_pic6 =  $result->slip_photo;
                   $profile_pic7 =  $result->medical_certificate_photo;
                   $profile_pic8 =  $result->reference_letter_photo;
                    $profile_pic9 =  $result->church_certificate_photo;
                     $profile_pic10 =  $result->transfer_certificate_photo;
        //mail ku data $admissionData intha variable la pass pannaum
      $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
$admissionData = ['admissionData' => 'https://www.santhoshavidhyalaya.com/SVS/admission-view/'.$admissionform1 ];

Mail::send('emails.admissionMail', $admissionData, function ($message) use ($profile_pic, $profile_pic2, $profile_pic1,$profile_pic3,$profile_pic4,$profile_pic5,$profile_pic6,$profile_pic7,$profile_pic8,$profile_pic9,$profile_pic10)  {
    $message->to('admissions@santhoshavidhyalaya.com')->cc('principal@santhoshavidhyalaya.com','prince@santhoshavidhyalaya.com','udhaya.suriya@eucto.com')
        ->subject('Admission Form');

    // Attach profile photo if it exists
    if ($profile_pic && Storage::exists('profile_photos/'.$profile_pic)) {
        $message->attach(Storage::path('profile_photos/'.$profile_pic), [
            'as' => $profile_pic,
            'mime' => 'image/jpeg',
        ]);
    }

    // Attach birth certificate photo if it exists
    if ($profile_pic2 && Storage::exists('birth_certificate_photos/'.$profile_pic2)) {
        $message->attach(Storage::path('birth_certificate_photos/'.$profile_pic2), [
            'as' => $profile_pic2,
            'mime' => 'image/jpeg',
        ]);
    }

    // Attach admission photo if it exists
    if ($profile_pic1 && Storage::exists('admission_photos/'.$profile_pic1)) {
        $message->attach(Storage::path('admission_photos/'.$profile_pic1), [
            'as' => $profile_pic1,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic3 && Storage::exists('aadhar_card_photos'.$profile_pic3)) {
        $message->attach(Storage::path('aadhar_card_photos'.$profile_pic3), [
            'as' => $profile_pic3,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic4 && Storage::exists('ration_card_photos/'.$profile_pic4)) {
        $message->attach(Storage::path('ration_card_photos/'.$profile_pic4), [
            'as' => $profile_pic4,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic5 && Storage::exists('community_certificate_photos/'.$profile_pic5)) {
        $message->attach(Storage::path('community_certificate_photos/'.$profile_pic5), [
            'as' => $profile_pic5,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic6 && Storage::exists('slip_photos/'.$profile_pic6)) {
        $message->attach(Storage::path('slip_photos/'.$profile_pic6), [
            'as' => $profile_pic6,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic7 && Storage::exists('medical_certificate_photos/'.$profile_pic7)) {
        $message->attach(Storage::path('medical_certificate_photos/'.$profile_pic7), [
            'as' => $profile_pic7,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic8 && Storage::exists('reference_letter_photos/'.$profile_pic8)) {
        $message->attach(Storage::path('reference_letter_photos/'.$profile_pic8), [
            'as' => $profile_pic8,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic9 && Storage::exists('church_certificate_photos/'.$profile_pic9)) {
        $message->attach(Storage::path('church_certificate_photos/'.$profile_pic9), [
            'as' => $profile_pic9,
            'mime' => 'image/jpeg',
        ]);
    }
     // Attach admission photo if it exists
    if ($profile_pic10 && Storage::exists('transfer_certificate_photo/'.$profile_pic10)) {
        $message->attach(Storage::path('transfer_certificate_photo/'.$profile_pic10), [
            'as' => $profile_pic10,
            'mime' => 'image/jpeg',
        ]);
    }
  
});
 if($result->father_email_id != '' ){
       $admissionData2 = ['name' => $result->father_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->father_email_id];
 //dd($admissionData2);
Mail::send('emails.admissionMail2', $admissionData2, function ($message) use ($admissionData2)  {
    // dd($admissionData2['father_mail']);
    $message->to($admissionData2['father_mail'])
        ->subject('Confirmation of Admission Form Submission for Your Child');
    
});
        
    }else if($result->mother_email_id != '' ){
        $admissionData2 = ['name' => $result->mother_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->mother_email_id];
// dd($admissionData2);
Mail::send('$result->mother_email_id', $admissionData2, function ($message) use ($admissionData2)  {
   // dd($admissionData2);
    $message->to($admissionData2['father_mail'])
        ->subject('Confirmation of Admission Form Submission for Your Child');
    
});
    }else {
      $admissionData2 = ['name' => $result->guardian_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->guardia_email_id];
// dd($admissionData2);
Mail::send('emails.admissionMail2', $admissionData2, function ($message) use ($admissionData2)  {
  // dd($admissionData2);
    $message->to($admissionData2['father_mail'])
        ->subject('Confirmation of Admission Form Submission for Your Child');
    
});

    }

    return view('admission.thankyou')->with('success', 'Admission Form Send Successfully!');

} else {
    return response()->json(['message' => 'No data found'], 404);
}
             
             
         }
        else{

$result = Admission::
    where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
    ->orderBy('payment_order_id', 'asc')
    ->first();

return view('admission.addedit',['data'=> $result])->with(['message' => 'Application Form is not sent']);

        }
        
    }
    
    
    
    
     public function view($id)
    {
    // dd($id);
        // Retrieve the record from the database based on the ID
        $admission = AdmissionForm:: where('payment_order_id', $id)
   // ->orderBy('payment_order_id', 'asc')
    ->first();
// dd($admission);

        // Check if the record exists
        // if ($admission) {
        //     // Pass the admission data to the view
        //     return view('admission.view_admission', ['admission' => $admission]);
        // } else {
        //     // Handle case where the record with the specified ID does not exist
        //   //  abort(404);
        //               return view('admission.view_admission', ['admission' => 'null']);

        // }
        
      
  if (isset($admission) && ($admission->name !== null || $admission->name !== '')) {
    // Pass the admission data to the view
    return view('admission.view_admission', ['admission' => $admission]);
} else {
    // Handle case where the record with the specified ID does not exist
    $admission1 = Admission::where('payment_order_id', $id)->first();
    if (isset($admission1) && ($admission1->name !== null || $admission1->name !== '')) {
        return view('admission.view_admission', ['admission' => $admission1]);
    } else {
        return view('admission.view_admission', ['admission' => '']);
    }
}


    }
    
         public function documents($id)
    {
        // dd($id);
        // Retrieve the record from the database based on the ID
        $admission = AdmissionForm:: where('id', 'LIKE', $id)
    ->orderBy('id', 'asc')
    ->first();
    //  dd($admission);

        // Check if the record exists
        if ($admission) {
            // Pass the admission data to the view
            return view('admission.documents', ['admission' => $admission]);
        } else {
            // Handle case where the record with the specified ID does not exist
            abort(404);
        }
    }
}

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Donation;

// class DonationController extends Controller
// {
//     public function submitDonation(Request $request)
//     {
//         $request->validate([
//             'image' => 'required|image|max:2048',
//             'amount' => 'required',
//             'name'   => 'required',
//             'email'  => 'required|email',
//             'phone'  => 'required|digits:10',
//         ]);

//         $path = $request->file('image')->store('donations', 'public');

//         Donation::create([
//             'image'  => $path,
//             'amount' => $request->amount,
//             'name'   => $request->name,
//             'email'  => $request->email,
//             'phone'  => $request->phone,
//         ]);

//         return response()->json(['message' => 'Donation submitted successfully!']);
//     }
// }
