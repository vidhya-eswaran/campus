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
use App\Exports\StudentsExport;
use Intervention\Image\Facades\Image;
use App\Helpers\LifecycleLogger;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\PushNotificationController;
use App\Models\Admission;

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

    public function uploadStudentData(Request $request)
    {
        $request->validate([
                'student_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240', // up to 10MB
                
            ]);

            if ($request->hasFile('student_file')) {
                $this->handleBulkUpload($request->file('student_file'));
            } else {
                $this->handleSingleUpload($request);
            }       

            return response()->json([
                'message' => 'Upload complete',
                'uploaded' => $response['uploaded'] ?? [],
                'duplicates' => $response['duplicates'] ?? [],
            ]);

    }

    public function processStudentRecord($request)
    {
        //dd($request);
        try {        
            
            $record =  (object) $request->all();

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
            //dd($record);
              
            if (isset($record->admission_no) && $record->admission_no !== "") {
                               
                $existingStudent = Student::where("roll_no","like",$record->roll_no)->first();
                $existingStudentuser = User::where("roll_no","like",$record->roll_no)->first();
                if ($existingStudentuser) {
                    // dd("2");
                    // Move the existing record to history table
                    //$studentHistory = new StudentHistory();

                    $studentHistory = new StudentHistory();
                    $studentHistory->original_id = $existingStudent->id;

                    foreach ($existingStudent->getAttributes() as $key => $value) {
                        if ($key === 'id') {
                            continue;
                        }

                        if (Schema::hasColumn('admitted_students_history', $key)) {
                            $studentHistory->$key = $value;
                        }
                    }

                    $studentHistory->save();
                    $schoolSlug = request()->route('school');
                    $mappedData = (array) $record;
                    foreach ($imageFields as $field) {
                        if ($request->hasFile($field)) {
                            $file = $request->file($field);

                            $filename = now()->format('Ymd_His') . '_' . $field . '.' . $file->getClientOriginalExtension();
                            $path = 'documents/' . $schoolSlug . '/student_images/' . $filename;

                            Storage::disk('s3')->put($path, file_get_contents($file));

                            // Set the full URL for accessing the image
                            $mappedData[$field] = Storage::disk('s3')->url($path);
                        } else {
                            $mappedData[$field] = null;
                        }
                    }
                   // dd("s3", $mappedData);

                    // If date fields need to be converted, handle them separately
                    if (!empty($record->dob)) {
                        $mappedData['dob'] = $this->convertExcelDate($record->dob);
                    }

                    if (!empty($record->date_form)) {
                        $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                    }

                    $existingStudent->fill($mappedData);
                    $existingStudent->save();



                    //existing user update
                    $existingStudentuser->name = $record->student_name ?? null;
                    $existingStudentuser->gender = $record->gender ?? null;
                    $existingStudentuser->email = $record->father_email_id ?? null;
                    $existingStudentuser->standard = $record->std_sought ?? null;
                    $existingStudentuser->sec = $record->sec ?? null;
                    $existingStudentuser->twe_group = $record->syllabus ?? null;
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
                        "email" => $record->father_email_id,
                        "admission_no" => $record->admission_no,
                        "message" =>
                            "Data modified successfully as it is already exists.",
                    ];
                } else {
                    // dd("3");
                    $recordEmail = $record->father_email_id;
                    $recordAdmissionNo = $record->admission_no;

                    try {
                        $student = new Student();

                        $mappedData = (array) $record;
                        $schoolSlug = request()->route('school');
                        foreach ($imageFields as $field) {
                            if ($request->hasFile($field)) {
                                $file = $request->file($field);

                                $filename = now()->format('Ymd_His') . '_' . $field . '.' . $file->getClientOriginalExtension();
                                $path = 'documents/' . $schoolSlug . '/student_images/' . $filename;

                                Storage::disk('s3')->put($path, file_get_contents($file));

                                // Set the full URL for accessing the image
                                $mappedData[$field] = Storage::disk('s3')->url($path);
                            } else {
                                $mappedData[$field] = null;
                            }
                        }
                      //  dd("s31", $mappedData);

                        if (!empty($record->dob)) {
                            $mappedData['dob'] = $this->convertExcelDate($record->dob);
                        }

                        if (!empty($record->date_form)) {
                            $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                        }

                        $student->fill($mappedData);
                        $student->save();

                     
                        $user = new User();
                        $lastid = User::latest("id")->value("id");
                        $lastid = $lastid + 1;
                        $user->id = $lastid;
                        $user->name = $record->student_name ?? null;
                        $user->gender = $record->gender ?? null;
                        $user->email = $record->father_email_id ?? null;
                        $user->standard = $record->std_sought ?? null;
                        $user->sec = $record->sec ?? null;
                        $user->twe_group = $record->group_first_choice ?? null;
                        $user->hostelOrDay = "hostel";
                        $user->password = Hash::make("Student@123");
                        $user->admission_no = $record->admission_no ?? null;
                        $user->roll_no = $record->roll_no ?? null;
                        $user->save();
                        $response["uploaded"][] = [
                            "email" => $recordEmail,
                            "admission_no" => $recordAdmissionNo,
                            "message" => "Data uploaded successfully.",
                        ];
                    } catch (\Illuminate\Validation\ValidationException $e) {
                        dd($e->errors()); // this will show validation errors
                    }
                }
            } elseif (!$record->admission_no && $record->student_name && $record->std_sought) {
              // dd("4");
                $lastAdmissionNo = User::where("admission_no", "like", "%SV%")
                    ->whereRaw("LENGTH(admission_no) = 12")
                    ->orderByRaw(
                        "STR_TO_DATE(SUBSTRING(admission_no, 3, 6), '%d%m%y') DESC"
                    )
                    ->orderByRaw(
                        "CAST(SUBSTRING(admission_no, 9, 4) AS UNSIGNED) DESC"
                    )
                    ->first();

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
                $admissionId = $newAdmissionNo;
                
                if ($admissionId && $record->father_email_id) {
                     //dd("5");
                    $existingUser = User::where("name", $record->student_name)
                        //->where('Father', $record[20])
                        //  ->where('Mobilenumber', $record[26])
                        ->where("standard", $record->std_sought)
                        //    ->where('sec', $record[50])  ///////////
                        ->first();

                        $recordEmail = $record->father_email_id ?? "";
                        $recordAdmissionNo = $admissionId;

                      
                        try {
                            $student = new Student();
                           // $student->admission_no = $admissionId ?? null;

                            $mappedData = (array) $record;
                            $schoolSlug = request()->route('school');
                            foreach ($imageFields as $field) {
                                if ($request->hasFile($field)) {
                                    $file = $request->file($field);

                                    $filename = now()->format('Ymd_His') . '_' . $field . '.' . $file->getClientOriginalExtension();
                                    $path = 'documents/' . $schoolSlug . '/student_images/' . $filename;

                                    Storage::disk('s3')->put($path, file_get_contents($file));

                                    // Set the full URL for accessing the image
                                    $mappedData[$field] = Storage::disk('s3')->url($path);
                                } else {
                                    $mappedData[$field] = null;
                                }
                            }

                            $mappedData['admission_no'] = $admissionId ?? null;

                            // If date fields need to be converted, handle them separately
                            if (!empty($record->dob)) {
                                $mappedData['dob'] = $this->convertExcelDate($record->dob);
                            }

                            if (!empty($record->date_form)) {
                                $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                            }

                            $student->fill($mappedData);
                            $student->save();

                           
                            $user = new User();
                            $lastid = User::latest("id")->value("id");
                            $lastid = $lastid + 1;
                            $user->id = $lastid;

                            $user->name = $record->student_name ?? null;
                            $user->gender = $record->gender ?? null;
                            $user->email = $record->father_email_id ?? null;
                            $user->standard = $record->std_sought ?? null;
                            $user->sec = $record->sec ?? null;
                            $user->twe_group = $record->group_first_choice ?? null;
                            $user->hostelOrDay = "hostel";
                            $user->password = Hash::make("Student@123");
                            $user->admission_no = $admissionId ?? null;
                            $user->roll_no = $record->roll_no ?? null;

                            $user->save();
                            $response["uploaded"][] = [
                                "email" => $recordEmail,
                                "admission_no" => $recordAdmissionNo,
                                "message" => "Data uploaded successfully.",
                            ];
                        } catch (\Illuminate\Validation\ValidationException $e) {
                        dd($e->errors()); // this will show validation errors
                    }
                    
                }
            }
        
            return response()
            ->json($response, 200)
            ->header("Access-Control-Allow-Origin", "*");

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching gender counts',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handleSingleUpload(Request $request)
    {
        //dd("single");
        $this->processStudentRecord($request);
    }




    public function handleBulkUpload($file)
    {
        $collection = Excel::toCollection(null, $file)->first();

        if ($collection->isEmpty() || $collection->count() < 2) {
            return response()->json(['message' => 'The Excel file is empty or has no data rows.'], 422);
        }

        // Get headers from the first row
        $headers = $collection->first()->toArray();

        foreach ($collection->skip(1) as $index => $row) {
            if ($row->filter()->isNotEmpty()) {
                $values = $row->toArray();

                // Map headers to values
                $data = array_combine($headers, $values);

            try {       
                
                $record =  (object) $data;

                //dd($record->admission_no);
                
                if (isset($record->admission_no) && $record->admission_no !== "") {
                                
                    $existingStudent = Student::where("roll_no","like",$record->roll_no)->first();
                    $existingStudentuser = User::where("roll_no","like",$record->roll_no)->first();
                    if ($existingStudentuser) {
                    // dd("2");
                        // Move the existing record to history table
                        //$studentHistory = new StudentHistory();

                        $studentHistory = new StudentHistory();
                        $studentHistory->original_id = $existingStudent->id;

                        foreach ($existingStudent->getAttributes() as $key => $value) {
                            if ($key === 'id') {
                                continue;
                            }

                            if (Schema::hasColumn('admitted_students_history', $key)) {
                                $studentHistory->$key = $value;
                            }
                        }

                        $studentHistory->save();
                    
                        $mappedData = $data;
                    
                        // If date fields need to be converted, handle them separately
                        if (!empty($record->dob)) {
                            $mappedData['dob'] = $this->convertExcelDate($record->dob);
                        }

                        if (!empty($record->date_form)) {
                            $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                        }
                    //  dd($mappedData);
                        $existingStudent->fill($mappedData);
                        $existingStudent->save();
                    // dd("SSs");


                        //existing user update
                        $existingStudentuser->name = $record->student_name ?? null;
                        $existingStudentuser->gender = $record->gender ?? null;
                        $existingStudentuser->email = $record->father_email_id ?? null;
                        $existingStudentuser->standard = $record->std_sought ?? null;
                        $existingStudentuser->sec = $record->sec ?? null;
                        $existingStudentuser->twe_group = $record->syllabus ?? null;
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
                        //dd($existingStudentuser);
                        $response["duplicates"][] = [
                            "email" => $record->father_email_id,
                            "admission_no" => $record->admission_no,
                            "message" =>
                                "Data modified successfully as it is already exists.",
                        ];
                    } else {
                        // dd("3");
                        $recordEmail = $record->father_email_id;
                        $recordAdmissionNo = $record->admission_no;

                        try {
                            $student = new Student();

                            $mappedData = $data;

                            if (!empty($record->dob)) {
                                $mappedData['dob'] = $this->convertExcelDate($record->dob);
                            }

                            if (!empty($record->date_form)) {
                                $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                            }

                            $student->fill($mappedData);
                            $student->save();

                        // dd("SSS");
                            $user = new User();
                            $lastid = User::latest("id")->value("id");
                            $lastid = $lastid + 1;
                            $user->id = $lastid;
                            $user->name = $record->student_name ?? null;
                            $user->gender = $record->gender ?? null;
                            $user->email = $record->father_email_id ?? null;
                            $user->standard = $record->std_sought ?? null;
                            $user->sec = $record->sec ?? null;
                            $user->twe_group = $record->group_first_choice ?? null;
                            $user->hostelOrDay = "hostel";
                            $user->password = Hash::make("Student@123");
                            $user->admission_no = $record->admission_no ?? null;
                            $user->roll_no = $record->roll_no ?? null;
                            $user->save();
                            $response["uploaded"][] = [
                                "email" => $recordEmail,
                                "admission_no" => $recordAdmissionNo,
                                "message" => "Data uploaded successfully.",
                            ];
                        } catch (\Illuminate\Validation\ValidationException $e) {
                            dd($e->errors()); // this will show validation errors
                        }
                    }
                } elseif (!$record->admission_no && $record->student_name && $record->std_sought) {
                    //  dd($record);
                    $lastAdmissionNo = User::where("admission_no", "like", "%SV%")
                        ->whereRaw("LENGTH(admission_no) = 12")
                        ->orderByRaw(
                            "STR_TO_DATE(SUBSTRING(admission_no, 3, 6), '%d%m%y') DESC"
                        )
                        ->orderByRaw(
                            "CAST(SUBSTRING(admission_no, 9, 4) AS UNSIGNED) DESC"
                        )
                        ->first();

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
                    $admissionId = $newAdmissionNo;
                    
                    if ($admissionId && $record->father_email_id) {
                        //dd("5");
                        $existingUser = User::where("name", $record->student_name)
                            //->where('Father', $record[20])
                            //  ->where('Mobilenumber', $record[26])
                            ->where("standard", $record->std_sought)
                            //    ->where('sec', $record[50])  ///////////
                            ->first();

                            $recordEmail = $record->father_email_id ?? "";
                            $recordAdmissionNo = $admissionId;

                        
                            try {
                                $student = new Student();
                            // $student->admission_no = $admissionId ?? null;

                                $mappedData = $data;

                            
                                $mappedData['admission_no'] = $admissionId ?? null;

                                // If date fields need to be converted, handle them separately
                                if (!empty($record->dob)) {
                                    $mappedData['dob'] = $this->convertExcelDate($record->dob);
                                }

                                if (!empty($record->date_form)) {
                                    $mappedData['date_form'] = $this->convertExcelDate($record->date_form);
                                }

                                $student->fill($mappedData);
                                $student->save();

                            
                                $user = new User();
                                $lastid = User::latest("id")->value("id");
                                $lastid = $lastid + 1;
                                $user->id = $lastid;

                                $user->name = $record->student_name ?? null;
                                $user->gender = $record->gender ?? null;
                                $user->email = $record->father_email_id ?? null;
                                $user->standard = $record->std_sought ?? null;
                                $user->sec = $record->sec ?? null;
                                $user->twe_group = $record->group_first_choice ?? null;
                                $user->hostelOrDay = "hostel";
                                $user->password = Hash::make("Student@123");
                                $user->admission_no = $admissionId ?? null;
                                $user->roll_no = $record->roll_no ?? null;

                                $user->save();
                                $response["uploaded"][] = [
                                    "email" => $recordEmail,
                                    "admission_no" => $recordAdmissionNo,
                                    "message" => "Data uploaded successfully.",
                                ];
                            } catch (\Illuminate\Validation\ValidationException $e) {
                            dd($e->errors()); // this will show validation errors
                        }
                        
                    }
                }           
                

                } catch (\Exception $e) {
                    Log::error("Row $index failed: " . $e->getMessage());
                    $response['errors'][] = [
                        'row' => $index + 1, // Human-readable row number
                        'message' => $e->getMessage(),
                    ];
                }
            }
        }
        return response()
                ->json($response, 200)
                ->header("Access-Control-Allow-Origin", "*");

    }



    public function insertStudentData(array $data)
    {
        // Insert into admitted_students table
        $studentId = DB::table('admitted_students')->insertGetId($data);
        return $studentId;
    }

    public function getadmissionStandards(Request $request, $standard)
    {
        $standard = $request->standard;
        $standards = Student::where("std_sought", $standard)
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
        $fields = ['student_name', 'roll_no', 'std_sought', 'sec', 'admission_id']; // <-- Define this

        $query = Student::query();

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
            $student->standard = $student->std_sought;
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

    public function readByadmissioNo(Request $request, $admission_no)
    {
        $admission_no = $request->admission_no;
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
        $id = $request->id;
        $admission = Student::findOrFail($id);
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

        // Bulk update non-file fields
        $nonFileData = $request->except($imageFields);
        $admission->update($nonFileData);
        $schoolSlug = request()->route('school');
       //dd($imageFields);
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);

                // Generate unique filename
                $filename = now()->format('Ymd_His') . '_' . $field . '.' . $file->getClientOriginalExtension();
                $path = 'documents/' . $schoolSlug . '/student_images/' . $filename;             

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $admission->$field = Storage::disk('s3')->url($path);
            } else {
                $admission->$field = null;
            }
        }

        //dd($admission);

        $admission->save();

        if (strtolower((string) $request->status) === "active") {
            // Check if admission_no already exists
            $userCreated = false;
            $user = null;
            if (!$admission->roll_no) {
                // Generate a new unique admission number
                $currentYear = now()->format("Y"); // Current year
                $yearCode = substr($currentYear, -2); // Last 2 digits of the year

                // Determine class code
                $classOfJoining = strtolower($admission->std_sought);
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
                    ->where("name", $admission->student_name)
                    ->exists()
            ) {
                $user = new User();

                // Get the last ID and increment it
                $lastid = User::latest("id")->value("id") ?? 0;
                $lastid = $lastid + 1;

                $user->id = $lastid;
                $user->name = $admission->student_name ?? null;
                $user->gender = $admission->gender ?? null;
                $user->email = $admission->father_email_id ?? null;
                $user->standard = $admission->std_sought ?? null;
                $user->sec = $admission->sec ?? null;
                $user->hostelOrDay = "hostel";
                $user->password = Hash::make("Student@123");
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
                        "student_name" => $admission->student_name,
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
                        "student_name" => $admission->student_name,
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
        $id = $request->id;
        // Find the student admission record by ID
        $admission = Admission::findOrFail($id);
        Log::info("Request payload:", $request->all());

        // Update the student's basic information (non-file fields)
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
        $admission->save();

        //push notification

        $title = 'Update Application form';
        $body = 'Your Application has been successfully booked.';
        $deviceToken = 'ExponentPushToken[cPUa6ABMOmFzB8qgfZymk4]';
        $type = 'Application';
        $to_user_id = 2;
        $data = [
            'student_id' => 123,
            'date' => now()->toDateString(),
        ];

        PushNotificationController::sendPushNotification($title, $body, $deviceToken, $type, $data, $to_user_id);
        
        // Return the updated student details
        return response()->json([
            "message" => "Student admission details updated successfully!",
            "data" => [
              $admission
            ],
        ]);
    }
    public function show(Request $request,$id)
    {
        try {
            $id = $request->id;
            // Find the student admission record by ID
            $admission = Student::findOrFail($id);

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

            foreach ($imageFields as $field) {
                if (!empty($admission->$field)) {
                    $admission->$field = asset($admission->$field);
                }
            }

            return response()->json([
                "message" => "Student admission details fetched successfully!",
                "data" => $admission,
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
    public function showfromAdmission(Request $request, $id)
    {
        try {
            $id = $request->id;
            $admission = Admission::findOrFail($id);

            // $imageFields = [
            //     'profile_image',
            //     'birth_certificate_image',
            //     'aadhar_image',
            //     'ration_card_image',
            //     'community_image',
            //     'salary_image',
            //     'reference_letter_image',
            //     'transfer_certificate_image',
            //     'migration_image',
            //     'church_endorsement_image',
            // ];

            // foreach ($imageFields as $field) {
            //     if (!empty($admission->$field)) {
            //         $admission->$field = asset('storage/student_images/' . $admission->$field);
            //     }
            // }

            return response()->json([
                "message" => "Student admission details fetched successfully!",
                "data" => $admission,
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

    public function getGenderCountByStandard(Request $request)
    {
        $request->validate([
        'standard' => 'required|string'
        ]);

        $standard = $request->standard;

        $maleCount = User::where('user_type', 'student')
            ->where('standard', $standard)
            ->where('gender', 'Male')
            ->count();

        $femaleCount = User::where('user_type', 'student')
            ->where('standard', $standard)
            ->where('gender', 'Female')
            ->count();

        return response()->json([
            'standard' => $standard,
            'male' => $maleCount,
            'female' => $femaleCount
        ]);

    }

    public function downloadStudentTemplate()
    {
        $path = storage_path('app/public/student_file.xlsx');

        //dd($path);
        
        if (!file_exists($path)) {
            return response()->json(['error' => 'Template file not found'], 404);
        }

        return response()->download($path, 'student_file.xlsx');
    }

}
