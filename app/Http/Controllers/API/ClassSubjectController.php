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

            $query->where('delete_status', 0);
        
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
        
        public function viewByClass(Request $request)
        {
            // Retrieve query parameters
            $sec = $request->query('sec'); // Section filter
            $group_no = $request->query('group_no'); // Group filter
            $term = $request->query('term'); // Term filter
            $standard = $request->query('class'); 
        
            // Fetch subjects with marks
            $subjectQuery = ClassSubject::where('class', '=', $standard)->where('delete_status', 0);
            
            if ($group_no) {
                $subjectQuery->where('group_no', '=', $group_no);
            }
            if ($term) {
                $subjectQuery->where('term', '=', $term);
            }
        
            $subjects = $subjectQuery->get(['id','subject', 'mark']); // Fetch subjects with marks
       
            //dd($subjects);

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
        $validated = $request->validate([
            '*.class' => 'required',
            '*.subject' => 'required',
            '*.group_no' => 'nullable',
            '*.term' => 'nullable',
            '*.mark' => 'nullable',
            '*.sec' => 'nullable',
            '*.acad_year' => 'nullable',
        ]);

        $insertData = collect($validated)->map(function ($item) {
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
        })->toArray();

        ClassSubject::insert($insertData);

        return response()->json([
            'message' => 'Classes and subjects added successfully',
            'data' => $insertData,
        ], 201);
    }

        
    public function updateBulk(Request $request)
    {
        try {
            // Validate the top-level array like insert function
            $validated = $request->validate([
                '*.class' => 'required',
                '*.subject' => 'required',
                '*.group_no' => 'nullable',
                '*.term' => 'nullable',
                '*.mark' => 'nullable',
                '*.sec' => 'nullable',
                '*.acad_year' => 'nullable',
            ]);

            // Use first item to determine delete condition
            $firstItem = $validated[0];

            $deleteQuery = ClassSubject::where('class', $firstItem['class'])
                ->where('term', $firstItem['term'])
                ->where('acad_year', $firstItem['acad_year']);

            if (empty($firstItem['group_no'])) {
                $deleteQuery->whereNull('group_no');
            } else {
                $deleteQuery->where('group_no', $firstItem['group_no']);
            }

            $deleted = $deleteQuery->delete();
            \Log::info("Deleted $deleted records for updateBulk.");

            // Prepare new records
            $insertData = collect($validated)->map(function ($item) {
                return [
                    'class' => $item['class'],
                    'subject' => $item['subject'],
                    'group_no' => $item['group_no'] ?? null,
                    'term' => $item['term'] ?? null,
                    'mark' => $item['mark'] ?? null,
                    'sec' => $item['sec'] ?? null,
                    'acad_year' => $item['acad_year'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Insert new records
            ClassSubject::insert($insertData);

            return response()->json([
                'message' => 'Records updated successfully.',
                'data' => $insertData,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating records.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    public function delete(Request $request)
    {
        try {
            // Validate top-level array format
            $validated = $request->validate([
                '*.id' => 'required|integer'
            ]);

            // Collect all IDs to be soft-deleted
            $idsToUpdate = collect($validated)->pluck('id')->toArray();

            \Log::info("Attempting to soft delete records with IDs: " . implode(',', $idsToUpdate));

            // Check if records exist
            $existingRecords = ClassSubject::whereIn('id', $idsToUpdate)->pluck('id')->toArray();

            if (empty($existingRecords)) {
                return response()->json([
                    'message' => "No matching records found to delete.",
                    'updated_count' => 0
                ], 404);
            }

            // Perform soft delete by updating `delete_status`
            $updatedRows = ClassSubject::whereIn('id', $existingRecords)->update(['delete_status' => 1]);

            \Log::info("Soft deleted $updatedRows records with IDs: " . implode(',', $existingRecords));

            return response()->json([
                'message' => "Records soft deleted successfully.",
                'updated_count' => $updatedRows,
            ], 200);

        } catch (\Exception $e) {
            \Log::error("Soft delete error: " . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while soft deleting records.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


           
}
