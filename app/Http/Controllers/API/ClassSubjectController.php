<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassSubject;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
 
use Illuminate\Support\Facades\Log;
 

class ClassSubjectController extends Controller
{
         
        //   public function viewAll()
        //     {
        //         $data = ClassSubject::all();
        //         return response()->json($data);
        //     }
        public function viewAll()
        {
            $data = ClassSubject::where('delete_status', 0)->get();
            return response()->json($data);
        }
            // View subjects by class
           public function viewByClasstwo($class, Request $request)
        {
            $query = ClassSubject::query();
        
            // Add filters based on query parameters
            if ($class) {
                $query->where('class', $class);
            }
            if ($request->query('group_no')) {
                $query->where('group_no', $request->query('group_no'));
            }
            if ($request->query('term')) {
                $query->where('term', $request->query('term'));
            }
            if ($request->query('mark')) {
                $query->where('mark', $request->query('mark'));
            }
            if ($request->query('sec')) {
                $query->where('sec', $request->query('sec'));
            }
        
            // Fetch subjects
            $subjects = $query->pluck('subject');
        
            return response()->json($subjects);
        }
        
        public function viewByClass($standard, Request $request)
        {
            // Retrieve query parameters
            $sec = $request->query('sec'); // Section filter
            $group_no = $request->query('group_no'); // Group filter
            $term = $request->query('term'); // Term filter
        
            // Fetch subjects with marks
            $subjectQuery = ClassSubject::where('class', '=', $standard);
            
            // Add additional filters for subjects
            if ($group_no) {
                $subjectQuery->where('group_no', '=', $group_no);
            }
            if ($term) {
                $subjectQuery->where('term', '=', $term);
            }
        
            $subjects = $subjectQuery->get(['id','subject', 'mark']); // Fetch subjects with marks
        
            // Fetch students
            $studentQuery = User::where('standard', '=', $standard)
                                ->where('status', '=', 1);
            
            // Add filters for students
            if ($sec) {
                $studentQuery->where('sec', '=', $sec);
            }
            if ($group_no) {
                $studentQuery->where('twe_group', '=', $group_no);
            }
        
            $students = $studentQuery->get();
        
            // Format student data
            $studentList = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'roll_no' => $student->roll_no,
                    'name' => $student->name,
                    'std' => $student->standard,
                    'concordinate_string' => $student->roll_no . ' - ' . $student->name,
                ];
            });
        
            // Prepare the final response
            $response = [
                'subjects' => $subjects, // Array of subjects with marks
                'students' => $studentList, // Array of students
            ];
        
            return response()->json($response);
        }
        
        
        
            // Insert a new class and subject SELECT `id`, `class`, `group_no`, `subject`, `term`, `acad_year`, `mark`, `sec`, `created_at`, `updated_at` FROM `class_subjects` WHERE 1
         
          public function bulkInsert(Request $request)
            {
                
                
                        $request->validate([
                            'data' => 'required|array|min:1', // Ensures at least one record is provided
                            'data.*.class' => 'required',
                            'data.*.subject' => 'required',
                            'data.*.group_no' => 'nullable',
                            'data.*.term' => 'nullable',
                            'data.*.mark' => 'nullable', // Ensures 'mark' is numeric if provided
                            'data.*.sec' => 'nullable',
                            'data.*.acad_year' => 'nullable',
                        ]);
                    
                        $insertData = collect($request->input('data'))
                            ->map(function ($item) {
                                return [
                                    'class' => $item['class'],
                                    'subject' => $item['subject'],
                                    'group_no' => $item['group_no'] ?? null,
                                    'term' => $item['term'] ?? null,
                                    'acad_year' => $item['acad_year'] ?? null,
                                    'mark' => $item['mark'] ?? null,
                                    'sec' => $item['sec'] ?? null,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            })
                            ->toArray();
                    
                        if (!empty($insertData)) {
                            ClassSubject::insert($insertData);
                        }
                    
                        return response()->json([
                            'message' => 'Classes and subjects added successfully',
                            'data' => $insertData,
                        ], 201);
            }
        
    public function updateBulk_old(Request $request)
    {
        $request->validate([
            'data' => 'required|array|min:1',
            'data.*.id' => 'required|exists:class_subjects,id',
            'data.*.class' => 'nullable|string|max:255',
            'data.*.subject' => 'nullable|string|max:255',
            'data.*.group_no' => 'nullable|string|max:255',
            'data.*.term' => 'nullable|string|max:255',
            'data.*.mark' => 'nullable|numeric',
            'data.*.sec' => 'nullable|string|max:255',
            'data.*.acad_year' => 'nullable|string|max:255',
        ]);
    
        $data = $request->input('data');
    
        // Fetch all records in one query
        $ids = array_column($data, 'id');
        $classSubjects = ClassSubject::whereIn('id', $ids)->get()->keyBy('id');
    
        $updatedData = [];
    
        foreach ($data as $item) {
            if (isset($classSubjects[$item['id']])) {
                $classSubject = $classSubjects[$item['id']];
                $classSubject->update([
                    'class' => $item['class'] ?? $classSubject->class,
                    'subject' => $item['subject'] ?? $classSubject->subject,
                    'group_no' => $item['group_no'] ?? $classSubject->group_no,
                    'term' => $item['term'] ?? $classSubject->term,
                    'acad_year' => $item['acad_year'] ?? $classSubject->acad_year,
                    'mark' => $item['mark'] ?? $classSubject->mark,
                    'sec' => $item['sec'] ?? $classSubject->sec,
                    'updated_at' => now(),
                ]);
                $updatedData[] = $classSubject;
            }
        }
    
        return response()->json([
            'message' => 'Classes and subjects updated successfully',
            'data' => $updatedData,
        ]);
    }
    
    public function updateBulk_OLLD(Request $request)
    {
        // Convert request data to expected format
        $convertedData = ['data' => $request->all()];
    
        // Validate the converted data
        $validatedData = Validator::make($convertedData, [
            'data' => 'required|array|min:1',
            'data.*.id' => 'required|exists:class_subjects,id',
            'data.*.subject' => 'required|string|max:255',
            'data.*.mark' => 'required|numeric|min:0|max:100', // Ensure mark is numeric and within range
        ])->validate();
    
        // Process and update records
        $updatedData = [];
        foreach ($validatedData['data'] as $subjectData) {
            $classSubject = ClassSubject::find($subjectData['id']);
    
            if ($classSubject) {
                $classSubject->update([
                    'subject' => $subjectData['subject'],
                    'mark' => $subjectData['mark']
                ]);
                $updatedData[] = $classSubject->toArray(); // Convert to array for JSON response
            }
        }
    
        return response()->json([
            'message' => 'Classes and subjects updated successfully',
            'data' => $updatedData, // Return updated records
        ]);
    }
public function updateBulk(Request $request)
{
 
    try {
        // Validate input request
        $validatedData = $request->validate([
            'data' => 'required', // Ensures at least one record is provided
            'data.*.class' => 'required',
            'data.*.subject' => 'required',
            'data.*.group_no' => 'nullable',
            'data.*.term' => 'required',
            'data.*.mark' => 'nullable',
            'data.*.acad_year' => 'required',
        ]);
         // Extract filter criteria from the first item in the payload
        $firstItem = $validatedData['data'][0];

        // Delete existing records matching the criteria
        $deleteQuery = ClassSubject::where('class', $firstItem['class'])
            ->where('term', $firstItem['term'])
            ->where('acad_year', $firstItem['acad_year']);

        // If group_no is empty or null, handle both cases
        if (empty($firstItem['group_no'])) {
            $deleteQuery->whereNull('group_no');
        } else {
            $deleteQuery->where('group_no', $firstItem['group_no']);
        }

        $deletedRows = $deleteQuery->delete();

        // Log deleted rows for debugging
        \Log::info("Deleted $deletedRows records matching criteria.");
 
        // Prepare new data for insertion
        $insertData = collect($validatedData['data'])->map(function ($item) {
            return [
                'class' => $item['class'],
                'subject' => $item['subject'],
                'group_no' => $item['group_no'] ?? null,
                'term' => $item['term'],
                'mark' => $item['mark'] ?? null,
                'acad_year' => $item['acad_year'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Insert new records
        ClassSubject::insert($insertData);

        return response()->json([
            'message' => "Records updated successfully.",
            'data' => $insertData,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while updating records.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



        
            // Delete a class-subject entry by ID
            public function delete_old($id)
            {
                $classSubject = ClassSubject::find($id);
        
                if (!$classSubject) {
                    return response()->json(['message' => 'Class-Subject not found'], 404);
                }
        
                $classSubject->delete();
        
                return response()->json(['message' => 'Class-Subject deleted successfully']);
            }
            public function deleteoldtwo(Request $request)
            {
                // Convert request data to expected format
                $convertedData = ['data' => $request->all()];
            
                // Validate the request
                $validatedData = Validator::make($convertedData, [
                    'data' => 'required|array|min:1',
                    'data.*.id' => 'required|exists:class_subjects,id',
                ])->validate();
            
                // Process and delete records
                $deletedIds = [];
                foreach ($validatedData['data'] as $subjectData) {
                    $classSubject = ClassSubject::find($subjectData['id']);
            
                    if ($classSubject) {
                        $classSubject->delete();
                        $deletedIds[] = $subjectData['id']; // Store deleted IDs
                    }
                }
            
                return response()->json([
                    'message' => 'Classes and subjects deleted successfully',
                    'deleted_ids' => $deletedIds, // Return deleted IDs
                ]);
            }
            
            // public function delete(Request $request)
            // {
            //     try {
            //         // Validate input request
            //         $validatedData = $request->validate([
            //             'data' => 'required|array', // Ensures an array of data is provided
            //             'data.*.id' => 'required|integer' // Ensure ID is provided and valid
            //         ]);
            
            //         // Extract all IDs to delete
            //         $idsToDelete = collect($validatedData['data'])->pluck('id')->toArray();
            
            //         // Log IDs before deletion for debugging
            //         \Log::info("Attempting to delete records with IDs: " . implode(',', $idsToDelete));
            
            //         // Ensure records exist before deletion
            //         $existingRecords = ClassSubject::whereIn('id', $idsToDelete)->pluck('id')->toArray();
            
            //         if (empty($existingRecords)) {
            //             return response()->json([
            //                 'message' => "No matching records found for deletion.",
            //                 'deleted_count' => 0
            //             ], 404);
            //         }
            
            //         // Delete records based on ID
            //         $deletedRows = ClassSubject::whereIn('id', $existingRecords)->delete();
            
            //         // Log deleted IDs for debugging
            //         \Log::info("Deleted $deletedRows records with IDs: " . implode(',', $existingRecords));
            
            //         return response()->json([
            //             'message' => "Records deleted successfully.",
            //             'deleted_count' => $deletedRows,
            //         ], 200);
            //     } catch (\Exception $e) {
            //         // Log error for debugging
            //         \Log::error("Error deleting records: " . $e->getMessage());
            
            //         return response()->json([
            //             'message' => 'An error occurred while deleting records.',
            //             'error' => $e->getMessage(),
            //         ], 500);
            //     }
            // }
            public function delete(Request $request)
            {
                try {
                    // Validate input request
                    $validatedData = $request->validate([
                        'data' => 'required|array', // Ensures an array of data is provided
                        'data.*.id' => 'required|integer' // Ensure ID is provided and valid
                    ]);
            
                    // Extract all IDs to update
                    $idsToUpdate = collect($validatedData['data'])->pluck('id')->toArray();
            
                    // Log IDs before update for debugging
                    \Log::info("Attempting to update delete_status for IDs: " . implode(',', $idsToUpdate));
            
                    // Ensure records exist before updating
                    $existingRecords = ClassSubject::whereIn('id', $idsToUpdate)->pluck('id')->toArray();
            
                    if (empty($existingRecords)) {
                        return response()->json([
                            'message' => "No matching records found for deletion.",
                            'updated_count' => 0
                        ], 404);
                    }
            
                    // Update delete_status to 1 instead of deleting records
                    $updatedRows = ClassSubject::whereIn('id', $existingRecords)->update(['delete_status' => 1]);
            
                    // Log updated IDs for debugging
                    \Log::info("Updated delete_status for $updatedRows records with IDs: " . implode(',', $existingRecords));
            
                    return response()->json([
                        'message' => "Records soft deleted successfully.",
                        'updated_count' => $updatedRows,
                    ], 200);
                } catch (\Exception $e) {
                    // Log error for debugging
                    \Log::error("Error updating delete_status: " . $e->getMessage());
            
                    return response()->json([
                        'message' => 'An error occurred while updating records.',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

           
}
