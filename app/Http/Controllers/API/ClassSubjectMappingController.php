<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSubject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
 use App\Models\ClassSubjectMapping;
use Illuminate\Support\Facades\Log;
 

class ClassSubjectMappingController extends Controller
{
  
 
  // 1️⃣ Bulk Insert Subjects for a Class (With Group No)
 public function bulkInsert(Request $request)
{
    $request->validate([
        'class' => 'required',
        'group_no' => 'nullable',
        'subjects' => 'required|array|min:1',
        'subjects.*' => 'required|string|max:255',
    ]);

    // Prepare data for insertion
    $data = [
        'class' => $request->class,
        'group_no' => $request->group_no,
        'subjects' => json_encode($request->subjects), // Store subjects as JSON
        'created_at' => now(),
        'updated_at' => now(),
    ];

    // Insert data into the database
    ClassSubjectMapping::insert([$data]);

    return response()->json([
        'message' => 'Subjects mapped successfully',
        'data' => $data
    ], 201);
}


 // 2️⃣ Get All Mappings (Including Group No)
// public function getAll()
// {
//     // Fetch all data
//     $mappings = ClassSubjectMapping::all();

//     // Decode 'subjects' JSON field if needed (optional)
//     foreach ($mappings as $mapping) {
//         $mapping->subjects = json_decode($mapping->subjects, true); // Decoding JSON to Array
//     }

//     return response()->json($mappings);
// }
public function getAll()
{
    // Fetch only records where delete_status is 0 (not deleted)
    $mappings = ClassSubjectMapping::where('delete_status', 0)->get();

    // Decode 'subjects' JSON field if needed (optional)
    foreach ($mappings as $mapping) {
        $mapping->subjects = json_decode($mapping->subjects, true); // Decoding JSON to Array
    }

    return response()->json($mappings);
}


// 3️⃣ Get Subjects by Class (With Group No)
public function getByClass($class, Request $request)
{
    $group_no = $request->group_no;

    // Start the query with the class condition
    $subjects = ClassSubjectMapping::where('class', $class);

    // If group_no is provided, apply the filter for group_no
    if ($group_no) {
        $subjects = $subjects->where('group_no', $group_no);
    }

    // Get the subjects with the required columns
    $subjects = $subjects->get(['subjects', 'group_no']);

    // Optionally decode 'subjects' JSON field if it's stored as JSON
    foreach ($subjects as $subject) {
        $subject->subject = json_decode($subject->subjects, true); // Decoding JSON to Array if needed
    }

    return response()->json([
        'class' => $class,
        'subjects' => $subjects
    ]);
}

// 4️⃣ Update Subjects for a Class (With Group No)
public function updateSubjects(Request $request)
{
    $request->validate([
        'class' => 'required',
        'group_no' => 'nullable',
        'subjects' => 'required|array|min:1',
        'subjects.*' => 'required|string|max:255',
    ]);

    // Delete existing subjects for this class
    ClassSubjectMapping::where('class', $request->class)->delete();

    // Prepare the subjects data as JSON
    $data = [
        'class' => $request->class,
        'group_no' => $request->group_no,
        'subjects' => json_encode($request->subjects),  // Encoding array to JSON
        'created_at' => now(),
        'updated_at' => now(),
    ];

    // Insert the new subjects as a JSON-encoded array
    ClassSubjectMapping::insert([$data]);

    return response()->json([
        'message' => 'Subjects updated successfully',
        'data' => $data
    ]);
}

// 5️⃣ Delete Subjects for a Class
// public function deleteSubjects($class)
// {
//     ClassSubjectMapping::where('id', $class)->delete();

//     return response()->json([
//         'message' => "Subjects for class '$class' deleted successfully"
//     ]);
// }
public function deleteSubjects($class)
{
    // Update delete_status to 1 instead of deleting the record
    $updatedRows = ClassSubjectMapping::where('id', $class)->update(['delete_status' => 1]);

    if ($updatedRows === 0) {
        return response()->json([
            'message' => "No matching record found for class '$class'."
        ], 404);
    }

    return response()->json([
        'message' => "Subjects for class '$class' marked as deleted successfully."
    ]);
}


}
