<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AdmissionForm;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Helpers\LifecycleLogger;

class StudentController extends Controller
{
    public function index()
    {
        dd("eeeeee");
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'student added successfully',

        // ]);
    }
    private function convertExcelDate($excelDate)
    {
        // Check if the input is already a valid date string
        if (
            is_string($excelDate) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $excelDate)
        ) {
            $date = \DateTime::createFromFormat("Y-m-d", $excelDate);
            if ($date !== false) {
                return $date->format("Y-m-d");
            }
        }

        // Check if the value is numeric and greater than zero
        if (is_numeric($excelDate) && $excelDate > 0) {
            // Excel's date starts on 1900-01-01, which corresponds to serial number 1.
            // Subtract 2 to account for Excel's leap year bug (1900 is not a leap year).
            $date = new \DateTime("1899-12-30");
            $date->modify("+{$excelDate} days");
            return $date->format("Y-m-d");
        }

        return null;
    }
    public function store(Request $request)
    {
        $student = new Student();
        $student->name = $request->input("name");
        $student->grade = $request->input("grade");
        $student->email = $request->input("email");
        $student->phone = $request->input("phone");

        $student->save();

        return response()->json([
            "status" => 200,
            "message" => "student added successfully",
        ]);
    }

    public function uploadStudentData(Request $res)
    {
        $datas = $res->data;
        $response = [
            "uploaded" => [],
            "duplicates" => [],
        ];
        foreach ($datas as $record) {
            if (empty($record)) {
                continue; // Skip empty arrays
            }
            // dd($record,(isset($record[1]) && $record[1] !== ""));

            if (isset($record[1]) && $record[1] !== "") {
                $admission_no = $record[1]; // Assuming the admission_no is stored in index 1
                //return response()->json( $admission_no);

                // $existingStudent = Student::where('admission_no', 'like', $admission_no)->first();
                // $existingStudentuser = User::where('admission_no', 'like', $admission_no)->first();

                $existingStudent = Student::where(
                    "roll_no",
                    "like",
                    $record[0]
                )->first();
                $existingStudentuser = User::where(
                    "roll_no",
                    "like",
                    $record[0]
                )->first();
                // dd($existingStudentuser);
                if ($existingStudentuser) {
                    // Move the existing record to history table
                    $studentHistory = new StudentHistory();

                    $studentHistory->original_id = $existingStudent->id;
                    $studentHistory->roll_no = $existingStudent->roll_no;
                    $studentHistory->admission_no =
                        $existingStudent->admission_no;
                    $studentHistory->STUDENT_NAME =
                        $existingStudent->STUDENT_NAME;
                    $studentHistory->date_form = $existingStudent->date_form;
                    $studentHistory->MOTHERTONGUE =
                        $existingStudent->MOTHERTONGUE;
                    $studentHistory->STATE = $existingStudent->STATE;
                    $studentHistory->DOB_DD_MM_YYYY =
                        $existingStudent->DOB_DD_MM_YYYY;
                    $studentHistory->SEX = $existingStudent->SEX;
                    $studentHistory->BLOOD_GROUP =
                        $existingStudent->BLOOD_GROUP;
                    $studentHistory->NATIONALITY =
                        $existingStudent->NATIONALITY;
                    $studentHistory->RELIGION = $existingStudent->RELIGION;
                    $studentHistory->DENOMINATION =
                        $existingStudent->DENOMINATION;
                    $studentHistory->CASTE = $existingStudent->CASTE;
                    $studentHistory->CASTE_CLASSIFICATION =
                        $existingStudent->CASTE_CLASSIFICATION;
                    $studentHistory->AADHAAR_CARD_NO =
                        $existingStudent->AADHAAR_CARD_NO;
                    $studentHistory->RATIONCARDNO =
                        $existingStudent->RATIONCARDNO;
                    $studentHistory->EMIS_NO = $existingStudent->EMIS_NO;
                    $studentHistory->FOOD = $existingStudent->FOOD;
                    $studentHistory->chronic_des =
                        $existingStudent->chronic_des;
                    $studentHistory->medicine_taken =
                        $existingStudent->medicine_taken;
                    $studentHistory->FATHER = $existingStudent->FATHER;
                    $studentHistory->OCCUPATION = $existingStudent->OCCUPATION;
                    $studentHistory->MOTHER = $existingStudent->MOTHER;
                    $studentHistory->mother_occupation =
                        $existingStudent->mother_occupation;
                    $studentHistory->GUARDIAN = $existingStudent->GUARDIAN;
                    $studentHistory->guardian_occupation =
                        $existingStudent->guardian_occupation;
                    $studentHistory->MOBILE_NUMBER =
                        $existingStudent->MOBILE_NUMBER;
                    $studentHistory->EMAIL_ID = $existingStudent->EMAIL_ID;
                    $studentHistory->WHATS_APP_NO =
                        $existingStudent->WHATS_APP_NO;
                    $studentHistory->mother_email_id =
                        $existingStudent->mother_email_id;
                    $studentHistory->guardian_contact_no =
                        $existingStudent->guardian_contact_no;
                    $studentHistory->guardian_email_id =
                        $existingStudent->guardian_email_id;
                    $studentHistory->MONTHLY_INCOME =
                        $existingStudent->MONTHLY_INCOME;
                    $studentHistory->mother_income =
                        $existingStudent->mother_income;
                    $studentHistory->guardian_income =
                        $existingStudent->guardian_income;
                    $studentHistory->PERMANENT_HOUSENUMBER =
                        $existingStudent->PERMANENT_HOUSENUMBER;
                    $studentHistory->P_STREETNAME =
                        $existingStudent->P_STREETNAME;
                    $studentHistory->P_VILLAGE_TOWN_NAME =
                        $existingStudent->P_VILLAGE_TOWN_NAME;
                    $studentHistory->P_DISTRICT = $existingStudent->P_DISTRICT;
                    $studentHistory->P_STATE = $existingStudent->P_STATE;
                    $studentHistory->P_PINCODE = $existingStudent->P_PINCODE;
                    $studentHistory->COMMUNICATION_HOUSE_NO =
                        $existingStudent->COMMUNICATION_HOUSE_NO;
                    $studentHistory->C_STREET_NAME =
                        $existingStudent->C_STREET_NAME;
                    $studentHistory->C_VILLAGE_TOWN_NAME =
                        $existingStudent->C_VILLAGE_TOWN_NAME;
                    $studentHistory->C_DISTRICT = $existingStudent->C_DISTRICT;
                    $studentHistory->C_STATE = $existingStudent->C_STATE;
                    $studentHistory->C_PINCODE = $existingStudent->C_PINCODE;
                    $studentHistory->CLASS_LAST_STUDIED =
                        $existingStudent->CLASS_LAST_STUDIED;
                    $studentHistory->NAME_OF_SCHOOL =
                        $existingStudent->NAME_OF_SCHOOL;
                    $studentHistory->SOUGHT_STD = $existingStudent->SOUGHT_STD;
                    $studentHistory->sec = $existingStudent->sec;
                    $studentHistory->syllabus = $existingStudent->syllabus;
                    $studentHistory->GROUP_12 = $existingStudent->GROUP_12;
                    $studentHistory->second_group_no =
                        $existingStudent->second_group_no;
                    $studentHistory->LANG_PART_I =
                        $existingStudent->LANG_PART_I;
                    $studentHistory->profile_photo =
                        $existingStudent->profile_photo;
                    $studentHistory->birth_certificate_photo =
                        $existingStudent->birth_certificate_photo;
                    $studentHistory->aadhar_card_photo =
                        $existingStudent->aadhar_card_photo;
                    $studentHistory->ration_card_photo =
                        $existingStudent->ration_card_photo;
                    $studentHistory->community_certificate =
                        $existingStudent->community_certificate;
                    $studentHistory->slip_photo = $existingStudent->slip_photo;
                    $studentHistory->medical_certificate_photo =
                        $existingStudent->medical_certificate_photo;
                    $studentHistory->reference_letter_photo =
                        $existingStudent->reference_letter_photo;
                    $studentHistory->church_certificate_photo =
                        $existingStudent->church_certificate_photo;
                    $studentHistory->transfer_certificate_photo =
                        $existingStudent->transfer_certificate_photo;
                    $studentHistory->admission_photo =
                        $existingStudent->admission_photo;
                    $studentHistory->payment_order_id =
                        $existingStudent->payment_order_id;
                    $studentHistory->brother_1 = $existingStudent->brother_1;
                    $studentHistory->brother_2 = $existingStudent->brother_2;
                    $studentHistory->gender_1 = $existingStudent->gender_1;
                    $studentHistory->gender_2 = $existingStudent->gender_2;
                    $studentHistory->class_1 = $existingStudent->class_1;
                    $studentHistory->class_2 = $existingStudent->class_2;
                    $studentHistory->brother_3 = $existingStudent->brother_3;
                    $studentHistory->gender_3 = $existingStudent->gender_3;
                    $studentHistory->class_3 = $existingStudent->class_3;
                    $studentHistory->last_school_state =
                        $existingStudent->last_school_state;
                    $studentHistory->second_language_school =
                        $existingStudent->second_language_school;
                    $studentHistory->reference_name_1 =
                        $existingStudent->reference_name_1;
                    $studentHistory->reference_name_2 =
                        $existingStudent->reference_name_2;
                    $studentHistory->reference_phone_1 =
                        $existingStudent->reference_phone_1;
                    $studentHistory->reference_phone_2 =
                        $existingStudent->reference_phone_2;
                    $studentHistory->ORGANISATION =
                        $existingStudent->ORGANISATION;
                    $studentHistory->mother_organization =
                        $existingStudent->mother_organization;
                    $studentHistory->guardian_organization =
                        $existingStudent->guardian_organization;
                    $studentHistory->created_at = $existingStudent->created_at;
                    $studentHistory->updated_at = $existingStudent->updated_at;
                    $studentHistory->documents = $existingStudent->documents;

                    $studentHistory->save();

                    // update existing student
                    //$existingStudent->admission_no  = $record[0] ?? null;
                    $existingStudent->admission_no = $record[1] ?? null;
                    $existingStudent->roll_no = $record[0] ?? null;
                    $existingStudent->student_name = $record[2] ?? null;
                    $existingStudent->date_form = isset($record[3])
                        ? $this->convertExcelDate($record[3])
                        : null;
                    $existingStudent->MOTHERTONGUE = $record[4] ?? null;
                    $existingStudent->STATE = $record[5] ?? null;
                    $existingStudent->DOB_DD_MM_YYYY = isset($record[6])
                        ? $this->convertExcelDate($record[6])
                        : null;
                    $existingStudent->SEX = $record[7] ?? null;
                    $existingStudent->BLOOD_GROUP = $record[8] ?? null;
                    $existingStudent->NATIONALITY = $record[9] ?? null;
                    $existingStudent->RELIGION = $record[10] ?? null;
                    $existingStudent->DENOMINATION = $record[11] ?? null;
                    $existingStudent->CASTE = $record[12] ?? null;
                    $existingStudent->CASTE_CLASSIFICATION =
                        $record[13] ?? null;
                    $existingStudent->AADHAAR_CARD_NO = $record[14] ?? null;
                    $existingStudent->RATIONCARDNO = $record[15] ?? null;
                    $existingStudent->EMIS_NO = $record[16] ?? null;
                    $existingStudent->FOOD = $record[17] ?? null;
                    $existingStudent->chronic_des = $record[18] ?? null;
                    $existingStudent->medicine_taken = $record[19] ?? null;
                    $existingStudent->FATHER = $record[20] ?? null;
                    $existingStudent->OCCUPATION = $record[21] ?? null;
                    $existingStudent->MOTHER = $record[22] ?? null;
                    $existingStudent->mother_occupation = $record[23] ?? null;
                    $existingStudent->GUARDIAN = $record[24] ?? null;
                    $existingStudent->guardian_occupation = $record[25] ?? null;
                    $existingStudent->MOBILE_NUMBER = $record[26] ?? null;
                    $existingStudent->EMAIL_ID = $record[27] ?? null;
                    $existingStudent->WHATS_APP_NO = $record[28] ?? null;
                    $existingStudent->mother_email_id = $record[29] ?? null;
                    $existingStudent->guardian_contact_no = $record[30] ?? null;
                    $existingStudent->guardian_email_id = $record[31] ?? null;
                    $existingStudent->MONTHLY_INCOME = $record[32] ?? null;
                    $existingStudent->mother_income = $record[33] ?? null;
                    $existingStudent->guardian_income = $record[34] ?? null;
                    $existingStudent->PERMANENT_HOUSENUMBER =
                        $record[35] ?? null;
                    $existingStudent->P_STREETNAME = $record[36] ?? null;
                    $existingStudent->P_VILLAGE_TOWN_NAME = $record[37] ?? null;
                    $existingStudent->P_DISTRICT = $record[38] ?? null;
                    $existingStudent->P_STATE = $record[39] ?? null;
                    $existingStudent->P_PINCODE = $record[40] ?? null;
                    $existingStudent->COMMUNICATION_HOUSE_NO =
                        $record[41] ?? null;
                    $existingStudent->C_STREET_NAME = $record[42] ?? null;
                    $existingStudent->C_VILLAGE_TOWN_NAME = $record[43] ?? null;
                    $existingStudent->C_DISTRICT = $record[44] ?? null;
                    $existingStudent->C_STATE = $record[45] ?? null;
                    $existingStudent->C_PINCODE = $record[46] ?? null;
                    $existingStudent->CLASS_LAST_STUDIED = $record[47] ?? null;
                    $existingStudent->NAME_OF_SCHOOL = $record[48] ?? null;
                    $existingStudent->SOUGHT_STD = $record[49] ?? null;
                    $existingStudent->sec = $record[50] ?? null;
                    $existingStudent->syllabus = $record[51] ?? null;
                    $existingStudent->GROUP_12 = $record[52] ?? null;
                    $existingStudent->second_group_no = $record[53] ?? null;
                    $existingStudent->LANG_PART_I = $record[54] ?? null;
                    $existingStudent->profile_photo = $record[55] ?? null;
                    $existingStudent->birth_certificate_photo =
                        $record[56] ?? null;
                    $existingStudent->aadhar_card_photo = $record[57] ?? null;
                    $existingStudent->ration_card_photo = $record[58] ?? null;
                    $existingStudent->community_certificate =
                        $record[59] ?? null;
                    $existingStudent->slip_photo = $record[60] ?? null;
                    $existingStudent->medical_certificate_photo =
                        $record[61] ?? null;
                    $existingStudent->reference_letter_photo =
                        $record[62] ?? null;
                    $existingStudent->church_certificate_photo =
                        $record[63] ?? null;
                    $existingStudent->transfer_certificate_photo =
                        $record[64] ?? null;
                    $existingStudent->admission_photo = $record[65] ?? null;
                    $existingStudent->payment_order_id = $record[66] ?? null;
                    $existingStudent->brother_1 = $record[67] ?? null;
                    $existingStudent->brother_2 = $record[68] ?? null;
                    $existingStudent->gender_1 = $record[69] ?? null;
                    $existingStudent->gender_2 = $record[70] ?? null;
                    $existingStudent->class_1 = $record[71] ?? null;
                    $existingStudent->class_2 = $record[72] ?? null;
                    $existingStudent->brother_3 = $record[73] ?? null;
                    $existingStudent->gender_3 = $record[74] ?? null;
                    $existingStudent->class_3 = $record[75] ?? null;
                    $existingStudent->last_school_state = $record[76] ?? null;
                    $existingStudent->second_language_school =
                        $record[77] ?? null;
                    $existingStudent->reference_name_1 = $record[78] ?? null;
                    $existingStudent->reference_name_2 = $record[79] ?? null;
                    $existingStudent->reference_phone_1 = $record[80] ?? null;
                    $existingStudent->reference_phone_2 = $record[81] ?? null;
                    $existingStudent->ORGANISATION = $record[82] ?? null;
                    $existingStudent->mother_organization = $record[83] ?? null;
                    $existingStudent->guardian_organization =
                        $record[84] ?? null;
                    $existingStudent->created_at = $record[85] ?? null;
                    $existingStudent->updated_at = $record[86] ?? null;
                    $existingStudent->documents = $record[87] ?? null;

                    $existingStudent->save();

                    $existingStudentuser->name = $record[2] ?? null;
                    $existingStudentuser->gender = $record[7] ?? null;
                    $existingStudentuser->email = $record[27] ?? null;
                    $existingStudentuser->standard = $record[49] ?? null;
                    $existingStudentuser->sec = $record[50] ?? null;
                    $existingStudentuser->twe_group = $record[52] ?? null;
                    $existingStudentuser->hostelOrDay = "hostel";
                    $existingStudentuser->save();

                    LifecycleLogger::log(
                        "Student Record Updated",
                        $existingStudentuser->id,
                        "student_record_update",
                        [
                            "student_name" => $existingStudentuser->name,
                            "roll_no" => $existingStudentuser->roll_no,
                            "standard" => $existingStudentuser->standard,
                            "section" => $existingStudentuser->sec,
                        ]
                    );

                    $response["duplicates"][] = [
                        "email" => $record[27],
                        "admission_no" => $record[1],
                        "message" =>
                            "Data modified successfully as it is already exists.",
                    ];
                } else {
                    $recordEmail = $record[27];
                    $recordAdmissionNo = $record[1];

                    // $existingUser = User::where('email', $record[40])->first();
                    // if ($existingUser) {
                    //     // User already exists, send a response or perform any necessary action
                    //     $response['duplicates'][] = [
                    //         'email' => $recordEmail,
                    //         'admission_no' => $recordAdmissionNo,
                    //         'message' => 'User already exists. Please change the email or the admission number.'
                    //     ];
                    //     // continue; // Skip processing the duplicate record

                    // }
                    try {
                        $student = new Student();

                        $student->admission_no = $record[1] ?? null;
                        $student->roll_no = $record[0] ?? null;
                        $student->student_name = $record[2] ?? null;
                        $student->date_form = isset($record[3])
                            ? $this->convertExcelDate($record[3])
                            : null;
                        $student->MOTHERTONGUE = $record[4] ?? null;
                        $student->STATE = $record[5] ?? null;
                        $student->DOB_DD_MM_YYYY = isset($record[6])
                            ? $this->convertExcelDate($record[6])
                            : null;
                        $student->SEX = $record[7] ?? null;
                        $student->BLOOD_GROUP = $record[8] ?? null;
                        $student->NATIONALITY = $record[9] ?? null;
                        $student->RELIGION = $record[10] ?? null;
                        $student->DENOMINATION = $record[11] ?? null;
                        $student->CASTE = $record[12] ?? null;
                        $student->CASTE_CLASSIFICATION = $record[13] ?? null;
                        $student->AADHAAR_CARD_NO = $record[14] ?? null;
                        $student->RATIONCARDNO = $record[15] ?? null;
                        $student->EMIS_NO = $record[16] ?? null;
                        $student->FOOD = $record[17] ?? null;
                        $student->chronic_des = $record[18] ?? null;
                        $student->medicine_taken = $record[19] ?? null;
                        $student->FATHER = $record[20] ?? null;
                        $student->OCCUPATION = $record[21] ?? null;
                        $student->MOTHER = $record[22] ?? null;
                        $student->mother_occupation = $record[23] ?? null;
                        $student->GUARDIAN = $record[24] ?? null;
                        $student->guardian_occupation = $record[25] ?? null;
                        $student->MOBILE_NUMBER = $record[26] ?? null;
                        $student->EMAIL_ID = $record[27] ?? null;
                        $student->WHATS_APP_NO = $record[28] ?? null;
                        $student->mother_email_id = $record[29] ?? null;
                        $student->guardian_contact_no = $record[30] ?? null;
                        $student->guardian_email_id = $record[31] ?? null;
                        $student->MONTHLY_INCOME = $record[32] ?? null;
                        $student->mother_income = $record[33] ?? null;
                        $student->guardian_income = $record[34] ?? null;
                        $student->PERMANENT_HOUSENUMBER = $record[35] ?? null;
                        $student->P_STREETNAME = $record[36] ?? null;
                        $student->P_VILLAGE_TOWN_NAME = $record[37] ?? null;
                        $student->P_DISTRICT = $record[38] ?? null;
                        $student->P_STATE = $record[39] ?? null;
                        $student->P_PINCODE = $record[40] ?? null;
                        $student->COMMUNICATION_HOUSE_NO = $record[41] ?? null;
                        $student->C_STREET_NAME = $record[42] ?? null;
                        $student->C_VILLAGE_TOWN_NAME = $record[43] ?? null;
                        $student->C_DISTRICT = $record[44] ?? null;
                        $student->C_STATE = $record[45] ?? null;
                        $student->C_PINCODE = $record[46] ?? null;
                        $student->CLASS_LAST_STUDIED = $record[47] ?? null;
                        $student->NAME_OF_SCHOOL = $record[48] ?? null;
                        $student->SOUGHT_STD = $record[49] ?? null;
                        $student->sec = $record[50] ?? null;
                        $student->syllabus = $record[51] ?? null;
                        $student->GROUP_12 = $record[52] ?? null;
                        $student->second_group_no = $record[53] ?? null;
                        $student->LANG_PART_I = $record[54] ?? null;
                        $student->profile_photo = $record[55] ?? null;
                        $student->birth_certificate_photo = $record[56] ?? null;
                        $student->aadhar_card_photo = $record[57] ?? null;
                        $student->ration_card_photo = $record[58] ?? null;
                        $student->community_certificate = $record[59] ?? null;
                        $student->slip_photo = $record[60] ?? null;
                        $student->medical_certificate_photo =
                            $record[61] ?? null;
                        $student->reference_letter_photo = $record[62] ?? null;
                        $student->church_certificate_photo =
                            $record[63] ?? null;
                        $student->transfer_certificate_photo =
                            $record[64] ?? null;
                        $student->admission_photo = $record[65] ?? null;
                        $student->payment_order_id = $record[66] ?? null;
                        $student->brother_1 = $record[67] ?? null;
                        $student->brother_2 = $record[68] ?? null;
                        $student->gender_1 = $record[69] ?? null;
                        $student->gender_2 = $record[70] ?? null;
                        $student->class_1 = $record[71] ?? null;
                        $student->class_2 = $record[72] ?? null;
                        $student->brother_3 = $record[73] ?? null;
                        $student->gender_3 = $record[74] ?? null;
                        $student->class_3 = $record[75] ?? null;
                        $student->last_school_state = $record[76] ?? null;
                        $student->second_language_school = $record[77] ?? null;
                        $student->reference_name_1 = $record[78] ?? null;
                        $student->reference_name_2 = $record[79] ?? null;
                        $student->reference_phone_1 = $record[80] ?? null;
                        $student->reference_phone_2 = $record[81] ?? null;
                        $student->ORGANISATION = $record[82] ?? null;
                        $student->mother_organization = $record[83] ?? null;
                        $student->guardian_organization = $record[84] ?? null;
                        $student->created_at = $record[85] ?? null;
                        $student->updated_at = $record[86] ?? null;
                        $student->documents = $record[87] ?? null;
                        $student->save();
                        $user = new User();
                        $lastid = User::latest("id")->value("id");
                        $lastid = $lastid + 1;
                        $user->id = $lastid;
                        $user->name = $record[2] ?? null;
                        $user->gender = $record[7] ?? null;
                        $user->email = $record[27] ?? null;
                        $user->standard = $record[49] ?? null;
                        $user->sec = $record[50] ?? null;
                        $user->twe_group = $record[52] ?? null;
                        $user->hostelOrDay = "hostel";
                        $user->password = Hash::make("svs@123");
                        $user->admission_no = $record[1] ?? null;
                        $user->roll_no = $record[0] ?? null;
                        $user->save();
                        $response["uploaded"][] = [
                            "email" => $recordEmail,
                            "admission_no" => $recordAdmissionNo,
                            "message" => "Data uploaded successfully.",
                        ];
                    } catch (\Exception $e) {
                        // Handle any exception that occurred during the saving process
                        Log::error(
                            "Error occurred while uploading data: " .
                                $e->getMessage()
                        );
                        $response["uploaded"][] = [
                            "email" => $recordEmail,
                            "admission_no" => $recordAdmissionNo,
                            "message" => "Error occurred while uploading data.",
                        ];
                    }
                    //   return response()->json(['msg' => $e->getMessage()], 500)->header("Access-Control-Allow-Origin",  "*");
                }
            } elseif (!$record[1] && $record[2] && $record[49]) {
                // $student->student_name  =    $record[2] ?? null;
                // $student->Father  =    $record[16] ?? null;
                // $student->Mobilenumber  =    $record[38] ?? null;
                // $student->sought_Std  =   $record[44] ?? null;
                // $student->sec  =    $record[45] ?? null;
                // Find the last admission number starting with "SV" and remove the date part
                // $lastAdmissionNo = User::where('admission_no', 'like', '%SV%')
                //  ->orderBy('admission_no', 'desc')
                // ->first();
                //$lastAdmissionNo = User::where('admission_no', 'like', '%SV%')
                //    ->whereRaw('LENGTH(admission_no) = 12')
                //   ->orderBy('admission_no', 'desc')
                //   ->first();
                $lastAdmissionNo = User::where("admission_no", "like", "%SV%")
                    ->whereRaw("LENGTH(admission_no) = 12")
                    ->orderByRaw(
                        "STR_TO_DATE(SUBSTRING(admission_no, 3, 6), '%d%m%y') DESC"
                    )
                    ->orderByRaw(
                        "CAST(SUBSTRING(admission_no, 9, 4) AS UNSIGNED) DESC"
                    )
                    ->first();

                // Define the format for the new admission number
                $format = "SV" . date("dmy");

                if ($lastAdmissionNo) {
                    // Extract the numeric part of the last admission number
                    $lastNumber = intval(
                        substr($lastAdmissionNo->admission_no, 8)
                    );

                    // Check if the last number is 9999, reset to 0001 if it is
                    if ($lastNumber === 9999) {
                        $newNumber = 1;
                    } else {
                        // Increment the last number by 1
                        $newNumber = $lastNumber + 1;
                    }
                } else {
                    // If no previous admission numbers found, start with 0001
                    $newNumber = 1;
                }

                // Pad the new number with leading zeros to make it 4 digits
                $newNumberPadded = str_pad($newNumber, 4, "0", STR_PAD_LEFT);

                // Combine the format and new number to create the new admission number
                $newAdmissionNo = $format . $newNumberPadded;

                // Assuming you have a function to generate a unique serial number
                // $serialNo = generateSerialNumber(); // Replace this with your serial number generation logic

                $admissionId = $newAdmissionNo;
                // $student->student_name  =    $record[2] ?? null;
                // $student->Father  =    $record[16] ?? null;
                // $student->Mobilenumber  =    $record[38] ?? null;
                // $student->sought_Std  =   $record[44] ?? null;
                // $student->sec  =    $record[45] ?? null;
                //  if ($admissionId && $record[2] && $record[20] && $record[38] && $record[26]) {
                if ($admissionId && $record[2]) {
                    $existingUser = User::where("name", $record[2])
                        //->where('Father', $record[20])
                        //  ->where('Mobilenumber', $record[26])
                        ->where("standard", $record[49])
                        //    ->where('sec', $record[50])  ///////////
                        ->first();

                    if ($existingUser) {
                        continue; // Skip empty arrays
                    } else {
                        $recordEmail = $record[27] ?? "";
                        $recordAdmissionNo = $admissionId;

                        // $existingUser = User::where('email', $record[40])->first();
                        // if ($existingUser) {
                        //     // User already exists, send a response or perform any necessary action
                        //     $response['duplicates'][] = [
                        //         'email' => $recordEmail,
                        //         'admission_no' => $recordAdmissionNo,
                        //         'message' => 'User already exists. Please change the email or the admission number.'
                        //     ];
                        //     // continue; // Skip processing the duplicate record

                        // }
                        try {
                            $student = new Student();
                            $student->admission_no = $admissionId ?? null;
                            $student->roll_no = $record[0] ?? null;
                            $student->student_name = $record[2] ?? null;
                            $student->date_form = isset($record[3])
                                ? $this->convertExcelDate($record[3])
                                : null;
                            $student->MOTHERTONGUE = $record[4] ?? null;
                            $student->STATE = $record[5] ?? null;
                            $student->DOB_DD_MM_YYYY = isset($record[6])
                                ? $this->convertExcelDate($record[6])
                                : null;
                            $student->SEX = $record[7] ?? null;
                            $student->BLOOD_GROUP = $record[8] ?? null;
                            $student->NATIONALITY = $record[9] ?? null;
                            $student->RELIGION = $record[10] ?? null;
                            $student->DENOMINATION = $record[11] ?? null;
                            $student->CASTE = $record[12] ?? null;
                            $student->CASTE_CLASSIFICATION =
                                $record[13] ?? null;
                            $student->AADHAAR_CARD_NO = $record[14] ?? null;
                            $student->RATIONCARDNO = $record[15] ?? null;
                            $student->EMIS_NO = $record[16] ?? null;
                            $student->FOOD = $record[17] ?? null;
                            $student->chronic_des = $record[18] ?? null;
                            $student->medicine_taken = $record[19] ?? null;
                            $student->FATHER = $record[20] ?? null;
                            $student->OCCUPATION = $record[21] ?? null;
                            $student->MOTHER = $record[22] ?? null;
                            $student->mother_occupation = $record[23] ?? null;
                            $student->GUARDIAN = $record[24] ?? null;
                            $student->guardian_occupation = $record[25] ?? null;
                            $student->MOBILE_NUMBER = $record[26] ?? null;
                            $student->EMAIL_ID = $record[27] ?? null;
                            $student->WHATS_APP_NO = $record[28] ?? null;
                            $student->mother_email_id = $record[29] ?? null;
                            $student->guardian_contact_no = $record[30] ?? null;
                            $student->guardian_email_id = $record[31] ?? null;
                            $student->MONTHLY_INCOME = $record[32] ?? null;
                            $student->mother_income = $record[33] ?? null;
                            $student->guardian_income = $record[34] ?? null;
                            $student->PERMANENT_HOUSENUMBER =
                                $record[35] ?? null;
                            $student->P_STREETNAME = $record[36] ?? null;
                            $student->P_VILLAGE_TOWN_NAME = $record[37] ?? null;
                            $student->P_DISTRICT = $record[38] ?? null;
                            $student->P_STATE = $record[39] ?? null;
                            $student->P_PINCODE = $record[40] ?? null;
                            $student->COMMUNICATION_HOUSE_NO =
                                $record[41] ?? null;
                            $student->C_STREET_NAME = $record[42] ?? null;
                            $student->C_VILLAGE_TOWN_NAME = $record[43] ?? null;
                            $student->C_DISTRICT = $record[44] ?? null;
                            $student->C_STATE = $record[45] ?? null;
                            $student->C_PINCODE = $record[46] ?? null;
                            $student->CLASS_LAST_STUDIED = $record[47] ?? null;
                            $student->NAME_OF_SCHOOL = $record[48] ?? null;
                            $student->SOUGHT_STD = $record[49] ?? null;
                            $student->sec = $record[50] ?? null;
                            $student->syllabus = $record[51] ?? null;
                            $student->GROUP_12 = $record[52] ?? null;
                            $student->second_group_no = $record[53] ?? null;
                            $student->LANG_PART_I = $record[54] ?? null;
                            $student->profile_photo = $record[55] ?? null;
                            $student->birth_certificate_photo =
                                $record[56] ?? null;
                            $student->aadhar_card_photo = $record[57] ?? null;
                            $student->ration_card_photo = $record[58] ?? null;
                            $student->community_certificate =
                                $record[59] ?? null;
                            $student->slip_photo = $record[60] ?? null;
                            $student->medical_certificate_photo =
                                $record[61] ?? null;
                            $student->reference_letter_photo =
                                $record[62] ?? null;
                            $student->church_certificate_photo =
                                $record[63] ?? null;
                            $student->transfer_certificate_photo =
                                $record[64] ?? null;
                            $student->admission_photo = $record[65] ?? null;
                            $student->payment_order_id = $record[66] ?? null;
                            $student->brother_1 = $record[67] ?? null;
                            $student->brother_2 = $record[68] ?? null;
                            $student->gender_1 = $record[69] ?? null;
                            $student->gender_2 = $record[70] ?? null;
                            $student->class_1 = $record[71] ?? null;
                            $student->class_2 = $record[72] ?? null;
                            $student->brother_3 = $record[73] ?? null;
                            $student->gender_3 = $record[74] ?? null;
                            $student->class_3 = $record[75] ?? null;
                            $student->last_school_state = $record[76] ?? null;
                            $student->second_language_school =
                                $record[77] ?? null;
                            $student->reference_name_1 = $record[78] ?? null;
                            $student->reference_name_2 = $record[79] ?? null;
                            $student->reference_phone_1 = $record[80] ?? null;
                            $student->reference_phone_2 = $record[81] ?? null;
                            $student->ORGANISATION = $record[82] ?? null;
                            $student->mother_organization = $record[83] ?? null;
                            $student->guardian_organization =
                                $record[84] ?? null;
                            $student->created_at = $record[85] ?? null;
                            $student->updated_at = $record[86] ?? null;
                            $student->documents = $record[87] ?? null;
                            $student->save();
                            $user = new User();
                            $lastid = User::latest("id")->value("id");
                            $lastid = $lastid + 1;
                            $user->id = $lastid;

                            $user->name = $record[2] ?? null;
                            $user->gender = $record[7] ?? null;
                            $user->email = $record[27] ?? null;
                            $user->standard = $record[49] ?? null;
                            $user->sec = $record[50] ?? null;
                            $user->twe_group = $record[52] ?? null;
                            $user->hostelOrDay = "hostel";
                            $user->password = Hash::make("svs@123");
                            $user->admission_no = $admissionId ?? null;
                            $user->roll_no = $record[0] ?? null;

                            $user->save();
                            $response["uploaded"][] = [
                                "email" => $recordEmail,
                                "admission_no" => $recordAdmissionNo,
                                "message" => "Data uploaded successfully.",
                            ];
                        } catch (\Exception $e) {
                            // Handle any exception that occurred during the saving process
                            Log::error(
                                "Error occurred while uploading data: " .
                                    $e->getMessage()
                            );
                            $response["uploaded"][] = [
                                "email" => $recordEmail,
                                "admission_no" => $recordAdmissionNo,
                                "message" =>
                                    "Error occurred while uploading data.",
                            ];
                        }
                    }
                }
            }
        }

        // DB::commit();
        return response()
            ->json($response, 200)
            ->header("Access-Control-Allow-Origin", "*");

        //  return response()->json(['msg' => "uploaded successfully"], 200)->header("Access-Control-Allow-Origin",  "*");
    }

    public function getadmissionStandards($standard)
    {
        $standards = Student::where("SOUGHT_STD", $standard)
            //->distinct() // Ensure no duplicates
            ->orderBy("id") // Sort the options
            ->get();
        if ($standards->isEmpty()) {
            return response()->json(["message" => "No standards found."], 404);
        }
        return response()->json(["standards" => $standards], 200);
    }

    public function readnotinuse(Request $request)
    {
        // Fetch all students and admission forms
        $students = Student::all();
        //  $admissionForms = AdmissionForm::all();

        // Loop through each student to set initial values
        foreach ($students as $student) {
            $student->standard = $student->SOUGHT_STD;
            $student->sec = $student->sec;
            $student->admission_id = $student->admission_id;
            $student->profile_id = $student->id;
            $student->through = "ADMITTED STUDENTS .............."; // Initialize `through` as null

            // Optionally save or update the student record here
            // $student->save();  // Uncomment if you need to save updates for each student
        }

        // Collect admission data to send in response
        $admissionsData = [];
        // foreach ($admissionForms as $admission) {
        //     $admissionsData[] = [
        //              'profile_id' => null,
        //          'STUDENT_NAME'=> $admission->name,
        //          'admission_id' => $admission->id,
        //         'admission_no' => $admission->admission_no,
        //         'roll_no' => $admission->roll_no,
        //         'date_form' => $admission->date_form,
        //         'MOTHERTONGUE' => $admission->language,
        //         'STATE' => $admission->state_student,
        //         'DOB_DD_MM_YYYY' => $admission->date_of_birth,
        //         'SEX' => $admission->gender,
        //         'BLOOD_GROUP' => $admission->blood_group,
        //         'NATIONALITY' => $admission->nationality,
        //         'RELIGION' => $admission->religion,
        //         'DENOMINATION' => $admission->church_denomination,
        //         'CASTE' => $admission->caste,
        //         'CASTE_CLASSIFICATION' => $admission->caste_type,
        //         'AADHAAR_CARD_NO' => $admission->aadhar_card_no,
        //         'RATIONCARDNO' => $admission->ration_card_no,
        //         'EMIS_NO' => $admission->emis_no,
        //         'FOOD' => $admission->veg_or_non,
        //         'chronic_des' => $admission->chronic_des,
        //         'medicine_taken' => $admission->medicine_taken,
        //         'FATHER' => $admission->father_name,
        //         'OCCUPATION' => $admission->father_occupation,
        //         'MOTHER' => $admission->mother_name,
        //         'mother_occupation' => $admission->mother_occupation,
        //         'GUARDIAN' => $admission->guardian_name,
        //         'guardian_occupation' => $admission->guardian_occupation,
        //         'MOBILE_NUMBER' => $admission->father_contact_no,
        //         'EMAIL_ID' => $admission->father_email_id,
        //         'WHATS_APP_NO' => $admission->mother_contact_no,
        //         'mother_email_id' => $admission->mother_email_id,
        //         'guardian_contact_no' => $admission->guardian_contact_no,
        //         'guardian_email_id' => $admission->guardian_email_id,
        //         'MONTHLY_INCOME' => $admission->father_income,
        //         'mother_income' => $admission->mother_income,
        //         'guardian_income' => $admission->guardian_income,
        //         'PERMANENT_HOUSENUMBER' => $admission->house_no,
        //         'P_STREETNAME' => $admission->street,
        //         'P_VILLAGE_TOWN_NAME' => $admission->city,
        //         'P_DISTRICT' => $admission->district,
        //         'P_STATE' => $admission->state,
        //         'P_PINCODE' => $admission->pincode,
        //         'COMMUNICATION_HOUSE_NO' => $admission->house_no_1,
        //         'C_STREET_NAME' => $admission->street_1,
        //         'C_VILLAGE_TOWN_NAME' => $admission->city_1,
        //         'C_DISTRICT' => $admission->district_1,
        //         'C_STATE' => $admission->state_1,
        //         'C_PINCODE' => $admission->pincode_1,
        //         'CLASS_LAST_STUDIED' => $admission->last_class_std,
        //         'NAME_OF_SCHOOL' => $admission->last_school,
        //         'SOUGHT_STD' => $admission->admission_for_class,
        //         'syllabus' => $admission->syllabus,
        //         'GROUP_12' => $admission->group_no,
        //         'second_group_no' => $admission->second_group_no,
        //         'LANG_PART_I' => $admission->second_language,
        //         'profile_photo' => $admission->profile_photo,
        //         'birth_certificate_photo' => $admission->birth_certificate_photo,
        //         'aadhar_card_photo' => $admission->aadhar_card_photo,
        //         'ration_card_photo' => $admission->ration_card_photo,
        //         'community_certificate' => $admission->community_certificate,
        //         'slip_photo' => $admission->slip_photo,
        //         'medical_certificate_photo' => $admission->medical_certificate_photo,
        //         'reference_letter_photo' => $admission->reference_letter_photo,
        //         'church_certificate_photo' => $admission->church_certificate_photo,
        //         'transfer_certificate_photo' => $admission->transfer_certificate_photo,
        //         'admission_photo' => $admission->admission_photo,
        //         'brother_1' => $admission->brother_1,
        //         'brother_2' => $admission->brother_2,
        //         'gender_1' => $admission->gender_1,
        //         'gender_2' => $admission->gender_2,
        //         'class_1' => $admission->class_1,
        //         'class_2' => $admission->class_2,
        //         'brother_3' => $admission->brother_3,
        //         'gender_3' => $admission->gender_3,
        //         'class_3' => $admission->class_3,
        //         'last_school_state' => $admission->last_school_state,
        //         'second_language_school' => $admission->second_language_school,
        //         'reference_name_1' => $admission->reference_name_1,
        //         'reference_name_2' => $admission->reference_name_2,
        //         'reference_phone_1' => $admission->reference_phone_1,
        //         'reference_phone_2' => $admission->reference_phone_2,
        //         'ORGANISATION' => $admission->father_organization,
        //         'mother_organization' => $admission->mother_organization,
        //         'guardian_organization' => $admission->guardian_organization,
        //         'status' => $admission->status,

        //             'profile_picture' => asset('storage/app/profile_photos/' . $admission->profile_photo),
        // 'birth_certificate' => asset('storage/app/birth_certificate_photos/' . $admission->birth_certificate_photo),
        // 'aadhar_copy' => asset('storage/app/aadhar_card_photos/' . $admission->aadhar_card_photo),
        // 'ration_card' => asset('storage/app/ration_card_photos/' . $admission->ration_card_photo),
        // 'community_certificate' => asset('storage/app/community_certificate_photos/' . $admission->community_certificate),
        // 'salary_certificate' => asset('storage/app/slip_photos/' . $admission->slip_photo),
        // 'medical_certificate' => asset('storage/app/medical_certificate_photos/' . $admission->medical_certificate_photo),
        // 'reference_letter' => asset('storage/app/reference_letter_photos/' . $admission->reference_letter_photo),
        // 'church_certificate' => asset('storage/app/church_certificate_photos/' . $admission->church_certificate_photo),
        // 'transfer_certificate' => asset('storage/app/transfer_certificate_photos/' . $admission->transfer_certificate_photo),
        //     'migration_certificate' => asset('storage/app/admission_photos/' . $admission->admission_photo),

        //     ];
        // }

        // Return admission data as response
        return response()->json([
            "students" => $students,
            //  'admissions' => $admissionsData
        ]);
    }
    public function readnotinuseaftersearch(Request $request)
    {
        // Get pagination parameters from request
        $limit = $request->input("limit", null); // Default to null if not provided
        $start = $request->input("start", 0); // Default start to 0 if not provided

        // If no limit is provided, fetch all students
        if ($limit === null) {
            $students = Student::all(); // Get all records if no limit is set
        } else {
            // Otherwise, fetch students with pagination
            $students = Student::skip($start)
                ->take($limit)
                ->get();
        }

        // Loop through each student to set initial values
        foreach ($students as $student) {
            $student->standard = $student->SOUGHT_STD;
            $student->sec = $student->sec;
            $student->admission_id = $student->admission_id;
            $student->profile_id = $student->id;
            $student->through = "ADMITTED STUDENTS .............."; // Initialize `through` as null
        }

        // Get the total count of students for pagination
        $totalStudents = Student::count();

        // Collect admission data to send in response
        $admissionsData = [];

        // Return admission data as response with pagination info
        return response()->json([
            "students" => $students,
            "total" => $totalStudents, // Include total count
        ]);
    }

    public function read(Request $request)
    {
        $fields = [
            "id",
            "roll_no",
            "admission_no",
            "STUDENT_NAME",
            "date_form",
            "MOTHERTONGUE",
            "STATE",
            "DOB_DD_MM_YYYY",
            "SEX",
            "BLOOD_GROUP",
            "NATIONALITY",
            "RELIGION",
            "DENOMINATION",
            "CASTE",
            "CASTE_CLASSIFICATION",
            "AADHAAR_CARD_NO",
            "RATIONCARDNO",
            "EMIS_NO",
            "FOOD",
            "chronic_des",
            "medicine_taken",
            "FATHER",
            "OCCUPATION",
            "MOTHER",
            "mother_occupation",
            "GUARDIAN",
            "guardian_occupation",
            "MOBILE_NUMBER",
            "EMAIL_ID",
            "WHATS_APP_NO",
            "mother_email_id",
            "guardian_contact_no",
            "guardian_email_id",
            "MONTHLY_INCOME",
            "mother_income",
            "guardian_income",
            "PERMANENT_HOUSENUMBER",
            "P_STREETNAME",
            "P_VILLAGE_TOWN_NAME",
            "P_DISTRICT",
            "P_STATE",
            "P_PINCODE",
            "COMMUNICATION_HOUSE_NO",
            "C_STREET_NAME",
            "C_VILLAGE_TOWN_NAME",
            "C_DISTRICT",
            "C_STATE",
            "C_PINCODE",
            "CLASS_LAST_STUDIED",
            "NAME_OF_SCHOOL",
            "SOUGHT_STD",
            "sec",
            "syllabus",
            "GROUP_12",
            "group_no",
            "second_group_no",
            "LANG_PART_I",
            "profile_photo",
            "birth_certificate_photo",
            "aadhar_card_photo",
            "ration_card_photo",
            "community_certificate",
            "slip_photo",
            "medical_certificate_photo",
            "reference_letter_photo",
            "church_certificate_photo",
            "transfer_certificate_photo",
            "admission_photo",
            "payment_order_id",
            "siblings",
            "brother_1",
            "brother_2",
            "gender_1",
            "gender_2",
            "class_1",
            "class_2",
            "brother_3",
            "gender_3",
            "class_3",
            "last_school_state",
            "second_language_school",
            "second_language",
            "reference_name_1",
            "reference_name_2",
            "reference_phone_1",
            "reference_phone_2",
            "ORGANISATION",
            "mother_organization",
            "guardian_organization",
            "academic_year",
            "grade_status",
            "created_at",
            "updated_at",
            "documents",
            "admission_id",
            "father_title",
            "mother_title",
            "guardian_title",
            "status",
            "upload_created_at",
            "upload_updated_at",
        ];

        $query = Student::select($fields);

        // Common Search
        if ($search = $request->input("search")) {
            $query->where(function ($q) use ($search, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, "LIKE", "%$search%");
                }
            });
        }

        // Field-Specific Filters
        foreach (
            $request->except(["limit", "start", "search"])
            as $field => $value
        ) {
            if (!empty($value) && in_array($field, $fields)) {
                $query->where($field, "LIKE", "%$value%");
            }
        }

        // Pagination
        $total = $query->count();
        $students = $request->has("limit")
            ? $query
                ->skip($request->input("start", 0))
                ->take($request->input("limit"))
                ->get()
            : $query->get();

        // $studentIds = $students->pluck('id')->toArray();
        $rollNos = $students->pluck("roll_no")->toArray();
        // Convert to array
        $studentsArray = $students->toArray();
        $existingInvoices = DB::table("generate_invoice_views")
            // ->whereIn('student_id', $studentIds)
            ->whereIn("roll_no", $rollNos)
            ->select("roll_no")
            ->get()
            ->groupBy(fn($row) => $row->roll_no);

        foreach ($students as $student) {
            $student->standard = $student->SOUGHT_STD;
            $student->sec = $student->sec;
            $student->admission_id = $student->admission_id;
            $student->profile_id = $student->id;
            $key = $student->roll_no;
            $student->new = isset($existingInvoices[$key]) ? false : true;

            $student->through = "ADMITTED STUDENTS .............."; // Initialize `through` as null
        }

        // Generate Excel file and store it in the 'public' disk
        $fileName = "students_" . time() . ".xlsx";
        $filePath = storage_path("app/public/" . $fileName);

        // Create and store the Excel file
        Excel::store(new StudentsExport($studentsArray), "public/" . $fileName);

        // Return the download link in the response
        $downloadUrl = url("storage/app/public/" . $fileName);

        return response()->json([
            "students" => $students,
            "total" => $total,
            "excelLink" => $downloadUrl,
        ]);
    }

    public function readtwo(Request $request)
    {
        $students = Student::all();
        $users = User::all();

        // Loop through each student and then each user to match and assign details
        foreach ($students as $student) {
            $student->standard = null;
            $student->sec = null;

            foreach ($users as $user) {
                if ($student->roll_no == $user->roll_no) {
                    $student->standard = $user->standard;
                    $student->sec = $user->sec;
                    break;
                }
            }
        }

        return response()->json(["data" => $students]);
    }

    public function readByadmissioNo($admission_no)
    {
        $students = Student::where("admission_no", $admission_no)->get();

        if ($students) {
            // $imagePath = public_path('photos/' . $admission_no . '.jpg');
            $imagePath = storage_path(
                "app/public/photos/" . $admission_no . ".jpg"
            );
            foreach ($students as $key => $student) {
                if (file_exists($imagePath)) {
                    $fileContents = file_get_contents($imagePath);
                    $base64Image = base64_encode($fileContents);
                    $students[$key]["File"] = $base64Image;
                }
            }
        }

        return response()->json(["data" => $students, "path" => $imagePath]);
    }

    public function update(Request $request, $id)
    {
        $admission = Student::findOrFail($id);
        //   $admitted_student = Student::findOrFail($admission->admitt);

        // Update non-file fields
        $admission->update([
            "roll_no" => $request->roll_no,
            "admission_no" => $request->admission_no,
            "STUDENT_NAME" => $request->STUDENT_NAME,
            "date_form" => $request->date_form,
            "MOTHERTONGUE" => $request->MOTHERTONGUE,
            "STATE" => $request->STATE,
            "DOB_DD_MM_YYYY" => $request->DOB_DD_MM_YYYY,
            "SEX" => $request->SEX,
            "BLOOD_GROUP" => $request->BLOOD_GROUP,
            "NATIONALITY" => $request->NATIONALITY,
            "RELIGION" => $request->RELIGION,
            "DENOMINATION" => $request->DENOMINATION,
            "CASTE" => $request->CASTE,
            "CASTE_CLASSIFICATION" => $request->CASTE_CLASSIFICATION,
            "AADHAAR_CARD_NO" => $request->AADHAAR_CARD_NO,
            "RATIONCARDNO" => $request->RATIONCARDNO,
            "EMIS_NO" => $request->EMIS_NO,
            "pen_no" => $request->pen_no,
            "academic_year" => $request->academic_year,
            "FOOD" => $request->FOOD,
            "chronic_des" => $request->chronic_des,
            "medicine_taken" => $request->medicine_taken,
            "FATHER" => $request->FATHER,
            "OCCUPATION" => $request->OCCUPATION,
            "MOTHER" => $request->MOTHER,
            "mother_occupation" => $request->mother_occupation,
            "GUARDIAN" => $request->GUARDIAN,
            "guardian_occupation" => $request->guardian_occupation,
            "MOBILE_NUMBER" => $request->MOBILE_NUMBER,
            "EMAIL_ID" => $request->EMAIL_ID,
            "WHATS_APP_NO" => $request->WHATS_APP_NO,
            "mother_email_id" => $request->mother_email_id,
            "guardian_contact_no" => $request->guardian_contact_no,
            "guardian_email_id" => $request->guardian_email_id,
            "MONTHLY_INCOME" => $request->MONTHLY_INCOME,
            "mother_income" => $request->mother_income,
            "guardian_income" => $request->guardian_income,
            "PERMANENT_HOUSENUMBER" => $request->PERMANENT_HOUSENUMBER,
            "P_STREETNAME" => $request->P_STREETNAME,
            "P_VILLAGE_TOWN_NAME" => $request->P_VILLAGE_TOWN_NAME,
            "P_DISTRICT" => $request->P_DISTRICT,
            "P_STATE" => $request->P_STATE,
            "P_PINCODE" => $request->P_PINCODE,
            "COMMUNICATION_HOUSE_NO" => $request->COMMUNICATION_HOUSE_NO,
            "C_STREET_NAME" => $request->C_STREET_NAME,
            "C_VILLAGE_TOWN_NAME" => $request->C_VILLAGE_TOWN_NAME,
            "C_DISTRICT" => $request->C_DISTRICT,
            "C_STATE" => $request->C_STATE,
            "C_PINCODE" => $request->C_PINCODE,
            "CLASS_LAST_STUDIED" => $request->CLASS_LAST_STUDIED,
            "NAME_OF_SCHOOL" => $request->NAME_OF_SCHOOL,
            "SOUGHT_STD" => $request->SOUGHT_STD,
            "sec" => $request->sec,
            "syllabus" => $request->syllabus,
            "GROUP_12" => $request->GROUP_12,
            "group_no" => $request->group_no,
            "father_title" => $request->father_title,
            "mother_title" => $request->mother_title,
            "second_group_no" => $request->second_group_no,
            "LANG_PART_I" => $request->LANG_PART_I,
            "brother_1" => $request->brother_1,
            "brother_2" => $request->brother_2,
            "gender_1" => $request->gender_1,
            "gender_2" => $request->gender_2,
            "class_1" => $request->class_1,
            "class_2" => $request->class_2,
            "brother_3" => $request->brother_3,
            "gender_3" => $request->gender_3,
            "class_3" => $request->class_3,
            "last_school_state" => $request->last_school_state,
            "second_language_school" => $request->second_language_school,
            "second_language" => $request->second_language,
            "ORGANISATION" => $request->ORGANISATION,
            "mother_organization" => $request->mother_organization,
            "guardian_organization" => $request->guardian_organization,

            "reference_name_1" => $request->reference_name_1,
            "reference_name_2" => $request->reference_name_2,
            "reference_phone_1" => $request->reference_phone_1,
            "status" => $request->status,
            "reference_phone_2" => $request->reference_phone_2,
        ]);

        //  request,$dmission, feildname on api,filepath,dbname

        // Handle file uploads for image fields
        $this->handleImageUpdate(
            $request,
            $admission,
            "migration_certificate",
            "admission_photos",
            "admission_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "profile_photo",
            "profile_photos",
            "profile_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "admission_photo",
            "admission_photos",
            "admission_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "birth_certificate",
            "birth_certificate_photos",
            "birth_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "aadhar_copy",
            "aadhar_card_photos",
            "aadhar_card_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "ration_card",
            "ration_card_photos",
            "ration_card_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "community_certificate",
            "community_certificate_photos",
            "community_certificate"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "salary_certificate",
            "slip_photos",
            "slip_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "medical_certificate",
            "medical_certificate_photos",
            "medical_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "reference_letter",
            "reference_letter_photos",
            "reference_letter_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "church_certificate",
            "church_certificate_photos",
            "church_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "transfer_certificate",
            "transfer_certificate_photos",
            "transfer_certificate_photo"
        );
        if (strtolower((string) $request->status) === "active") {
            // Check if admission_no already exists
            $userCreated = false;
            $user = null;
            if (!$admission->roll_no) {
                // Generate a new unique admission number
                $currentYear = now()->format("Y"); // Current year
                $yearCode = substr($currentYear, -2); // Last 2 digits of the year

                // Determine class code
                $classOfJoining = strtolower($admission->SOUGHT_STD);
                if ($classOfJoining === "lkg") {
                    $classCode = "13";
                } elseif ($classOfJoining === "ukg") {
                    $classCode = "14";
                } else {
                    $classCode = str_pad($classOfJoining, 2, "0", STR_PAD_LEFT); // Pad numeric classes
                }

                // Generate base admission number (YYCC)
                $baseAdmissionNo = $yearCode . $classCode;

                // Check the last admission number for the current year and class
                $lastAdmissionNo = Student::where(
                    "roll_no",
                    "like",
                    $baseAdmissionNo . "%"
                )
                    ->orderBy("admission_no", "desc")
                    ->value("roll_no");

                // Extract the serial number and increment
                if ($lastAdmissionNo) {
                    $lastSerial = (int) substr($lastAdmissionNo, -2); // Get the last 2 digits
                    $newSerial = str_pad($lastSerial + 1, 2, "0", STR_PAD_LEFT); // Increment and pad to 2 digits
                } else {
                    $newSerial = "01"; // Start with 01 if no existing records
                }

                // Combine base admission number with the new serial number
                $newAdmissionNo = $baseAdmissionNo . $newSerial;

                // Update the student's admission_no
                $admission->update(["roll_no" => $newAdmissionNo]);
            }

            if (!$admission->admission_no) {
                $lastAdmissionNo = User::where("admission_no", "like", "%SV%")
                    ->whereRaw("LENGTH(admission_no) = 12")
                    ->orderByRaw(
                        "STR_TO_DATE(SUBSTRING(admission_no, 3, 6), '%d%m%y') DESC"
                    )
                    ->orderByRaw(
                        "CAST(SUBSTRING(admission_no, 9, 4) AS UNSIGNED) DESC"
                    )
                    ->first();

                // Define the format for the new admission number
                $format = "SV" . date("dmy");

                if ($lastAdmissionNo) {
                    // Extract the numeric part of the last admission number
                    $lastNumber = intval(
                        substr($lastAdmissionNo->admission_no, 8)
                    );

                    // Check if the last number is 9999, reset to 0001 if it is
                    if ($lastNumber === 9999) {
                        $newNumber = 1;
                    } else {
                        // Increment the last number by 1
                        $newNumber = $lastNumber + 1;
                    }
                } else {
                    // If no previous admission numbers found, start with 0001
                    $newNumber = 1;
                }

                // Pad the new number with leading zeros to make it 4 digits
                $newNumberPadded = str_pad($newNumber, 4, "0", STR_PAD_LEFT);

                // Combine the format and new number to create the new admission number
                $newAdmissionNo = $format . $newNumberPadded;

                // Assuming you have a function to generate a unique serial number
                // $serialNo = generateSerialNumber(); // Replace this with your serial number generation logic

                $admissionId = $newAdmissionNo;
                $admission->update(["admission_no" => $admissionId]);
            }
            if (
                !User::where("roll_no", $admission->roll_no)
                    ->where("name", $admission->STUDENT_NAME)
                    ->exists()
            ) {
                $user = new User();

                // Get the last ID and increment it
                $lastid = User::latest("id")->value("id") ?? 0;
                $lastid = $lastid + 1;

                $user->id = $lastid;
                $user->name = $admission->STUDENT_NAME ?? null;
                $user->gender = $admission->SEX ?? null;
                $user->email = $admission->EMAIL_ID ?? null;
                $user->standard = $admission->SOUGHT_STD ?? null;
                $user->sec = $admission->sec ?? null;
                $user->hostelOrDay = "hostel";
                $user->password = Hash::make("svs@123");
                $user->admission_no = $admission->admission_no ?? null;
                $user->roll_no = $admission->roll_no ?? null;

                $user->save();
                $userCreated = true;
            }

            try {
                $userId = $user
                    ? $user->id
                    : User::where("roll_no", $admission->roll_no)
                        ->latest("id")
                        ->value("id");

                LifecycleLogger::log(
                    "Application Status Updated to Active",
                    $userId,
                    "application_status_activation",
                    [
                        "student_name" => $admission->STUDENT_NAME,
                        "admission_no" => $admission->admission_no,
                        "roll_no" => $admission->roll_no,
                        "user_created" => $userCreated,
                    ]
                );
            } catch (\Exception $e) {
                \Log::error(
                    "Failed to log application active lifecycle.  Status Updated to Active",
                    [
                        "user_id" => $userId ?? "N/A",
                        "error" => $e->getMessage(),
                    ]
                );
            }
        } else {
            try {
                $userId = User::where("roll_no", $admission->roll_no)
                    ->latest("id")
                    ->value("id");

                LifecycleLogger::log(
                    "Student Record Updated",
                    $userId,
                    "application_status_Updated",
                    [
                        "student_name" => $admission->STUDENT_NAME,
                        "admission_no" => $admission->admission_no,
                        "roll_no" => $admission->roll_no,
                    ]
                );
            } catch (\Exception $e) {
                \Log::error(
                    "Failed to log application active lifecycle.  Status Updated record",
                    [
                        "user_id" => $userId ?? "N/A",
                        "error" => $e->getMessage(),
                    ]
                );
            }
        }

        return response()->json([
            "message" => "Admission record updated successfully!",
            "data" => $admission,
        ]);
    }
    public function updatefromAdmission(Request $request, $id)
    {
        // Find the student admission record by ID
        $admission = AdmissionForm::findOrFail($id);
        Log::info("Request payload:", $request->all());

        // Update the student's basic information (non-file fields)
        $admission->update([
            //  'admission_no' => $request->input('admission_no'),
            "name" => $request->input("STUDENT_NAME"),
            "date_form" => $request->input("date_form"),
            "language" => $request->input("MOTHERTONGUE"),
            "state_student" => $request->input("STATE"),
            "date_of_birth" => $request->input("DOB_DD_MM_YYYY"),
            "gender" => $request->input("SEX"),
            "blood_group" => $request->input("BLOOD_GROUP"),
            "nationality" => $request->input("NATIONALITY"),
            "religion" => $request->input("RELIGION"),
            "church_denomination" => $request->input("DENOMINATION"),
            "caste" => $request->input("CASTE"),
            "caste_type" => $request->input("CASTE_CLASSIFICATION"),
            "aadhar_card_no" => $request->input("AADHAAR_CARD_NO"),
            "ration_card_no" => $request->input("RATIONCARDNO"),
            "emis_no" => $request->input("EMIS_NO"),
            "veg_or_non" => $request->input("FOOD"),
            "chronic_des" => $request->input("chronic_des"),
            "medicine_taken" => $request->input("medicine_taken"),
            "father_name" => $request->input("FATHER"),
            "father_occupation" => $request->input("OCCUPATION"),
            "mother_name" => $request->input("MOTHER"),
            "mother_occupation" => $request->input("mother_occupation"),
            "guardian_name" => $request->input("GUARDIAN"),
            "guardian_occupation" => $request->input("guardian_occupation"),
            "father_contact_no" => $request->input("MOBILE_NUMBER"),
            "father_email_id" => $request->input("EMAIL_ID"),
            "mother_contact_no" => $request->input("WHATS_APP_NO"),
            "mother_email_id" => $request->input("mother_email_id"),
            "guardian_contact_no" => $request->input("guardian_contact_no"),
            "guardian_email_id" => $request->input("guardian_email_id"),
            "father_income" => $request->input("MONTHLY_INCOME"),
            "mother_income" => $request->input("mother_income"),
            "guardian_income" => $request->input("guardian_income"),
            "house_no" => $request->input("PERMANENT_HOUSENUMBER"),
            "street" => $request->input("P_STREETNAME"),
            "city" => $request->input("P_VILLAGE_TOWN_NAME"),
            "district" => $request->input("P_DISTRICT"),
            "state" => $request->input("P_STATE"),
            "pincode" => $request->input("P_PINCODE"),
            "house_no_1" => $request->input("COMMUNICATION_HOUSE_NO"),
            "street_1" => $request->input("C_STREET_NAME"),
            "city_1" => $request->input("C_VILLAGE_TOWN_NAME"),
            "district_1" => $request->input("C_DISTRICT"),
            "state_1" => $request->input("C_STATE"),
            "pincode_1" => $request->input("C_PINCODE"),
            "last_class_std" => $request->input("CLASS_LAST_STUDIED"),
            "last_school" => $request->input("NAME_OF_SCHOOL"),
            "admission_for_class" => $request->input("SOUGHT_STD"),
            "brother_1" => $request->input("brother_1"),
            "brother_2" => $request->input("brother_2"),
            "gender_1" => $request->input("gender_1"),
            "gender_2" => $request->input("gender_2"),
            "class_1" => $request->input("class_1"),
            "class_2" => $request->input("class_2"),
            "brother_3" => $request->input("brother_3"),
            "gender_3" => $request->input("gender_3"),
            "class_3" => $request->input("class_3"),
            "last_school_state" => $request->input("last_school_state"),
            "reference_name_1" => $request->input("reference_name_1"),
            "reference_name_2" => $request->input("reference_name_2"),
            "reference_phone_1" => $request->input("reference_phone_1"),
            "reference_phone_2" => $request->input("reference_phone_2"),
            "status" => $request->input("status"),
            "syllabus" => $request->input("syllabus"),
            "group_no" => $request->input("GROUP_12"),
            "second_group_no" => $request->input("second_group_no"),
            "second_language" => $request->input("second_language"),
            "second_language_school" => $request->input(
                "second_language_school"
            ),
            "guardian_organization" => $request->input("guardian_organization"),
            "father_organization" => $request->input("ORGANISATION"),
            "mother_organization" => $request->input("mother_organization"),
            "father_title" => $request->father_title,
            "mother_title" => $request->mother_title,
        ]);

        foreach (
            [
                "profile_photo",
                "admission_photo",
                "birth_certificate",
                "aadhar_copy",
                "ration_card",
                "community_certificate",
                "salary_certificate",
                "medical_certificate",
                "reference_letter",
                "church_certificate",
                "transfer_certificate",
            ]
            as $field
        ) {
            Log::info("Field {$field}:", [$request->input($field)]);
        }

        // Handle the image update (using the helper function)profile_photo
        //  request,$dmission, feildname on api,filepath,dbname
        $this->handleImageUpdate(
            $request,
            $admission,
            "migration_certificate",
            "admission_photos",
            "admission_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "profile_photo",
            "profile_photos",
            "profile_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "admission_photo",
            "profile_photos",
            "admission_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "birth_certificate",
            "birth_certificate_photos",
            "birth_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "aadhar_copy",
            "aadhar_card_photos",
            "aadhar_card_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "ration_card",
            "ration_card_photos",
            "ration_card_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "community_certificate",
            "community_certificate_photos",
            "community_certificate"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "salary_certificate",
            "slip_photos",
            "slip_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "medical_certificate",
            "medical_certificate_photos",
            "medical_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "reference_letter",
            "reference_letter_photos",
            "reference_letter_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "church_certificate",
            "church_certificate_photos",
            "church_certificate_photo"
        );
        $this->handleImageUpdate(
            $request,
            $admission,
            "transfer_certificate",
            "transfer_certificate_photos",
            "transfer_certificate_photo"
        );

        // Return the updated student details
        return response()->json([
            "message" => "Student admission details updated successfully!",
            "data" => [
                "roll_no" => $admission->id,
                "admission_no" => $admission->admission_no,
                "STUDENT_NAME" => $admission->name,
                "date_form" => $admission->date_form,
                "MOTHERTONGUE" => $admission->language,
                "STATE" => $admission->state_student,
                "DOB_DD_MM_YYYY" => $admission->date_of_birth,
                "SEX" => $admission->gender,
                "BLOOD_GROUP" => $admission->blood_group,
                "NATIONALITY" => $admission->nationality,
                "RELIGION" => $admission->religion,
                "DENOMINATION" => $admission->church_denomination,
                "CASTE" => $admission->caste,
                "CASTE_CLASSIFICATION" => $admission->caste_type,
                "AADHAAR_CARD_NO" => $admission->aadhar_card_no,
                "RATIONCARDNO" => $admission->ration_card_no,
                "EMIS_NO" => $admission->emis_no,
                "FOOD" => $admission->veg_or_non,
                "chronic_des" => $admission->chronic_des,
                "medicine_taken" => $admission->medicine_taken,
                "FATHER" => $admission->father_name,
                "OCCUPATION" => $admission->father_occupation,
                "MOTHER" => $admission->mother_name,
                "mother_occupation" => $admission->mother_occupation,
                "GUARDIAN" => $admission->guardian_name,
                "guardian_occupation" => $admission->guardian_occupation,
                "MOBILE_NUMBER" => $admission->father_contact_no,
                "EMAIL_ID" => $admission->father_email_id,
                "WHATS_APP_NO" => $admission->mother_contact_no,
                "mother_email_id" => $admission->mother_email_id,
                "guardian_contact_no" => $admission->guardian_contact_no,
                "guardian_email_id" => $admission->guardian_email_id,
                "MONTHLY_INCOME" => $admission->father_income,
                "mother_income" => $admission->mother_income,
                "guardian_income" => $admission->guardian_income,
                "PERMANENT_HOUSENUMBER" => $admission->house_no,
                "P_STREETNAME" => $admission->street,
                "P_VILLAGE_TOWN_NAME" => $admission->city,
                "P_DISTRICT" => $admission->district,
                "P_STATE" => $admission->state,
                "P_PINCODE" => $admission->pincode,
                "COMMUNICATION_HOUSE_NO" => $admission->house_no_1,
                "C_STREET_NAME" => $admission->street_1,
                "C_VILLAGE_TOWN_NAME" => $admission->city_1,
                "C_DISTRICT" => $admission->district_1,
                "C_STATE" => $admission->state_1,
                "C_PINCODE" => $admission->pincode_1,
                "CLASS_LAST_STUDIED" => $admission->last_class_std,
                "NAME_OF_SCHOOL" => $admission->last_school,
                "SOUGHT_STD" => $admission->admission_for_class,
                "sec" => $admission->syllabus,
                "syllabus" => $admission->group_no,
                "syllabus" => $admission->group_no,
                "group_no" => $admission->second_group_no,
                "second_group_no" => $admission->second_group_no,
                "second_language" => $admission->second_language,
            ],
        ]);
    }
    public function show($id)
    {
        try {
            // Find the student admission record by ID
            $admission = Student::findOrFail($id);

            // Return the student details with image URLs
            return response()->json([
                "message" => "Student admission details fetched successfully!",
                "data" => [
                    "roll_no" => $admission->roll_no,
                    "admission_no" => $admission->admission_no,
                    "STUDENT_NAME" => $admission->STUDENT_NAME,
                    "date_form" => $admission->date_form,
                    "MOTHERTONGUE" => $admission->MOTHERTONGUE,
                    "STATE" => $admission->STATE,
                    "DOB_DD_MM_YYYY" => $admission->DOB_DD_MM_YYYY,
                    "SEX" => $admission->SEX,
                    "BLOOD_GROUP" => $admission->BLOOD_GROUP,
                    "NATIONALITY" => $admission->NATIONALITY,
                    "RELIGION" => $admission->RELIGION,
                    "DENOMINATION" => $admission->DENOMINATION,
                    "CASTE" => $admission->CASTE,
                    "CASTE_CLASSIFICATION" => $admission->CASTE_CLASSIFICATION,
                    "AADHAAR_CARD_NO" => $admission->AADHAAR_CARD_NO,
                    "RATIONCARDNO" => $admission->RATIONCARDNO,
                    "EMIS_NO" => $admission->EMIS_NO,
                    "pen_no" => $admission->pen_no,
                    "FOOD" => $admission->FOOD,
                    "chronic_des" => $admission->chronic_des,
                    "medicine_taken" => $admission->medicine_taken,
                    "FATHER" => $admission->FATHER,
                    "OCCUPATION" => $admission->OCCUPATION,
                    "MOTHER" => $admission->MOTHER,
                    "mother_occupation" => $admission->mother_occupation,
                    "GUARDIAN" => $admission->GUARDIAN,
                    "guardian_occupation" => $admission->guardian_occupation,
                    "MOBILE_NUMBER" => $admission->MOBILE_NUMBER,
                    "EMAIL_ID" => $admission->EMAIL_ID,
                    "WHATS_APP_NO" => $admission->WHATS_APP_NO,
                    "mother_email_id" => $admission->mother_email_id,
                    "guardian_contact_no" => $admission->guardian_contact_no,
                    "guardian_email_id" => $admission->guardian_email_id,
                    "MONTHLY_INCOME" => $admission->MONTHLY_INCOME,
                    "mother_income" => $admission->mother_income,
                    "guardian_income" => $admission->guardian_income,
                    "PERMANENT_HOUSENUMBER" =>
                        $admission->PERMANENT_HOUSENUMBER,
                    "P_STREETNAME" => $admission->P_STREETNAME,
                    "P_VILLAGE_TOWN_NAME" => $admission->P_VILLAGE_TOWN_NAME,
                    "P_DISTRICT" => $admission->P_DISTRICT,
                    "P_STATE" => $admission->P_STATE,
                    "P_PINCODE" => $admission->P_PINCODE,
                    "COMMUNICATION_HOUSE_NO" =>
                        $admission->COMMUNICATION_HOUSE_NO,
                    "C_STREET_NAME" => $admission->C_STREET_NAME,
                    "C_VILLAGE_TOWN_NAME" => $admission->C_VILLAGE_TOWN_NAME,
                    "C_DISTRICT" => $admission->C_DISTRICT,
                    "C_STATE" => $admission->C_STATE,
                    "C_PINCODE" => $admission->C_PINCODE,
                    "CLASS_LAST_STUDIED" => $admission->CLASS_LAST_STUDIED,
                    "NAME_OF_SCHOOL" => $admission->NAME_OF_SCHOOL,
                    "SOUGHT_STD" => $admission->SOUGHT_STD,
                    "sec" => $admission->sec,
                    "syllabus" => $admission->syllabus,
                    "GROUP_12" => $admission->GROUP_12,
                    // 'group_no' => $admission->group_no,
                    "second_group_no" => $admission->second_group_no,
                    "LANG_PART_I" => $admission->LANG_PART_I,
                    "guardian_organization" =>
                        $admission->guardian_organization,
                    "sibling" => isset($admission->brother_1) ? "Yes" : "No",
                    "brother_1" => $admission->brother_1,
                    "brother_2" => $admission->brother_2,
                    "gender_1" => $admission->gender_1,
                    "gender_2" => $admission->gender_2,
                    "class_1" => $admission->class_1,
                    "class_2" => $admission->class_2,
                    "brother_3" => $admission->brother_3,
                    "gender_3" => $admission->gender_3,
                    "class_3" => $admission->class_3,
                    "last_school_state" => $admission->last_school_state,
                    "second_language_school" =>
                        $admission->second_language_school,
                    "second_group_no" => $admission->second_group_no,
                    "second_language" => $admission->second_language,
                    "reference_name_1" => $admission->reference_name_1,
                    "reference_name_2" => $admission->reference_name_2,
                    "reference_phone_1" => $admission->reference_phone_1,
                    "reference_phone_2" => $admission->reference_phone_1,
                    "ORGANISATION" => $admission->ORGANISATION,
                    "mother_organization" => $admission->mother_organization,
                    "last_school_state" => $admission->last_school_state,
                    "GROUP_12" => $admission->GROUP_12,
                    "second_group_no" => $admission->second_group_no,
                    "reference_name_1" => $admission->reference_name_1,
                    "reference_phone_1" => $admission->reference_phone_1,
                    "reference_name_2" => $admission->reference_name_2,
                    "reference_phone_2" => $admission->reference_phone_2,
                    "status" => $admission->status,
                    "father_title" => $admission->father_title,
                    "mother_title" => $admission->mother_title,
                    "academic_year" => $admission->academic_year,
                    "grade_status" => $admission->grade_status,

                    // Image URLs
                    "profile_picture" => asset(
                        "storage/app/profile_photos/" .
                            $admission->profile_photo
                    ),
                    "birth_certificate" => asset(
                        "storage/app/birth_certificate_photos/" .
                            $admission->birth_certificate_photo
                    ),
                    "aadhar_copy" => asset(
                        "storage/app/aadhar_card_photos/" .
                            $admission->aadhar_card_photo
                    ),
                    "ration_card" => asset(
                        "storage/app/ration_card_photos/" .
                            $admission->ration_card_photo
                    ),
                    "community_certificate" => asset(
                        "storage/app/community_certificate_photos/" .
                            $admission->community_certificate
                    ),
                    "salary_certificate" => asset(
                        "storage/app/slip_photos/" . $admission->slip_photo
                    ),
                    "medical_certificate" => asset(
                        "storage/app/medical_certificate_photos/" .
                            $admission->medical_certificate_photo
                    ),
                    "reference_letter" => asset(
                        "storage/app/reference_letter_photos/" .
                            $admission->reference_letter_photo
                    ),
                    "church_certificate" => asset(
                        "storage/app/church_certificate_photos/" .
                            $admission->church_certificate_photo
                    ),
                    "transfer_certificate" => asset(
                        "storage/app/transfer_certificate_photos/" .
                            $admission->transfer_certificate_photo
                    ),
                    "migration_certificate" => asset(
                        "storage/app/admission_photos/" .
                            $admission->admission_photo
                    ),
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return an error response if the record is not found
            return response()->json(
                [
                    "message" => "Admission record not found!",
                    "error" => $e->getMessage(),
                ],
                404
            );
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json(
                [
                    "message" =>
                        "An error occurred while fetching the admission record.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
    public function showfromAdmission($id)
    {
        try {
            $admission = AdmissionForm::findOrFail($id);

            // Return the student details with the same key names but updated values
            return response()->json([
                "message" =>
                    "Admission submitted details fetched successfully!",
                "data" => [
                    "roll_no" => "",
                    "admission_no" => $admission->admission_no,
                    "STUDENT_NAME" => $admission->name,
                    "date_form" => $admission->date_form,
                    "MOTHERTONGUE" => $admission->language,
                    "STATE" => $admission->state_student,
                    "DOB_DD_MM_YYYY" => $admission->date_of_birth,
                    "SEX" => $admission->gender,
                    "BLOOD_GROUP" => $admission->blood_group,
                    "NATIONALITY" => $admission->nationality,
                    "RELIGION" => $admission->religion,
                    "DENOMINATION" => $admission->church_denomination,
                    "CASTE" => $admission->caste,
                    "CASTE_CLASSIFICATION" => $admission->caste_type,
                    "AADHAAR_CARD_NO" => $admission->aadhar_card_no,
                    "RATIONCARDNO" => $admission->ration_card_no,
                    "EMIS_NO" => $admission->emis_no,
                    "pen_no" => $admission->pen_no,
                    "FOOD" => $admission->veg_or_non,
                    "chronic_des" => $admission->chronic_des,
                    "medicine_taken" => $admission->medicine_taken,
                    "FATHER" => $admission->father_name,
                    "OCCUPATION" => $admission->father_occupation,
                    "MOTHER" => $admission->mother_name,
                    "mother_occupation" => $admission->mother_occupation,
                    "GUARDIAN" => $admission->guardian_name,
                    "guardian_occupation" => $admission->guardian_occupation,
                    "MOBILE_NUMBER" => $admission->father_contact_no,
                    "EMAIL_ID" => $admission->father_email_id,
                    "WHATS_APP_NO" => $admission->mother_contact_no,
                    "mother_email_id" => $admission->mother_email_id,
                    "guardian_contact_no" => $admission->guardian_contact_no,
                    "guardian_email_id" => $admission->guardian_email_id,
                    "MONTHLY_INCOME" => $admission->father_income,
                    "mother_income" => $admission->mother_income,
                    "guardian_income" => $admission->guardian_income,
                    "PERMANENT_HOUSENUMBER" => $admission->house_no,
                    "P_STREETNAME" => $admission->street,
                    "P_VILLAGE_TOWN_NAME" => $admission->city,
                    "P_DISTRICT" => $admission->district,
                    "P_STATE" => $admission->state,
                    "P_PINCODE" => $admission->pincode,
                    "COMMUNICATION_HOUSE_NO" => $admission->house_no_1,
                    "C_STREET_NAME" => $admission->street_1,
                    "C_VILLAGE_TOWN_NAME" => $admission->city_1,
                    "C_DISTRICT" => $admission->district_1,
                    "C_STATE" => $admission->state_1,
                    "C_PINCODE" => $admission->pincode_1,
                    "CLASS_LAST_STUDIED" => $admission->last_class_std,
                    "NAME_OF_SCHOOL" => $admission->last_school,
                    "SOUGHT_STD" => $admission->admission_for_class,
                    "sec" => $admission->sec,
                    "syllabus" => $admission->syllabus,
                    "GROUP_12" => $admission->group_no,
                    "second_group_no" => $admission->second_group_no,
                    "second_language" => $admission->second_language,
                    "second_group_no" => $admission->second_group_no,
                    "LANG_PART_I" => $admission->second_language_school,
                    "guardian_organization" =>
                        $admission->guardian_organization,
                    "ORGANISATION" => $admission->father_organization,
                    "mother_organization" => $admission->mother_organization,
                    "sibling" => isset($admission->brother_1) ? "Yes" : "No",
                    "brother_1" => $admission->brother_1,
                    "brother_2" => $admission->brother_2,
                    "gender_1" => $admission->gender_1,
                    "gender_2" => $admission->gender_2,
                    "class_1" => $admission->class_1,
                    "class_2" => $admission->class_2,
                    "brother_3" => $admission->brother_3,
                    "gender_3" => $admission->gender_3,
                    "class_3" => $admission->class_3,
                    "last_school_state" => $admission->last_school_state,
                    "second_language_school" =>
                        $admission->second_language_school,
                    "reference_name_1" => $admission->reference_name_1,
                    "reference_name_2" => $admission->reference_name_2,
                    "reference_phone_1" => $admission->reference_phone_1,
                    "reference_phone_2" => $admission->reference_phone_2,
                    "group_no" => $admission->group_no,
                    "father_title" => $admission->father_title,
                    "mother_title" => $admission->mother_title,
                    "status" => $admission->status,
                    // Image URLs
                    "profile_picture" => asset(
                        "storage/app/profile_photos/" .
                            $admission->profile_photo
                    ),
                    "birth_certificate" => asset(
                        "storage/app/birth_certificate_photos/" .
                            $admission->birth_certificate_photo
                    ),
                    "aadhar_copy" => asset(
                        "storage/app/aadhar_card_photos/" .
                            $admission->aadhar_card_photo
                    ),
                    "ration_card" => asset(
                        "storage/app/ration_card_photos/" .
                            $admission->ration_card_photo
                    ),
                    "community_certificate" => asset(
                        "storage/app/community_certificate_photos/" .
                            $admission->community_certificate
                    ),
                    "salary_certificate" => asset(
                        "storage/app/slip_photos/" . $admission->slip_photo
                    ),
                    "medical_certificate" => asset(
                        "storage/app/medical_certificate_photos/" .
                            $admission->medical_certificate_photo
                    ),
                    "reference_letter" => asset(
                        "storage/app/reference_letter_photos/" .
                            $admission->reference_letter_photo
                    ),
                    "church_certificate" => asset(
                        "storage/app/church_certificate_photos/" .
                            $admission->church_certificate_photo
                    ),
                    "transfer_certificate" => asset(
                        "storage/app/transfer_certificate_photos/" .
                            $admission->transfer_certificate_photo
                    ),
                    "migration_certificate" => asset(
                        "storage/app/admission_photos/" .
                            $admission->admission_photo
                    ),
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return an error response if the record is not found
            return response()->json(
                [
                    "message" => "Admission record not found!",
                    "error" => $e->getMessage(),
                ],
                404
            );
        } catch (\Exception $e) {
            // Handle any other exceptions
            return response()->json(
                [
                    "message" =>
                        "An error occurred while fetching the admission record.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    /**
     * Handle updating an image field with a base64 encoded string.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Admission $admission
     * @param string $fieldName
     */
    //  request,$dmission, feildname on api,filepath,dbname

    private function handleImageUpdate(
        $request,
        $staff,
        $fieldNameApi,
        $filePath,
        $dbName
    ) {
        try {
            // Check for uploaded file (multipart/form-data)
            if ($request->hasFile($fieldNameApi)) {
                $file = $request->file($fieldNameApi);

                $extension = $file->getClientOriginalExtension();
                $timestamp = now()->format("Ymd_His");
                $fileName = uniqid() . "_{$timestamp}." . $extension;
                $filePathWithName = $filePath . "/" . $fileName;

                // Save the uploaded file
                $stored = Storage::disk("custompublic")->put($filePathWithName, file_get_contents($file));

                if (!$stored) {
                    throw new \Exception("Failed to store file for field: $fieldNameApi");
                }

                $staff->update([$dbName => $fileName]);
                Log::info("Uploaded file for {$fieldNameApi} stored at: {$filePathWithName}");

            } elseif ($request->has($fieldNameApi)) {
                // Handle base64 string
                $base64Image = $request->input($fieldNameApi);

                if (preg_match("/^data:image\/(\w+);base64,/", $base64Image, $matches)) {
                    $extension = $matches[1];
                    $base64Image = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));

                    if ($base64Image === false) {
                        throw new \Exception("Base64 decoding failed for field: $fieldNameApi");
                    }

                    $timestamp = now()->format("Ymd_His");
                    $fileName = uniqid() . "_{$timestamp}." . $extension;
                    $filePathWithName = $filePath . "/" . $fileName;

                    $fullPath = Storage::disk("custompublic")->path($filePathWithName);
                    $isWritten = file_put_contents($fullPath, $base64Image);

                    if ($isWritten === false) {
                        throw new \Exception("Failed to write base64 image for field: $fieldNameApi");
                    }

                    $staff->update([$dbName => $fileName]);
                    Log::info("Base64 image for {$fieldNameApi} stored at: {$filePathWithName}");
                } else {
                    throw new \Exception("Invalid base64 string for field: $fieldNameApi");
                }
            }

        } catch (\Exception $e) {
            Log::error("Error updating {$fieldNameApi}: " . $e->getMessage());
        }
    }


    private function handleImageUpdatepreviosu($request, $admission, $fieldName)
    {
        // Check if the field is a URL (skip if URL)
        if (filter_var($request->$fieldName, FILTER_VALIDATE_URL)) {
            return; // Do nothing if it's a URL
        }

        // Check if the field contains a file upload
        if ($request->hasFile($fieldName)) {
            $timestamp = date("YmdHis");
            $extension = $request->$fieldName->extension();
            $filename =
                $fieldName .
                $admission->id .
                "_" .
                $timestamp .
                "." .
                $extension;

            $path = $request->$fieldName->storeAs(
                $fieldName . "_photos",
                $filename
            );
            $request->$fieldName->storeAs($fieldName . "_photos", $path);

            // Update the model with the file path
            $admission->update([$fieldName => $path]);
        } elseif (preg_match("/^data:image/", $request->$fieldName)) {
            // Handle base64 encoded image
            $image = $request->$fieldName;
            $image = str_replace("data:image/jpeg;base64,", "", $image);
            $image = str_replace("data:image/png;base64,", "", $image);
            $image = str_replace(" ", "+", $image);
            $imageData = base64_decode($image);

            // Create a unique file name
            $path = $fieldName . $admission->id . ".jpg"; // Or change extension based on type

            // Store the image in the storage folder
            Storage::disk("local")->put(
                $fieldName . "_photos/" . $path,
                $imageData
            );

            // Update the model with the file path
            $admission->update([$fieldName => $path]);
        }
    }
    public function updatetttt(Request $request, $id)
    {
        $record = Student::find($id); // Replace YourModel with the actual model name

        if (!$record) {
            return response()->json(["error" => "Record not found."]);
        }

        $record->id = $request->input("id");
        $record->admission_no = $request->input("admission_no");
        $record->roll_no = $request->input("roll_no");
        $record->student_name = $request->input("student_name");
        $record->sex = $request->input("sex");
        $record->dob = $request->input("dob");
        $record->blood_group = $request->input("blood_group");
        $record->emis_no = $request->input("emis_no");
        $record->Nationality = $request->input("Nationality");
        $record->State = $request->input("State");
        $record->Religion = $request->input("Religion");
        $record->Denomination = $request->input("Denomination");
        $record->Caste = $request->input("Caste");
        $record->CasteClassification = $request->input("CasteClassification");
        $record->AadhaarCardNo = $request->input("AadhaarCardNo");
        $record->RationCard = $request->input("RationCard");
        $record->Mothertongue = $request->input("Mothertongue");
        $record->Father = $request->input("Father");
        $record->Mother = $request->input("Mother");
        $record->Guardian = $request->input("Guardian");
        $record->Occupation = $request->input("Occupation");
        $record->Organisation = $request->input("Organisation");
        $record->Monthlyincome = $request->input("Monthlyincome");
        $record->p_housenumber = $request->input("p_housenumber");
        $record->p_Streetname = $request->input("p_Streetname");
        $record->p_VillagetownName = $request->input("p_VillagetownName");
        $record->p_Postoffice = $request->input("p_Postoffice");
        $record->p_Taluk = $request->input("p_Taluk");
        $record->p_District = $request->input("p_District");
        $record->p_State = $request->input("p_State");
        $record->p_Pincode = $request->input("p_Pincode");
        $record->c_HouseNumber = $request->input("c_HouseNumber");
        $record->c_StreetName = $request->input("c_StreetName");
        $record->c_VillageTownName = $request->input("c_VillageTownName");
        $record->c_Postoffice = $request->input("c_Postoffice");
        $record->c_Taluk = $request->input("c_Taluk");
        $record->c_District = $request->input("c_District");
        $record->c_State = $request->input("c_State");
        $record->c_Pincode = $request->input("c_Pincode");
        $record->Mobilenumber = $request->input("Mobilenumber");
        $record->WhatsAppNo = $request->input("WhatsAppNo");
        $record->ClasslastStudied = $request->input("ClasslastStudied");
        $record->EmailID = $request->input("EmailID");
        $record->Nameofschool = $request->input("Nameofschool");
        $record->File = $request->input("File");
        $record->sought_Std = $request->input("sought_Std");
        $record->sec = $request->input("sec");
        $record->Part_I = $request->input("Part_I");
        $record->Group = $request->input("Group");
        $record->FOOD = $request->input("FOOD");
        $record->special_information = $request->input("special_information");
        $record->Declare_not_attended = $request->input("Declare_not_attended");
        $record->Declare_dues = $request->input("Declare_dues");
        $record->Declare_dob = $request->input("Declare_dob");
        $record->Declare_Date = $request->input("Declare_Date");
        $record->Declare_Place = $request->input("Declare_Place");
        $record->Measles = $request->input("Measles");
        $record->Chickenpox = $request->input("Chickenpox");
        $record->Fits = $request->input("Fits");
        $record->Rheumaticfever = $request->input("Rheumaticfever");
        $record->Mumps = $request->input("Mumps");
        $record->Jaundice = $request->input("Jaundice");
        $record->Asthma = $request->input("Asthma");
        $record->Nephritis = $request->input("Nephritis");
        $record->Whoopingcough = $request->input("Whoopingcough");
        $record->Tuberculosis = $request->input("Tuberculosis");
        $record->Hayfever = $request->input("Hayfever");
        $record->CongenitalHeartDisease = $request->input(
            "CongenitalHeartDisease"
        );
        $record->P_Bronchial = $request->input("P_Bronchial");
        $record->P_Tuberculosis = $request->input("P_Tuberculosis");
        $record->BCG = $request->input("BCG");
        $record->Triple_Vaccine = $request->input("Triple_Vaccine");
        $record->Polio_Drops = $request->input("Polio_Drops");
        $record->Measles_given = $request->input("Measles_given");
        $record->MMR = $request->input("MMR");
        $record->Dual_Vaccine = $request->input("Dual_Vaccine");
        $record->Typhoid = $request->input("Typhoid");
        $record->Cholera = $request->input("Cholera");
        $record->permission_to_principal = $request->input(
            "permission_to_principal"
        );
        $record->administration_of_anaesthetic = $request->input(
            "administration_of_anaesthetic"
        );
        $record->hostelOrDay = $request->input("hostelOrDay");
        $record->language = $request->input("language");
        $record->state_student = $request->input("state_student");
        $record->profile_photo = $request->input("profile_photo");
        $record->admission_photo = $request->input("admission_photo");
        $record->brother_1 = $request->input("brother_1");
        $record->brother_2 = $request->input("brother_2");
        $record->gender_1 = $request->input("gender_1");
        $record->gender_2 = $request->input("gender_2");
        $record->class_1 = $request->input("class_1");
        $record->class_2 = $request->input("class_2");
        $record->brother_3 = $request->input("brother_3");
        $record->gender_3 = $request->input("gender_3");
        $record->class_3 = $request->input("class_3");
        $record->last_school_state = $request->input("last_school_state");
        $record->second_language_school = $request->input(
            "second_language_school"
        );
        $record->reference_name_1 = $request->input("reference_name_1");
        $record->reference_name_2 = $request->input("reference_name_2");
        $record->reference_phone_1 = $request->input("reference_phone_1");
        $record->reference_phone_2 = $request->input("reference_phone_2");

        $record->save(); // Save the updated record

        return response()->json(["success" => "Record updated successfully."]);
    }
    public function deletefromstudent(Request $request, $id)
    {
        $admission = Student::findOrFail($id);
        if (
            $admission->profile_photo &&
            Storage::exists($admission->profile_photo)
        ) {
            Storage::delete($admission->profile_photo); // Delete the profile photo
        }

        if (
            $admission->admission_photo &&
            Storage::exists($admission->admission_photo)
        ) {
            Storage::delete($admission->admission_photo); // Delete the admission photo
        }
        $admission->delete();
        return response()->json([
            "message" => "Student admission deleted successfully.",
        ]);
    }
    public function deletefromadmission(Request $request, $id)
    {
        $admission = AdmissionForm::findOrFail($id);
        if (
            $admission->profile_photo &&
            Storage::exists($admission->profile_photo)
        ) {
            Storage::delete($admission->profile_photo); // Delete the profile photo
        }

        if (
            $admission->admission_photo &&
            Storage::exists($admission->admission_photo)
        ) {
            Storage::delete($admission->admission_photo); // Delete the admission photo
        }
        $admission->delete();
        return response()->json([
            "message" => "Student admission deleted successfully.",
        ]);
    }
}

// function convertValue($value) {
//     $romanNumerals = [
//         'I' => 1, 'II' => 2, 'III' => 3, 'IV' => 4, 'V' => 5,
//         'VI' => 6, 'VII' => 7, 'VIII' => 8, 'IX' => 9, 'X' => 10, 'XI' => 11,'XII' => 12,
//     ];

//     $value = strtoupper(trim($value));

//     if (isset($romanNumerals[$value])) {
//         return $romanNumerals[$value];
//     } elseif ($value === 'LKG' || $value === 'UKG') {
//         return strtolower($value); // Convert to lowercase
//     } else {
//         return null;
//     }
// }
