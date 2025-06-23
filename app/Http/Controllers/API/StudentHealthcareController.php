<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthcareRecord;
use App\Models\Student;
use App\Models\User;
use App\Helpers\LifecycleLogger;

class StudentHealthcareController extends Controller
{
    // Add healthcare record
    public function addHealthcareRecord(Request $request)
    {
        $requestData = $request->validate([
            'admissionNo' => 'required',
            'hostel_name'=> 'nullable',
            'treatmentType' => 'nullable',
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
            'natureOfSkiness' => 'nullable',
            'cost' => 'nullable',
            'fatherName' => 'nullable',
            'fatherNumber' => 'nullable',
            'motherName' => 'nullable',
            'motherNumber' => 'nullable',
        ]);

        // Create healthcare record
        $healthcareRecord = HealthcareRecord::create([
            'admission_no' => $requestData['admissionNo'],
            'treatment_type' => $requestData['treatmentType'],
            'from_date' => $requestData['fromDate'],
            'to_date' => $requestData['toDate'],
            'cost' => $requestData['cost'],
            'nature_of_sickness' => $requestData['natureOfSkiness'],
            'hostel_name' => $requestData['hostel_name'],
            'father_name' => $requestData['fatherName'],
            'father_number' => $requestData['fatherNumber'],
            'mother_name' => $requestData['motherName'],
            'mother_number' => $requestData['motherNumber'],
        ]);
        LifecycleLogger::log(
            "Healthcare Record Created",
            $requestData['admissionNo'], // assuming this is same as User ID
            'healthcare_record_created',
            [
                'treatment_type' => $requestData['treatmentType'],
                'from_date' => $requestData['fromDate'],
                'to_date' => $requestData['toDate'],
                'cost' => $requestData['cost'],
                'nature_of_sickness' => $requestData['natureOfSkiness'],
                'hostel_name' => $requestData['hostel_name'],
            ]
        );
        return response()->json(['message' => 'Healthcare record added successfully!', 'healthcare_record' => $healthcareRecord], 201);
    }

    // Edit healthcare record
    public function editHealthcareRecord(Request $request, $id)
    {
         $id = $request->id;
        $healthcareRecord = HealthcareRecord::find($id);

        if (!$healthcareRecord) {
            return response()->json(['message' => 'Healthcare record not found'], 404);
        }

        $requestData = $request->validate([
            'admissionNo' => 'required',
            'hostel_name'=> 'nullable',
            'treatmentType' => 'nullable',
            'fromDate' => 'nullable',
            'toDate' => 'nullable',
            'natureOfSkiness' => 'nullable',
            'cost' => 'nullable',
            'fatherName' => 'nullable',
            'fatherNumber' => 'nullable',
            'motherName' => 'nullable',
            'motherNumber' => 'nullable',
        ]);

        $healthcareRecord->update([
           'admission_no' => $requestData['admissionNo'],
            'treatment_type' => $requestData['treatmentType'],
            'from_date' => $requestData['fromDate'],
            'to_date' => $requestData['toDate'],
            'cost' => $requestData['cost'],
            'nature_of_sickness' => $requestData['natureOfSkiness'],
            'hostel_name' => $requestData['hostel_name'],
            'father_name' => $requestData['fatherName'],
            'father_number' => $requestData['fatherNumber'],
            'mother_name' => $requestData['motherName'],
            'mother_number' => $requestData['motherNumber'],
        ]);

        return response()->json(['message' => 'Healthcare record updated successfully!', 'healthcare_record' => $healthcareRecord], 200);
    }

    // View healthcare record by ID
  public function viewHealthcareRecord(Request $request,$id)
    {
         $id = $request->id;
        // Fetch the healthcare record by ID
        $healthcareRecord = HealthcareRecord::find($id);

        // Check if the healthcare record exists
        if (!$healthcareRecord) {
            return response()->json(['message' => 'Healthcare record not found'], 404);
        }
        $roll_no= User::where('admission_no', $healthcareRecord->admission_no)->value('roll_no');
        // Fetch the student details related to this healthcare record
        $student = Student::where('roll_no',$roll_no)->first();
        // dd($roll_no);
        // If student is found, add student details to healthcare record
        if ($student) {
            $healthcareRecord->studentName = $student->STUDENT_NAME;
            $healthcareRecord->admissionNo = $student->admission_no;
            $healthcareRecord->class = $student->SOUGHT_STD;
            $healthcareRecord->section = User::where('id', $healthcareRecord->admission_no)->value('sec') ?? '';
            $healthcareRecord->father_mobile_no = $student->MOBILE_NUMBER;
            $healthcareRecord->father_name = $student->FATHER;
            $healthcareRecord->mother_name = $student->MOTHER;
            $healthcareRecord->mother_mobile_no = $student->WHATS_APP_NO;
            $healthcareRecord->roll_no = $student->roll_no;


        } else {
            // If no student found, add empty or default values
            $healthcareRecord->studentName = null;
            $healthcareRecord->admissionNo = null;
            $healthcareRecord->class = null;
            $healthcareRecord->section = null;
            $healthcareRecord->father_mobile_no =null;
            $healthcareRecord->father_name = null;
            $healthcareRecord->mother_name = null;
            $healthcareRecord->mother_mobile_no =null;
            $healthcareRecord->roll_no = null;

        }

        // Return the healthcare record with student details
        return response()->json(['healthcare_record' => $healthcareRecord], 200);
    }

    // View all healthcare records
  public function viewAllHealthcareRecords()
    {
        // Fetch all healthcare records
        $healthcareRecords = HealthcareRecord::where('delete_status', 0)->get();

        // Add student details for each healthcare record
        $healthcareRecordsWithStudentDetails = $healthcareRecords->map(function ($record) {
            // Fetch the student by admission_no
            $roll_no= User::where('admission_no', $record->admission_no)->value('roll_no');

            $student = Student::where('roll_no',$roll_no)->first();
        // Fetch the student details related to this healthcare record
            // If student is found, merge the student details into the record
            if ($student) {
                $record->studentName = $student->STUDENT_NAME;
                $record->admissionNo = $student->admission_no;
                $record->class = $student->SOUGHT_STD;
                $record->section =  User::where('id', $record->admission_no)->value('sec');
                $record->father_mobile_no = $student->MOBILE_NUMBER;
                $record->father_name = $student->FATHER;
                $record->mother_name = $student->MOTHER;
                $record->mother_mobile_no = $student->WHATS_APP_NO;
                $record->roll_no = $student->roll_no;
            } else {
                // If no student found, add empty or default values
                $record->studentName = null;
                $record->admissionNo = null;
                $record->class = null;
                $record->section = null;
                $record->father_mobile_no =null;
                $record->father_name = null;
                $record->mother_name = null;
                $record->mother_mobile_no =null;
                $record->roll_no = null;

            }

            return $record;
        });

        // Return healthcare records with student details
        return response()->json(['healthcare_records' => $healthcareRecordsWithStudentDetails], 200);
    }

     public function destroy(Request $request, $id)
    {
         $id = $request->id;
        $donor = HealthcareRecord::findOrFail($id);
        $donor->delete_status = 1;
        $donor->save();
        return response()->json([
            'message' => 'healthcare Record deleted successfully',
        ]);
    }

}
