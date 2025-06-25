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
            'admission_no' => 'required',
            'hostel_name'=> 'nullable',
            'treatment_type' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable',
            'nature_of_sickness' => 'nullable',
            'cost' => 'nullable',
            'father_name' => 'nullable',
            'father_number' => 'nullable',
            'mother_name' => 'nullable',
            'mother_number' => 'nullable',
            'remarks' => 'nullable',
            'student_id' => 'required',
        ]);

        // Create healthcare record
        $healthcareRecord = HealthcareRecord::create([
            'admission_no' => $requestData['admission_no'],
            'treatment_type' => $requestData['treatment_type'],
            'from_date' => $requestData['from_date'],
            'to_date' => $requestData['to_date'],
            'cost' => $requestData['cost'],
            'nature_of_sickness' => $requestData['nature_of_sickness'],
            'hostel_name' => $requestData['hostel_name'],
            'father_name' => $requestData['father_name'],
            'father_number' => $requestData['father_number'],
            'mother_name' => $requestData['mother_name'],
            'mother_number' => $requestData['mother_number'],
            'remarks' => $requestData['remarks'],
            'student_id' => $requestData['student_id'],
        ]);
        LifecycleLogger::log(
            "Healthcare Record Created",
            $requestData['admission_no'], // assuming this is same as User ID
            'healthcare_record_created',
            [
                'treatment_type' => $requestData['treatment_type'],
                'from_date' => $requestData['from_date'],
                'to_date' => $requestData['to_date'],
                'cost' => $requestData['cost'],
                'nature_of_sickness' => $requestData['nature_of_sickness'],
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
            'admission_no' => 'required',
            'hostel_name'=> 'nullable',
            'treatment_type' => 'nullable',
            'from_date' => 'nullable',
            'to_date' => 'nullable',
            'nature_of_sickness' => 'nullable',
            'cost' => 'nullable',
            'father_name' => 'nullable',
            'father_number' => 'nullable',
            'mother_name' => 'nullable',
            'mother_number' => 'nullable',
            'remarks' => 'nullable',
            'student_id' => 'required',
        ]);

        $healthcareRecord->update([
           'admission_no' => $requestData['admission_no'],
            'treatment_type' => $requestData['treatment_type'],
            'from_date' => $requestData['from_date'],
            'to_date' => $requestData['to_date'],
            'cost' => $requestData['cost'],
            'nature_of_sickness' => $requestData['nature_of_sickness'],
            'hostel_name' => $requestData['hostel_name'],
            'father_name' => $requestData['father_name'],
            'father_number' => $requestData['father_number'],
            'mother_name' => $requestData['mother_name'],
            'mother_number' => $requestData['mother_number'],
            'remarks' => $requestData['remarks'],
            'student_id' => $requestData['student_id'],
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
            $healthcareRecord->student_name = $student->student_name;
            $healthcareRecord->admission_no = $student->admission_no;
            $healthcareRecord->class = $student->std_sought;
            $healthcareRecord->section = User::where('id', $healthcareRecord->admission_no)->value('sec') ?? '';
            $healthcareRecord->father_mobile_no = $student->father_mobile_no;
            $healthcareRecord->father_name = $student->father_name;
            $healthcareRecord->mother_name = $student->mother_name;
            $healthcareRecord->mother_mobile_no = $student->mother_mobile_no;
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
                $record->student_name = $student->student_name;
                $record->admission_no = $student->admission_no;
                $record->class = $student->std_sought;
                $record->section =  User::where('id', $record->admission_no)->value('sec');
                $record->father_mobile_no = $student->father_mobile_no;
                $record->father_name = $student->father_name;
                $record->mother_name = $student->mother_name;
                $record->mother_mobile_no = $student->mother_mobile_no;
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
