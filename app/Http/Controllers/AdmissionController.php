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
use DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Helpers\SmsHelper;
use App\Helpers\HelperEmail;
use Illuminate\Support\Facades\DB;


date_default_timezone_set('Asia/Kolkata');


require_once app_path('Helpers/TransactionRequestBean.php');
require_once app_path('Helpers/TransactionResponseBean.php');

class AdmissionController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $imageFields = [
                'profile_image',
                'birth_certificate_image',
                'aadhar_image',
                'ration_card_image',
                'community_image',
                'salary_image',
                'reference_letter_image',
                'transfer_certificate_image',
                'migration_image',
                'church_endorsement_image',
            ];

        $schoolSlug = request()->route('school');
        $mappedData = [];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                $filename = now()->format('Ymd_His') . '_' . $field . '.' . $file->getClientOriginalExtension();
                
                $path = 'documents/' . $schoolSlug . '/admission_form/' . $filename;

                Storage::disk('s3')->put($path, file_get_contents($file));

                                    // Set the full URL for accessing the image
                $mappedData[$field] = Storage::disk('s3')->url($path);
                } else {
                    $mappedData[$field] = null;
                }
        }

        $inputData = array_merge($request->except($imageFields), $mappedData);

        $admission = new Admission();
        $admission->fill($inputData);
        $admission->save();

        $student = new Student();
        $student->admission_id = $admission->id;
        $student->fill($inputData);
        $student->save();
        
        
        // Sending admission confirmation email
        // $admissionData = $admission->toArray();
        // Mail::send('emails.admissionMail', $admissionData, function ($message) use ($admissionData, $pdfContent) {
        //     $toEmail = 'vidhyamca94@gmail.com'; // Ensure this is not empty or null

        //     if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        //         return redirect('/error')->with('error', 'Invalid recipient email!');
        //     }

        //     $message->to($toEmail)
        //             ->subject('Admission PDF')
        //             ->attachData($pdfContent, 'admission.pdf', [
        //                 'mime' => 'application/pdf',
        //             ]);
        // });

        if (Mail::failures()) {
            return redirect('/error')->with('error', 'Failed to send email!');
        }

        
        return redirect()->back()->with('success', 'Admission stored successfully!');
    }


    public function index()
    {  
        $schoolSlug = request()->route('school');

        $school = DB::connection('central')->table('schools')->where('name', $schoolSlug)->first();

        return view('admission.addedit', compact('school'));
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
 
        return view('admission.data',['data'=>$data]);
        
    }
    public function Data_1(){
        // dd("a");
        $result = AdmissionForm::get();
        $result_1 = PaymentOrdersDetails::get();
        //dd($result_1->toArray());
        $result = AdmissionForm::join('payment_orders_statuses', 'admission_process_live.payment_order_id', '=', 'payment_orders_statuses.clnt_txn_ref')
            ->select('admission_process_live.*', 'payment_orders_statuses.*') 
            ->get();

        dd($result->toArray());
            $data = $result->toArray();
        

                return view('admission.data_1',['data'=>$data]);
        
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
       public function offline_store(Request $request){
            
            //dd($request);
                 $admission = new AdmissionForm();
          
        $admission-> name = $request->name;
        $admission-> date_form = $request->date_form;
        $admission-> language = $request->language;
        $admission-> state_student = $request->state_student;
        $admission-> date_of_birth = $request->date_of_birth;
        $admission-> gender = $request->gender;
        $admission-> blood_group = $request->blood_group;
        $admission-> nationality = $request->nationality;
        $admission-> religion = $request->religion;
        $admission-> church_denomination = $request->church_denomination;
        $admission-> caste = $request->caste;
        $admission-> caste_type = $request->caste_type;
        $admission-> aadhar_card_no = $request->aadhar_card_no;
        $admission-> ration_card_no = $request->ration_card_no;
        $admission-> emis_no = $request->emis_no;
        $admission-> veg_or_non = $request->veg_or_non;
        $admission-> chronic_des = $request->chronic_des;
        $admission-> medicine_taken = $request->medicine_taken;
        //   $admission-> father_name = $request->father_name;
        $admission->father_name = $request->father_title . ' ' . $request->father_name;
        $admission-> father_occupation = $request->father_occupation;
        //   $admission-> mother_name = $request->mother_name;
        $admission-> mother_name =  $request->mother_title . ' ' . $request->mother_name;
        $admission-> mother_occupation = $request->mother_occupation;
        //   $admission-> guardian_name = $request->guardian_name;
            $admission-> guardian_name = $request->guardian_title . ' ' . $request->guardian_name;

        $admission-> guardian_occupation = $request->guardian_occupation;
        $admission-> father_contact_no = $request->father_contact_no;
        $admission-> father_email_id = $request->father_email_id;
        $admission-> mother_contact_no = $request->mother_contact_no;
        $admission-> mother_email_id = $request->mother_email_id;
        $admission-> guardian_contact_no = $request->guardian_contact_no;
        $admission-> guardian_email_id = $request->guardian_email_id;
        $admission-> house_no = $request->house_no;
        $admission-> street = $request->street;
        $admission-> city = $request->city;
        $admission-> district = $request->district;
        $admission-> state = $request->state;
        $admission-> pincode = $request->pincode;
        $admission-> house_no_1 = $request->house_no_1;
        $admission-> street_1 = $request->street_1;
        $admission-> city_1 = $request->city_1;
        $admission-> district_1 = $request->district_1;
        $admission-> state_1 = $request->state_1;
        $admission-> pincode_1 = $request->pincode_1;
        $admission-> last_class_std = $request->last_class_std;
        $admission-> last_school = $request->last_school;
        $admission-> admission_for_class = $request->admission_for_class;
        $admission-> syllabus = $request->syllabus;
        $admission-> group_no = $request->group_no;
        $admission-> second_group_no = $request->second_group_no;
        $admission-> second_language = $request->second_language;
        $admission-> brother_1 = $request->brother_1;
        $admission-> brother_2 = $request->brother_2;
        $admission-> brother_3 = $request->brother_3;
        $admission-> gender_1 = $request->gender_1;
        $admission-> gender_2 = $request->gender_2;
        $admission-> gender_3 = $request->gender_3;
        $admission-> class_1 = $request->class_1;
        $admission-> class_2 = $request->class_2;
        $admission-> class_3 = $request->class_3;
        $admission-> father_income = $request->father_income;
        $admission-> mother_income = $request->mother_income;
        $admission-> guardian_income = $request->guardian_income;
        $admission-> reference_name_1 = $request->reference_name_1;
        $admission-> reference_phone_1 = $request->reference_phone_1;
        $admission-> reference_name_2 = $request->reference_name_2;
        $admission-> reference_phone_2 = $request->reference_phone_1;
        $admission-> second_language_school = $request->second_language_school;
        $admission-> last_school_state = $request->last_school_state;
        $admission-> father_organization = $request->father_organization;
        $admission-> mother_organization = $request->mother_organization;
        $admission-> guardian_organization = $request->father_organization;

        
        $transactionId = randomId();
         $admission->payment_order_id	 =  $transactionId;
       
        $admission->save();
        // dd($admission);
        if ($request->hasFile('profile_photo')) {
            $profile_path = 'profile' . $admission->id . '.' . $request->profile_photo->extension();
            $request->profile_photo->storeAs('profile_photos', $profile_path);

            // Update the admission model with the profile photo path
            $admission->update(['profile_photo' => $profile_path]);
        }
        if ($request->hasFile('admission_photo')) {
            $admission_path = 'admission' . $admission->id . '.' . $request->admission_photo->extension();
            $request->admission_photo->storeAs('admission_photos', $admission_path);

            // Update the admission model with the profile photo path
            $admission->update(['admission_photo' => $admission_path]);
        }        

        if ($request->hasFile('birth_certificate_photo')) {
            $birth_certificate_path = 'birth_certificate' . $admission->id . '.' . $request->birth_certificate_photo->extension();
            $request->birth_certificate_photo->storeAs('birth_certificate_photos', $birth_certificate_path);

            // Update the admission model with the profile photo path
            $admission->update(['birth_certificate_photo' => $birth_certificate_path]);
        }

        if ($request->hasFile('aadhar_card_photo')) {
                    $aadhar_card_path = 'aadhar_card' . $admission->id . '.' . $request->aadhar_card_photo->extension();
                    $request->aadhar_card_photo->storeAs('aadhar_card_photos', $aadhar_card_path);
                
                    // Update the aadhar_card model with the profile photo path
                    $admission->update(['aadhar_card_photo' => $aadhar_card_path]);
                }  
        if ($request->hasFile('ration_card_photo')) {
            $ration_card_path = 'ration_card' . $admission->id . '.' . $request->ration_card_photo->extension();
            $request->ration_card_photo->storeAs('ration_card_photos', $ration_card_path);
        
            // Update the ration_card model with the profile photo path
            $admission->update(['ration_card_photo' => $ration_card_path]);
        }
        
        
        if ($request->hasFile('community_certificate_photo')) {
            $community_certificate_path = 'community_certificate' . $admission->id . '.' . $request->community_certificate_photo->extension();
            $request->community_certificate_photo->storeAs('community_certificate_photos', $community_certificate_path);
        
            // Update the community_certificate model with the profile photo path
            $admission->update(['community_certificate' => $community_certificate_path]);
        }
             if ($request->hasFile('slip_photo')) {
            $slip_path = 'slip' . $admission->id . '.' . $request->slip_photo->extension();
            $request->slip_photo->storeAs('slip_photos', $slip_path);
        
            // Update the slip model with the profile photo path
            $admission->update(['slip_photo' => $slip_path]);
        }
         if ($request->hasFile('medical_certificate_photo')) {
            $medical_certificate_path = 'medical_certificate' . $admission->id . '.' . $request->medical_certificate_photo->extension();
            $request->medical_certificate_photo->storeAs('medical_certificate_photos', $medical_certificate_path);
        
            // Update the medical_certificate model with the profile photo path
            $admission->update(['medical_certificate_photo' => $medical_certificate_path]);
        }
         if ($request->hasFile('reference_letter_photo')) {
            $reference_letter_path = 'reference_letter' . $admission->id . '.' . $request->reference_letter_photo->extension();
            $request->reference_letter_photo->storeAs('reference_letter_photos', $reference_letter_path);
        
            // Update the reference_letter model with the profile photo path
            $admission->update(['reference_letter_photo' => $reference_letter_path]);
        }
           if ($request->hasFile('church_certificate_photo')) {
            $church_certificate_path = 'church_certificate' . $admission->id . '.' . $request->church_certificate_photo->extension();
            $request->church_certificate_photo->storeAs('church_certificate_photos', $church_certificate_path);
        
            // Update the church_certificate model with the profile photo path
            $admission->update(['church_certificate_photo' => $church_certificate_path]);
        }
        
        if ($request->hasFile('transfer_certificate_photo')) {
            $transfer_certificate_path = 'transfer_certificate' . $admission->id . '.' . $request->transfer_certificate_photo->extension();
            $request->transfer_certificate_photo->storeAs('transfer_certificate_photos', $transfer_certificate_path);
        
            // Update the transfer_certificate model with the profile photo path
            $admission->update(['transfer_certificate_photo' => $transfer_certificate_path]);
        }
        // dd( $admission);
        // $transactionId = randomId();
    // $admissionform->payment_order_id = $transactionId;
    
        $requestform = new AdmissionForm([
             "name"=>isset($admission->name)?$admission->name:null,
        "date_form"=>isset($admission->date_form)?$admission->date_form:null,
        "language"=>isset($admission->language)?$admission->language:null,
        "state_student"=>isset($admission->state_student)?$admission->state_student:null,
        "date_of_birth"=>isset($admission->date_of_birth)?$admission->date_of_birth:null,
        "gender"=>isset($admission->gender)?$admission->gender:null,
        "blood_group"=>isset($admission->blood_group)?$admission->blood_group:null,
        "nationality"=>isset($admission->nationality)?$admission->nationality:null,
        "religion"=>isset($admission->religion)?$admission->religion:null,
        "church_denomination"=>isset($admission->church_denomination)?$admission->church_denomination:null,
        "caste"=>isset($admission->caste)?$admission->caste:null,
        "caste_type"=>isset($admission->caste_type)?$admission->caste_type:null,
        "aadhar_card_no"=>isset($admission->aadhar_card_no)?$admission->aadhar_card_no:null,
        "ration_card_no"=>isset($admission->ration_card_no)?$admission->ration_card_no:null,
        "emis_no"=>isset($admission->emis_no)?$admission->emis_no:null,
        "veg_or_non"=>isset($admission->veg_or_non)?$admission->veg_or_non:null,
        "chronic_des"=>isset($admission->chronic_des)?$admission->chronic_des:null,
        "medicine_taken"=>isset($admission->medicine_taken)?$admission->medicine_taken:null,
        "father_name"=>isset($admission->father_name)?$admission->father_name:null,
        "father_occupation"=>isset($admission->father_occupation)?$admission->father_occupation:null,
        "mother_name"=>isset($admission->mother_name)?$admission->mother_name:null,
        "mother_occupation"=>isset($admission->mother_occupation)?$admission->mother_occupation:null,
        "guardian_name"=>isset($admission->guardian_name)?$admission->guardian_name:null,
        "guardian_occupation"=>isset($admission->guardian_occupation)?$admission->guardian_occupation:null,
        "father_contact_no"=>isset($admission->father_contact_no)?$admission->father_contact_no:null,
        "father_email_id"=>isset($admission->father_email_id)?$admission->father_email_id:null,
        "mother_contact_no"=>isset($admission->mother_contact_no)?$admission->mother_contact_no:null,
        "mother_email_id"=>isset($admission->mother_email_id)?$admission->mother_email_id:null,
        "guardian_contact_no"=>isset($admission->guardian_contact_no)?$admission->guardian_contact_no:null,
        "guardian_email_id"=>isset($admission->guardian_email_id)?$admission->guardian_email_id:null,
        "house_no"=>isset($admission->house_no)?$admission->house_no:null,
        "street"=>isset($admission->street)?$admission->street:null,
        "city"=>isset($admission->city)?$admission->city:null,
        "district"=>isset($admission->district)?$admission->district:null,
        "state"=>isset($admission->state)?$admission->state:null,
        "pincode"=>isset($admission->pincode)?$admission->pincode:null,
        "house_no_1"=>isset($admission->house_no_1)?$admission->house_no_1:null,
        "street_1"=>isset($admission->street_1)?$admission->street_1:null,
        "city_1"=>isset($admission->city_1)?$admission->city_1:null,
        "district_1"=>isset($admission->district_1)?$admission->district_1:null,
        "state_1"=>isset($admission->state_1)?$admission->state_1:null,
        "pincode_1"=>isset($admission->pincode_1)?$admission->pincode_1:null,
        "last_class_std"=>isset($admission->last_class_std)?$admission->last_class_std:null,
        "last_school"=>isset($admission->last_school)?$admission->last_school:null,
        "admission_for_class"=>isset($admission->admission_for_class)?$admission->admission_for_class:null,
        "syllabus"=>isset($admission->syllabus)?$admission->syllabus:null,
        "group_no"=>isset($admission->group_no)?$admission->group_no:null,
        "second_group_no"=>isset($admission->second_group_no)?$admission->second_group_no:null,
        "second_language"=>isset($admission->second_language)?$admission->second_language:null,
            "profile_photo" => isset($admission->profile_photo)?$admission->profile_photo:null,
            "admission_photo" => isset($admission->admission_photo)?$admission->admission_photo:null,
            "birth_certificate_photo" => isset($admission->birth_certificate_photo)?$admission->birth_certificate_photo:null,
            "aadhar_card_photo" => isset($admission->aadhar_card_photo)?$admission->aadhar_card_photo:null,
            "ration_card_photo" => isset($admission->ration_card_photo)?$admission->ration_card_photo:null,
            "community_certificate" => isset($admission->community_certificate)?$admission->community_certificate:null,
            "slip_photo" => isset($admission->slip_photo)?$admission->slip_photo:null,
            "medical_certificate_photo" => isset($admission->medical_certificate_photo)?$admission->medical_certificate_photo:null,
            "reference_letter_photo" => isset($admission->reference_letter_photo)?$admission->reference_letter_photo:null,
            "church_certificate_photo" => isset($admission->church_certificate_photo)?$admission->church_certificate_photo:null,
            "transfer_certificate_photo" => isset($admission->transfer_certificate_photo)?$admission->transfer_certificate_photo:null,
            "father_organization" => isset($admission->father_organization)?$admission->father_organization:null,
            "mother_organization" => isset($admission->mother_organization)?$admission->mother_organization:null,
            "guardian_organization" => isset($admission->guardian_organization)?$admission->guardian_organization:null,
            "payment_order_id" =>  $admission->payment_order_id
            ]);
      $result = AdmissionForm::
    where('payment_order_id', 'LIKE', $requestform->payment_order_id)
    ->orderBy('payment_order_id', 'asc')
    ->first();
//  dd($result);
            $admissionform1 = $requestform->payment_order_id;
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
        $admissionform1 = $requestform->payment_order_id;
        $admissionData = ['admissionData' => 'https://www.santhoshavidhyalaya.com/SVS/admission-view/'.$admissionform1 ];
        // dd($admissionData);
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
    //  dd($admissionform);
                    //  $admissionform = AdmissionForm::create($admissionform);

                 return view('admission.thankyou');

        // dd($admission);
         
     }
    // public function store(Request $request)
    // {
    //    //dd($request);
    //     $appUrl = env('APP_URL');
        
    //     $admission = new Admission();
          
    //     $admission-> name = $request->name;
    //     $admission-> date_form = $request->date_form;
    //     $admission-> language = $request->language;
    //     $admission-> state_student = $request->state_student;
    //     $admission-> date_of_birth = $request->date_of_birth;
    //     $admission-> gender = $request->gender;
    //     $admission-> blood_group = $request->blood_group;
    //     $admission-> nationality = $request->nationality;
    //     $admission-> religion = $request->religion;
    //     $admission-> church_denomination = $request->church_denomination;
    //     $admission-> caste = $request->caste;
    //     $admission-> caste_type = $request->caste_type;
    //     $admission-> aadhar_card_no = $request->aadhar_card_no;
    //     $admission-> ration_card_no = $request->ration_card_no;
    //     $admission-> emis_no = $request->emis_no;
    //     $admission-> veg_or_non = $request->veg_or_non;
    //     $admission-> chronic_des = $request->chronic_des;
    //     $admission-> medicine_taken = $request->medicine_taken;
    //     $admission->father_title = $request->father_title ;
    //     $admission->father_name = $request->father_name ;
    //     $admission-> father_occupation = $request->father_occupation;
    //     $admission-> mother_title =  $request->mother_title;
    //     $admission-> mother_name =  $request->mother_name;
    //     $admission-> mother_occupation = $request->mother_occupation;
    //     $admission-> guardian_name = $request->guardian_title . ' ' . $request->guardian_name;
    //     $admission-> guardian_occupation = $request->guardian_occupation;
    //     $admission-> father_contact_no = $request->father_contact_no;
    //     $admission-> father_email_id = $request->father_email_id;
    //     $admission-> mother_contact_no = $request->mother_contact_no;
    //     $admission-> mother_email_id = $request->mother_email_id;
    //     $admission-> guardian_contact_no = $request->guardian_contact_no;
    //     $admission-> guardian_email_id = $request->guardian_email_id;
    //     $admission-> house_no = $request->house_no;
    //     $admission-> street = $request->street;
    //     $admission-> city = $request->city;
    //     $admission-> district = $request->district;
    //     $admission-> state = $request->state;
    //     $admission-> pincode = $request->pincode;
    //     $admission-> house_no_1 = $request->house_no_1;
    //     $admission-> street_1 = $request->street_1;
    //     $admission-> city_1 = $request->city_1;
    //     $admission-> district_1 = $request->district_1;
    //     $admission-> state_1 = $request->state_1;
    //     $admission-> pincode_1 = $request->pincode_1;
    //     $admission-> last_class_std = $request->last_class_std;
    //     $admission-> last_school = $request->last_school;
    //     $admission-> admission_for_class = $request->admission_for_class;
    //     $admission-> syllabus = $request->syllabus;
    //     $admission-> group_no = $request->group_no;
    //     $admission-> second_group_no = $request->second_group_no;
    //     $admission-> second_language = $request->second_language;
    //     $admission-> brother_1 = $request->brother_1;
    //     $admission-> brother_2 = $request->brother_2;
    //     $admission-> brother_3 = $request->brother_3;
    //     $admission-> gender_1 = $request->gender_1;
    //     $admission-> gender_2 = $request->gender_2;
    //     $admission-> gender_3 = $request->gender_3;
    //     $admission-> class_1 = $request->class_1;
    //     $admission-> class_2 = $request->class_2;
    //     $admission-> class_3 = $request->class_3;
    //     $admission-> father_income = $request->father_income;
    //     $admission-> mother_income = $request->mother_income;
    //     $admission-> guardian_income = $request->guardian_income;
    //     $admission-> reference_name_1 = $request->reference_name_1;
    //     $admission-> reference_phone_1 = $request->reference_phone_1;
    //     $admission-> reference_name_2 = $request->reference_name_2;
    //     $admission-> reference_phone_2 = $request->reference_phone_1;
    //     $admission-> second_language_school = $request->second_language_school;
    //     $admission-> last_school_state = $request->last_school_state;
    //     $admission-> father_organization = $request->father_organization;
    //     $admission-> mother_organization = $request->mother_organization;
    //     $admission-> guardian_organization = $request->father_organization;

        
    //     $transactionId = randomId();
    //      $admission->payment_order_id	 =  $transactionId;
        
    //     $admission->save();
      
    //     if ($request->hasFile('profile_photo')) {
    //         $profile_path = 'profile' . $admission->id . '.' . $request->profile_photo->extension();
    //         $request->profile_photo->storeAs('profile_photos', $profile_path);

    //         // Update the admission model with the profile photo path
    //         $admission->update(['profile_photo' => $profile_path]);
    //     }
    //     if ($request->hasFile('admission_photo')) {
    //         $admission_path = 'admission' . $admission->id . '.' . $request->admission_photo->extension();
    //         $request->admission_photo->storeAs('admission_photos', $admission_path);

    //         // Update the admission model with the profile photo path
    //         $admission->update(['admission_photo' => $admission_path]);
    //     }        

    //     if ($request->hasFile('birth_certificate_photo')) {
    //         $birth_certificate_path = 'birth_certificate' . $admission->id . '.' . $request->birth_certificate_photo->extension();
    //         $request->birth_certificate_photo->storeAs('birth_certificate_photos', $birth_certificate_path);

    //         // Update the admission model with the profile photo path
    //         $admission->update(['birth_certificate_photo' => $birth_certificate_path]);
    //     }

    //     if ($request->hasFile('aadhar_card_photo')) {
    //                 $aadhar_card_path = 'aadhar_card' . $admission->id . '.' . $request->aadhar_card_photo->extension();
    //                 $request->aadhar_card_photo->storeAs('aadhar_card_photos', $aadhar_card_path);
                
    //                 // Update the aadhar_card model with the profile photo path
    //                 $admission->update(['aadhar_card_photo' => $aadhar_card_path]);
    //             }  
    //     if ($request->hasFile('ration_card_photo')) {
    //         $ration_card_path = 'ration_card' . $admission->id . '.' . $request->ration_card_photo->extension();
    //         $request->ration_card_photo->storeAs('ration_card_photos', $ration_card_path);
        
    //         // Update the ration_card model with the profile photo path
    //         $admission->update(['ration_card_photo' => $ration_card_path]);
    //     }
        
        
    //     if ($request->hasFile('community_certificate_photo')) {
    //         $community_certificate_path = 'community_certificate' . $admission->id . '.' . $request->community_certificate_photo->extension();
    //         $request->community_certificate_photo->storeAs('community_certificate_photos', $community_certificate_path);
        
    //         // Update the community_certificate model with the profile photo path
    //         $admission->update(['community_certificate' => $community_certificate_path]);
    //     }
    //          if ($request->hasFile('slip_photo')) {
    //         $slip_path = 'slip' . $admission->id . '.' . $request->slip_photo->extension();
    //         $request->slip_photo->storeAs('slip_photos', $slip_path);
        
    //         // Update the slip model with the profile photo path
    //         $admission->update(['slip_photo' => $slip_path]);
    //     }
    //      if ($request->hasFile('medical_certificate_photo')) {
    //         $medical_certificate_path = 'medical_certificate' . $admission->id . '.' . $request->medical_certificate_photo->extension();
    //         $request->medical_certificate_photo->storeAs('medical_certificate_photos', $medical_certificate_path);
        
    //         // Update the medical_certificate model with the profile photo path
    //         $admission->update(['medical_certificate_photo' => $medical_certificate_path]);
    //     }
    //      if ($request->hasFile('reference_letter_photo')) {
    //         $reference_letter_path = 'reference_letter' . $admission->id . '.' . $request->reference_letter_photo->extension();
    //         $request->reference_letter_photo->storeAs('reference_letter_photos', $reference_letter_path);
        
    //         // Update the reference_letter model with the profile photo path
    //         $admission->update(['reference_letter_photo' => $reference_letter_path]);
    //     }
    //        if ($request->hasFile('church_certificate_photo')) {
    //         $church_certificate_path = 'church_certificate' . $admission->id . '.' . $request->church_certificate_photo->extension();
    //         $request->church_certificate_photo->storeAs('church_certificate_photos', $church_certificate_path);
        
    //         // Update the church_certificate model with the profile photo path
    //         $admission->update(['church_certificate_photo' => $church_certificate_path]);
    //     }
        
    //     if ($request->hasFile('transfer_certificate_photo')) {
    //         $transfer_certificate_path = 'transfer_certificate' . $admission->id . '.' . $request->transfer_certificate_photo->extension();
    //         $request->transfer_certificate_photo->storeAs('transfer_certificate_photos', $transfer_certificate_path);
        
    //         // Update the transfer_certificate model with the profile photo path
    //         $admission->update(['transfer_certificate_photo' => $transfer_certificate_path]);
    //     }

               
    //     // Save payment order details and retrun url after faild or success
    //     $payment_order_data['user_return_Url'] ="https://santhoshavidhyalaya.com/SVS/admission";
    //     $payment_order_data['user_retrun_req_data'] = null;
    //     $payment_order_data['user_access_key'] =null;
    //     $payment_order_data['internal_txn_id'] = $transactionId ;
    //     $payment_order_data['user_id'] = 0 ;
    //     $payment_order_data['amount'] = 10;
    //     $payment_order_data['maxAmount'] = null;
    //     $payment_order_data['name'] = $request->first_name;
    //     $payment_order_data['custID']  = 0;
    //     $payment_order_data['mobNo'] =$request->mobile_number;
        
    //     //Payment 
    //     $payment_order_data['paymentMode'] = 'all';
    //     $payment_order_data['accNo'] = null;
    //     $payment_order_data['debitStartDate']=null;
    //     $payment_order_data['debitEndDate'] = null;
    //     $payment_order_data['amountType'] =null;
    //     $payment_order_data['currency'] = 'INR';
    //     $payment_order_data['frequency'] =null;
    //     $payment_order_data['cardNumber'] =null;
    //     $payment_order_data['expMonth'] =null;
    //     $payment_order_data['expYear'] =null;
    //     $payment_order_data['cvvCode'] =null;
    //     $payment_order_data['scheme'] ='FIRST';
    //     $payment_order_data['accountName'] =null;
    //     $payment_order_data['ifscCode'] =null;
    //     $payment_order_data['accountType'] =null;
    //     $payment_order_data['payment_status'] = null;
    //     $payment_order_data['order_hash_value'] =null;
    //     // dd($$payment_order_data);
    //     $PaymentOrderDetails = PaymentOrdersDetails::create($payment_order_data);
        
        
    //     $returnUrl = $appUrl.'/admission-redirect';  //after apyament return url path
    //     //New 
    //             if($PaymentOrderDetails->id)
    //             { 
    //                 ob_start();
    //                 error_reporting(E_ALL);
    //                 $strNo = rand(1, 1000000);
    //                 date_default_timezone_set('Asia/Calcutta');
    //                 $strCurDate = date('Y-m-d');
    //                 $transactionRequestBean = new TransactionRequestBean();
    //                 $transactionRequestBean->merchantCode = env('MERCHANT_CODE');
    //                 $transactionRequestBean->ITC = "admin@santhoshavidhyalaya.com";
    //                 $transactionRequestBean->customerName = "admin";
    //                 $transactionRequestBean->requestType = "T";
    //                 $transactionRequestBean->merchantTxnRefNumber =  $transactionId;
    //                 $transactionRequestBean->amount = 10.00;
    //                 $transactionRequestBean->currencyCode = 'INR';
    //                 $transactionRequestBean->returnURL = $returnUrl;
    //                   $school_fee = env('SCHOOL_FEE_SCHEME_CDOE') . "_" . number_format(10.00, 2, '.', '') . "_0.0"; 

    //                // $transactionRequestBean->shoppingCartDetails = env('SCHOOL_FEE_SCHEME_CDOE')."_".number_format(10.00,2,'.', '')."_".number_format(0.00,2,'.', '')."";
    //                 $transactionRequestBean->shoppingCartDetails = $school_fee;
                    
    //             //   $hostel_fee = env('HOSTEL_FEE_SCHEME_CDOE') . "_" . number_format($account2_amount, 2, '.', '') . "_0.0"; 
                     
    //                 $transactionRequestBean->TPSLTxnID = '';
    //                 $transactionRequestBean->mobileNumber = 0;
    //                 $transactionRequestBean->txnDate = date('Y-m-d');
    //                 $transactionRequestBean->bankCode = 470;
    //                 $transactionRequestBean->custId = 12;
    //                 $transactionRequestBean->key = env('ENCRYPTION_KEY');
    //                 $transactionRequestBean->iv = env('ENCRYPTION_IV');
    //                 $transactionRequestBean->accountNo = '';
    //                 $transactionRequestBean->webServiceLocator = 'https://www.tpsl-india.in/PaymentGateway/TransactionDetailsNew.wsdl';
    //                 $transactionRequestBean->timeOut = 30;


    //                 $payment_req_data['payment_req_customerName'] = $transactionRequestBean->customerName;
    //                 $payment_req_data['payment_req_merchantCode'] = $transactionRequestBean->merchantCode;
    //                 $payment_req_data['payment_req_ITC'] = $transactionRequestBean->ITC;
    //                 $payment_req_data['payment_req_requestType']=$transactionRequestBean->requestType;
    //                 $payment_req_data['payment_req_merchantTxnRefNumber'] = $transactionRequestBean->merchantTxnRefNumber;
    //                 $payment_req_data['payment_req_amount'] =$transactionRequestBean->amount;
    //                 $payment_req_data['payment_req_currencyCode'] = $transactionRequestBean->currencyCode;
    //                 $payment_req_data['payment_req_returnURL'] = $transactionRequestBean->returnURL;
    //                 $payment_req_data['payment_req_shoppingCartDetails'] = $transactionRequestBean->shoppingCartDetails;
    //                 $payment_req_data['payment_req_TPSLTxnID'] = $transactionRequestBean->TPSLTxnID;
    //                 $payment_req_data['payment_req_mobileNumber'] = $transactionRequestBean->mobileNumber;
    //                 $payment_req_data['payment_req_txnDate'] = $transactionRequestBean->txnDate;
    //                 $payment_req_data['payment_req_bankCode'] = 'FIRST';
    //                 $payment_req_data['payment_req_custId'] = $transactionRequestBean->custId;
    //                 $payment_req_data['payment_req_key'] = $transactionRequestBean->key;
    //                 $payment_req_data['payment_req_iv'] = $transactionRequestBean->iv;
    //                 $payment_req_data['payment_req_accountNo'] = $transactionRequestBean->accountNo;
    //                 $payment_req_data['payment_req_webServiceLocator_PHP_EOL'] = $transactionRequestBean->webServiceLocator.PHP_EOL;
    //          //     dd($payment_req_data);

    //                 $PaymentOrderDetails = PaymentReqData::create($payment_req_data);
    //                 // dd($PaymentOrderDetail);
    //                 $responseDetails = $transactionRequestBean->getTransactionToken();
    //                 $responseDetails = (array)$responseDetails;
    //                 $response = $responseDetails[0];
    //                 echo "<script>window.location = '" . $response . "'</script>";
    //                 ob_flush();
    //             }
    //             else{
    //                 return redirect("https://santhoshavidhyalaya.com/SVS/admission");
    //             }
        
    //     Mail::send('emails.admissionMail', $admissionData, function($message) use ($admissionData, $pdfContent) {
    //         $message->to('admissions@santhoshavidhyalaya.com')
    //                 ->subject('Admission PDF');
    //         $message->attachData($pdfContent, 'admission.pdf', [
    //             'mime' => 'application/pdf',
    //         ]);
    //     });

    //     if (Mail::failures()) {
    //         return redirect('/error')->with('error', 'Failed to send email!');
    //     }


    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
       //dd($request);
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
       "father_income"=> isset($result->father_income)?$result->father_income:null,
     "mother_income"  => isset($result->mother_income)?$result->mother_income:null,
     "guardian_income"  => isset($result->guardian_income)?$result->guardian_income:null, 
       "brother_1"  => isset($result->brother_1)?$result->brother_1:null, 
        "brother_2"  => isset($result->brother_2)?$result->brother_2:null, 
        "gender_1"  => isset($result->gender_1)?$result->gender_1:null, 
        "gender_2"	 => isset($result->gender_2)?$result->gender_2:null, 
        "class_1"	 => isset($result->class_1)?$result->class_1:null, 
        "class_2"	 => isset($result->class_2)?$result->class_2:null, 
        "brother_3"	 => isset($result->brother_3)?$result->brother_3:null, 
        "gender_3"	 => isset($result->gender_3)?$result->gender_3:null, 
        "class_3"    => isset($result->class_3)?$result->class_3:null, 
        "last_school_state"   => isset($result->last_school_state)?$result->last_school_state:null, 
        "second_language_school"  => isset($result->second_language_school)?$result->second_language_school:null, 
        "reference_name_1"  => isset($result->reference_name_1)?$result->reference_name_1:null, 
        "reference_name_2"  => isset($result->reference_name_2)?$result->reference_name_2:null, 
        "reference_phone_1" => isset($result->reference_phone_1)?$result->reference_phone_1:null, 
        "reference_phone_2" => isset($result->reference_phone_2)?$result->reference_phone_2:null, 
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
Mail::send('$result->mother_email_id', $admissionData2, function ($message) use ($admissionData2) {
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

$fatherNumber = isset($result->father_contact_no) ? $result->father_contact_no : null;
$motherNumber = isset($result->mother_contact_no) ? $result->mother_contact_no : null;

// Choose which number to use
$mobileToSend = $fatherNumber ?? $motherNumber;

SmsHelper::sendTemplateSms(
    'E Student Management - Application',
    $mobileToSend,
    []
);


// Mail::send('emails.admissionMail', $admissionData, function ($message)use ($profile_pic,$profile_pic1,$profile_pic2,$profile_pic3,$profile_pic4,$profile_pic5,$profile_pic6,$profile_pic7,$profile_pic8,$profile_pic9,$profile_pic10)  {
//     $message->to('udhayatech19@gmail.com')
//         ->subject('Admission Form') 
       
        // ->attach(Storage::path('profile_photos/'.$profile_pic), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('admission_photos/'.$profile_pic1), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('birth_certificate_photos/'.$profile_pic2), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('aadhar_card_photos/'.$profile_pic3), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('ration_card_photos/'.$profile_pic4), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('community_certificate_photos/'.$profile_pic5), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('slip_photos/'.$profile_pic6), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('medical_certificate_photos/'.$profile_pic7), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('reference_letter_photos/'.$profile_pic8), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('church_certificate_photos/'.$profile_pic9), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('transfer_certificate_photos/'.$profile_pic10), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ]);
        
// });

    return view('admission.thankyou')->with('success', 'Admission Form Send Successfully!');

} else {
    return response()->json(['message' => 'No data found'], 404);
}
             
             
         }
        else{
            
//             $result = Admission::
//     where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
//     ->orderBy('payment_order_id', 'asc')
//     ->first();
//      //dd($result);
//     if ($result) {
//         $HrtPage2TemplateData = array(
//      "name"=>isset($result->name)?$result->name:null,
//   "date_form"=>isset($result->date_form)?$result->date_form:null,
//   "language"=>isset($result->language)?$result->language:null,
//   "state_student"=>isset($result->state_student)?$result->state_student:null,
//   "date_of_birth"=>isset($result->date_of_birth)?$result->date_of_birth:null,
//   "gender"=>isset($result->gender)?$result->gender:null,
//   "blood_group"=>isset($result->blood_group)?$result->blood_group:null,
//   "nationality"=>isset($result->nationality)?$result->nationality:null,
//   "religion"=>isset($result->religion)?$result->religion:null,
//   "church_denomination"=>isset($result->church_denomination)?$result->church_denomination:null,
//   "caste"=>isset($result->caste)?$result->caste:null,
//   "caste_type"=>isset($result->caste_type)?$result->caste_type:null,
//   "aadhar_card_no"=>isset($result->aadhar_card_no)?$result->aadhar_card_no:null,
//   "ration_card_no"=>isset($result->ration_card_no)?$result->ration_card_no:null,
//   "emis_no"=>isset($result->emis_no)?$result->emis_no:null,
//   "veg_or_non"=>isset($result->veg_or_non)?$result->veg_or_non:null,
//   "chronic_des"=>isset($result->chronic_des)?$result->chronic_des:null,
//   "medicine_taken"=>isset($result->medicine_taken)?$result->medicine_taken:null,
//   "father_name"=>isset($result->father_name)?$result->father_name:null,
//   "father_occupation"=>isset($result->father_occupation)?$result->father_occupation:null,
//   "mother_name"=>isset($result->mother_name)?$result->mother_name:null,
//   "mother_occupation"=>isset($result->mother_occupation)?$result->mother_occupation:null,
//   "guardian_name"=>isset($result->guardian_name)?$result->guardian_name:null,
//   "guardian_occupation"=>isset($result->guardian_occupation)?$result->guardian_occupation:null,
//   "father_contact_no"=>isset($result->father_contact_no)?$result->father_contact_no:null,
//   "father_email_id"=>isset($result->father_email_id)?$result->father_email_id:null,
//   "mother_contact_no"=>isset($result->mother_contact_no)?$result->mother_contact_no:null,
//   "mother_email_id"=>isset($result->mother_email_id)?$result->mother_email_id:null,
//   "guardian_contact_no"=>isset($result->guardian_contact_no)?$result->guardian_contact_no:null,
//   "guardian_email_id"=>isset($result->guardian_email_id)?$result->guardian_email_id:null,
//   "house_no"=>isset($result->house_no)?$result->house_no:null,
//   "street"=>isset($result->street)?$result->street:null,
//   "city"=>isset($result->city)?$result->city:null,
//   "district"=>isset($result->district)?$result->district:null,
//   "state"=>isset($result->state)?$result->state:null,
//   "pincode"=>isset($result->pincode)?$result->pincode:null,
//   "house_no_1"=>isset($result->house_no_1)?$result->house_no_1:null,
//   "street_1"=>isset($result->street_1)?$result->street_1:null,
//   "city_1"=>isset($result->city_1)?$result->city_1:null,
//   "district_1"=>isset($result->district_1)?$result->district_1:null,
//   "state_1"=>isset($result->state_1)?$result->state_1:null,
//   "pincode_1"=>isset($result->pincode_1)?$result->pincode_1:null,
//   "last_class_std"=>isset($result->last_class_std)?$result->last_class_std:null,
//   "last_school"=>isset($result->last_school)?$result->last_school:null,
//   "admission_for_class"=>isset($result->admission_for_class)?$result->admission_for_class:null,
//   "syllabus"=>isset($result->syllabus)?$result->syllabus:null,
//   "group_no"=>isset($result->group_no)?$result->group_no:null,
//   "second_group_no"=>isset($result->second_group_no)?$result->second_group_no:null,
//   "second_language"=>isset($result->second_language)?$result->second_language:null,
//     "profile_photo" => isset($result->profile_photo)?$result->profile_photo:null,
//       "admission_photo" => isset($result->admission_photo)?$result->admission_photo:null,
//      "birth_certificate_photo" => isset($result->birth_certificate_photo)?$result->birth_certificate_photo:null,
//      "aadhar_card_photo" => isset($result->aadhar_card_photo)?$result->aadhar_card_photo:null,
//      "ration_card_photo" => isset($result->ration_card_photo)?$result->ration_card_photo:null,
//      "community_certificate" => isset($result->community_certificate)?$result->community_certificate:null,
//      "slip_photo" => isset($result->slip_photo)?$result->slip_photo:null,
//      "medical_certificate_photo" => isset($result->medical_certificate_photo)?$result->medical_certificate_photo:null,
//      "reference_letter_photo" => isset($result->reference_letter_photo)?$result->reference_letter_photo:null,
//      "church_certificate_photo" => isset($result->church_certificate_photo)?$result->church_certificate_photo:null,
//      "transfer_certificate_photo" => isset($result->transfer_certificate_photo)?$result->transfer_certificate_photo:null,
//      "father_organization" => isset($result->father_organization)?$result->father_organization:null,
//      "mother_organization" => isset($result->mother_organization)?$result->mother_organization:null,
//      "guardian_organization" => isset($result->guardian_organization)?$result->guardian_organization:null,

     
            
//             );
//             // dd( $HrtPage2TemplateData);
//             $admissionform = AdmissionForm::create($HrtPage2TemplateData);
//       $result = Admission::
//     where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
//     ->orderBy('payment_order_id', 'asc')
//     ->first();
// //   dd($result);
//             $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
//             $profile_pic =  $result->profile_photo;
//              $profile_pic1 =  $result->admission_photo;
//               $profile_pic2 =  $result->birth_certificate_photo;
//               $profile_pic3 =  $result->aadhar_card_photo;
//                 $profile_pic4 =  $result->ration_card_photo;
//                  $profile_pic5 =  $result->community_certificate_photo;
//                   $profile_pic6 =  $result->slip_photo;
//                   $profile_pic7 =  $result->medical_certificate_photo;
//                   $profile_pic8 =  $result->reference_letter_photo;
//                     $profile_pic9 =  $result->church_certificate_photo;
//                      $profile_pic10 =  $result->transfer_certificate_photo;
//         //mail ku data $admissionData intha variable la pass pannaum
//       $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
// $admissionData = ['admissionData' => 'https://www.santhoshavidhyalaya.com/SVS/admission-view/'.$admissionform1 ];

// Mail::send('emails.admissionMail', $admissionData, function ($message) use ($profile_pic, $profile_pic2, $profile_pic1,$profile_pic3,$profile_pic4,$profile_pic5,$profile_pic6,$profile_pic7,$profile_pic8,$profile_pic9,$profile_pic10)  {
//     $message->to('udhaya.suriya@eucto.com')
//         ->subject('Admission Form');

//     // Attach profile photo if it exists
//     if ($profile_pic && Storage::exists('profile_photos/'.$profile_pic)) {
//         $message->attach(Storage::path('profile_photos/'.$profile_pic), [
//             'as' => $profile_pic,
//             'mime' => 'image/jpeg',
//         ]);
//     }

//     // Attach birth certificate photo if it exists
//     if ($profile_pic2 && Storage::exists('birth_certificate_photos/'.$profile_pic2)) {
//         $message->attach(Storage::path('birth_certificate_photos/'.$profile_pic2), [
//             'as' => $profile_pic2,
//             'mime' => 'image/jpeg',
//         ]);
//     }

//     // Attach admission photo if it exists
//     if ($profile_pic1 && Storage::exists('admission_photos/'.$profile_pic1)) {
//         $message->attach(Storage::path('admission_photos/'.$profile_pic1), [
//             'as' => $profile_pic1,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic3 && Storage::exists('aadhar_card_photos'.$profile_pic3)) {
//         $message->attach(Storage::path('aadhar_card_photos'.$profile_pic3), [
//             'as' => $profile_pic3,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic4 && Storage::exists('ration_card_photos/'.$profile_pic4)) {
//         $message->attach(Storage::path('ration_card_photos/'.$profile_pic4), [
//             'as' => $profile_pic4,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic5 && Storage::exists('community_certificate_photos/'.$profile_pic5)) {
//         $message->attach(Storage::path('community_certificate_photos/'.$profile_pic5), [
//             'as' => $profile_pic5,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic6 && Storage::exists('slip_photos/'.$profile_pic6)) {
//         $message->attach(Storage::path('slip_photos/'.$profile_pic6), [
//             'as' => $profile_pic6,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic7 && Storage::exists('medical_certificate_photos/'.$profile_pic7)) {
//         $message->attach(Storage::path('medical_certificate_photos/'.$profile_pic7), [
//             'as' => $profile_pic7,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic8 && Storage::exists('reference_letter_photos/'.$profile_pic8)) {
//         $message->attach(Storage::path('reference_letter_photos/'.$profile_pic8), [
//             'as' => $profile_pic8,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic9 && Storage::exists('church_certificate_photos/'.$profile_pic9)) {
//         $message->attach(Storage::path('church_certificate_photos/'.$profile_pic9), [
//             'as' => $profile_pic9,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic10 && Storage::exists('transfer_certificate_photo/'.$profile_pic10)) {
//         $message->attach(Storage::path('transfer_certificate_photo/'.$profile_pic10), [
//             'as' => $profile_pic10,
//             'mime' => 'image/jpeg',
//         ]);
//     }
  
// });
//  if($result->father_email_id != '' ){
//       $admissionData2 = ['name' => $result->father_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->father_email_id];
//  //dd($admissionData2);
// Mail::send('emails.admissionMail2', $admissionData2, function ($message) use ($admissionData2)  {
//     // dd($admissionData2['father_mail']);
//     $message->to($admissionData2['father_mail'])
//         ->subject('Confirmation of Admission Form Submission for Your Child');
    
// });
        
//     }else if($result->mother_email_id != '' ){
//         $admissionData2 = ['name' => $result->mother_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->mother_email_id];
// // dd($admissionData2);
// Mail::send('$result->mother_email_id', $admissionData2, function ($message) use ($admissionData2)  {
//   // dd($admissionData2);
//     $message->to($admissionData2['father_mail'])
//         ->subject('Confirmation of Admission Form Submission for Your Child');
    
// });
//     }else {
//       $admissionData2 = ['name' => $result->guardian_name,'child_name' => $result->name,'class_name' => $result->admission_for_class,'father_mail'=> $result->guardia_email_id];
// // dd($admissionData2);
// Mail::send('emails.admissionMail2', $admissionData2, function ($message) use ($admissionData2)  {
//   // dd($admissionData2);
//     $message->to($admissionData2['father_mail'])
//         ->subject('Confirmation of Admission Form Submission for Your Child');
    
// });

    // }
// Mail::send('emails.admissionMail', $admissionData, function ($message)use ($profile_pic,$profile_pic1,$profile_pic2,$profile_pic3,$profile_pic4,$profile_pic5,$profile_pic6,$profile_pic7,$profile_pic8,$profile_pic9,$profile_pic10)  {
//     $message->to('udhayatech19@gmail.com')
//         ->subject('Admission Form') 
       
        // ->attach(Storage::path('profile_photos/'.$profile_pic), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('admission_photos/'.$profile_pic1), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('birth_certificate_photos/'.$profile_pic2), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('aadhar_card_photos/'.$profile_pic3), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('ration_card_photos/'.$profile_pic4), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('community_certificate_photos/'.$profile_pic5), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('slip_photos/'.$profile_pic6), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('medical_certificate_photos/'.$profile_pic7), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('reference_letter_photos/'.$profile_pic8), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('church_certificate_photos/'.$profile_pic9), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ])
        // ->attach(Storage::path('transfer_certificate_photos/'.$profile_pic10), [
        //     'as' => $profile_pic,
        //     'mime' => 'image/jpeg',
        // ]);
        
// });
// return response()->json(['message' => 'Data stored failed'], 200);
// } else {
//     return response()->json(['message' => 'No data found'], 404);
// }
        
//           // dd($PaymentOrdersStatus);
//             //  $paymentOrderId = '20240207090949056';

$result = Admission::
    where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
    ->orderBy('payment_order_id', 'asc')
    ->first();
//dd($result);
//     if ($result) {
//         $HrtPage2TemplateData = array(
//      "name"=>isset($result->name)?$result->name:null,
//   "date_form"=>isset($result->date_form)?$result->date_form:null,
//   "language"=>isset($result->language)?$result->language:null,
//   "state_student"=>isset($result->state_student)?$result->state_student:null,
//   "date_of_birth"=>isset($result->date_of_birth)?$result->date_of_birth:null,
//   "gender"=>isset($result->gender)?$result->gender:null,
//   "blood_group"=>isset($result->blood_group)?$result->blood_group:null,
//   "nationality"=>isset($result->nationality)?$result->nationality:null,
//   "religion"=>isset($result->religion)?$result->religion:null,
//   "church_denomination"=>isset($result->church_denomination)?$result->church_denomination:null,
//   "caste"=>isset($result->caste)?$result->caste:null,
//   "caste_type"=>isset($result->caste_type)?$result->caste_type:null,
//   "aadhar_card_no"=>isset($result->aadhar_card_no)?$result->aadhar_card_no:null,
//   "ration_card_no"=>isset($result->ration_card_no)?$result->ration_card_no:null,
//   "emis_no"=>isset($result->emis_no)?$result->emis_no:null,
//   "veg_or_non"=>isset($result->veg_or_non)?$result->veg_or_non:null,
//   "chronic_des"=>isset($result->chronic_des)?$result->chronic_des:null,
//   "medicine_taken"=>isset($result->medicine_taken)?$result->medicine_taken:null,
//   "father_name"=>isset($result->father_name)?$result->father_name:null,
//   "father_occupation"=>isset($result->father_occupation)?$result->father_occupation:null,
//   "mother_name"=>isset($result->mother_name)?$result->mother_name:null,
//   "mother_occupation"=>isset($result->mother_occupation)?$result->mother_occupation:null,
//   "guardian_name"=>isset($result->guardian_name)?$result->guardian_name:null,
//   "guardian_occupation"=>isset($result->guardian_occupation)?$result->guardian_occupation:null,
//   "father_contact_no"=>isset($result->father_contact_no)?$result->father_contact_no:null,
//   "father_email_id"=>isset($result->father_email_id)?$result->father_email_id:null,
//   "mother_contact_no"=>isset($result->mother_contact_no)?$result->mother_contact_no:null,
//   "mother_email_id"=>isset($result->mother_email_id)?$result->mother_email_id:null,
//   "guardian_contact_no"=>isset($result->guardian_contact_no)?$result->guardian_contact_no:null,
//   "guardian_email_id"=>isset($result->guardian_email_id)?$result->guardian_email_id:null,
//   "house_no"=>isset($result->house_no)?$result->house_no:null,
//   "street"=>isset($result->street)?$result->street:null,
//   "city"=>isset($result->city)?$result->city:null,
//   "district"=>isset($result->district)?$result->district:null,
//   "state"=>isset($result->state)?$result->state:null,
//   "pincode"=>isset($result->pincode)?$result->pincode:null,
//   "house_no_1"=>isset($result->house_no_1)?$result->house_no_1:null,
//   "street_1"=>isset($result->street_1)?$result->street_1:null,
//   "city_1"=>isset($result->city_1)?$result->city_1:null,
//   "district_1"=>isset($result->district_1)?$result->district_1:null,
//   "state_1"=>isset($result->state_1)?$result->state_1:null,
//   "pincode_1"=>isset($result->pincode_1)?$result->pincode_1:null,
//   "last_class_std"=>isset($result->last_class_std)?$result->last_class_std:null,
//   "last_school"=>isset($result->last_school)?$result->last_school:null,
//   "admission_for_class"=>isset($result->admission_for_class)?$result->admission_for_class:null,
//   "syllabus"=>isset($result->syllabus)?$result->syllabus:null,
//   "group_no"=>isset($result->group_no)?$result->group_no:null,
//   "second_group_no"=>isset($result->second_group_no)?$result->second_group_no:null,
//   "second_language"=>isset($result->second_language)?$result->second_language:null,
//     "profile_photo" => isset($result->profile_photo)?$result->profile_photo:null,
//       "admission_photo" => isset($result->admission_photo)?$result->admission_photo:null,
//      "birth_certificate_photo" => isset($result->birth_certificate_photo)?$result->birth_certificate_photo:null,
//      "aadhar_card_photo" => isset($result->aadhar_card_photo)?$result->aadhar_card_photo:null,
//      "ration_card_photo" => isset($result->ration_card_photo)?$result->ration_card_photo:null,
//      "community_certificate_photo" => isset($result->community_certificate)?$result->community_certificate:null,
//      "slip_photo" => isset($result->slip_photo)?$result->slip_photo:null,
//      "medical_certificate_photo" => isset($result->medical_certificate_photo)?$result->medical_certificate_photo:null,
//      "reference_letter_photo" => isset($result->reference_letter_photo)?$result->reference_letter_photo:null,
//      "church_certificate_photo" => isset($result->church_certificate_photo)?$result->church_certificate_photo:null,
//      "transfer_certificate_photo" => isset($result->transfer_certificate_photo)?$result->transfer_certificate_photo:null,
     
            
//             );
//             // dd( $HrtPage2TemplateData);
//             $admissionform = AdmissionForm::create($HrtPage2TemplateData);
//       $result = Admission::
//     where('payment_order_id', 'LIKE', $PaymentOrdersStatus->clnt_txn_ref)
//     ->orderBy('payment_order_id', 'asc')
//     ->first();
//   // dd($result->profile_photo);
//             $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
//             $profile_pic =  $result->profile_photo;
//              $profile_pic1 =  $result->admission_photo;
            
//               $profile_pic2 =  $result->birth_certificate_photo;
//               // dd($profile_pic2);
//               $profile_pic3 =  $result->aadhar_card_photo;
//                 $profile_pic4 =  $result->ration_card_photo;
//                  $profile_pic5 =  $result->community_certificate_photo;
//                   $profile_pic6 =  $result->slip_photo;
//                   $profile_pic7 =  $result->medical_certificate_photo;
//                   $profile_pic8 =  $result->reference_letter_photo;
//                     $profile_pic9 =  $result->church_certificate_photo;
//                      $profile_pic10 =  $result->transfer_certificate_photo;
//         //mail ku data $admissionData intha variable la pass pannaum
//       $admissionform1 = $PaymentOrdersStatus->clnt_txn_ref;
// $admissionData = ['admissionData' => 'https://www.santhoshavidhyalaya.com/SVS/admission-view/'.$admissionform1 ];

// Mail::send('emails.admissionMail', $admissionData, function ($message) use ($profile_pic, $profile_pic2, $profile_pic1,$profile_pic3,$profile_pic4,$profile_pic5,$profile_pic6,$profile_pic7,$profile_pic8,$profile_pic9,$profile_pic10)  {
//     $message->to('udhayatech19@gmail.com')
//         ->subject('Admission Form');

//     // Attach profile photo if it exists
//     if ($profile_pic && Storage::exists('profile_photos/'.$profile_pic)) {
//         $message->attach(Storage::path('profile_photos/'.$profile_pic), [
//             'as' => $profile_pic,
//             'mime' => 'image/jpeg',
//         ]);
//     }

//     // Attach birth certificate photo if it exists
//     if ($profile_pic2 && Storage::exists('birth_certificate_photos/'.$profile_pic2)) {
//         $message->attach(Storage::path('birth_certificate_photos/'.$profile_pic2), [
//             'as' => $profile_pic2,
//             'mime' => 'image/jpeg',
//         ]);
//     }

//     // Attach admission photo if it exists
//     if ($profile_pic1 && Storage::exists('admission_photos/'.$profile_pic1)) {
//         $message->attach(Storage::path('admission_photos/'.$profile_pic1), [
//             'as' => $profile_pic1,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic3 && Storage::exists('aadhar_card_photos'.$profile_pic3)) {
//         $message->attach(Storage::path('aadhar_card_photos'.$profile_pic3), [
//             'as' => $profile_pic3,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic4 && Storage::exists('ration_card_photos/'.$profile_pic4)) {
//         $message->attach(Storage::path('ration_card_photos/'.$profile_pic4), [
//             'as' => $profile_pic4,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic5 && Storage::exists('community_certificate_photos/'.$profile_pic5)) {
//         $message->attach(Storage::path('community_certificate_photos/'.$profile_pic5), [
//             'as' => $profile_pic5,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic6 && Storage::exists('slip_photos/'.$profile_pic6)) {
//         $message->attach(Storage::path('slip_photos/'.$profile_pic6), [
//             'as' => $profile_pic6,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic7 && Storage::exists('medical_certificate_photos/'.$profile_pic7)) {
//         $message->attach(Storage::path('medical_certificate_photos/'.$profile_pic7), [
//             'as' => $profile_pic7,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic8 && Storage::exists('reference_letter_photos/'.$profile_pic8)) {
//         $message->attach(Storage::path('reference_letter_photos/'.$profile_pic8), [
//             'as' => $profile_pic8,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic9 && Storage::exists('church_certificate_photos/'.$profile_pic9)) {
//         $message->attach(Storage::path('church_certificate_photos/'.$profile_pic9), [
//             'as' => $profile_pic9,
//             'mime' => 'image/jpeg',
//         ]);
//     }
//      // Attach admission photo if it exists
//     if ($profile_pic10 && Storage::exists('transfer_certificate_photo/'.$profile_pic10)) {
//         $message->attach(Storage::path('transfer_certificate_photo/'.$profile_pic10), [
//             'as' => $profile_pic10,
//             'mime' => 'image/jpeg',
//         ]);
//     }
  
// });
    
//     return response()->json(['message' => 'Data stored successfully1'], 200);
    
    
   
    
// } else {
//     return response()->json(['message' => 'No data found'], 404);
// }
// dd($result);
 
       

// return response()->json(['message' => 'Application Form is not sent'], 404)
//                 ->setEncodingOptions(JSON_UNESCAPED_UNICODE)
//                 ->header('Content-Type', 'application/json');
 // Replace this with the actual ID you want to pass
//dd($result);
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