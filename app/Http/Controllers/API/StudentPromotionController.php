<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserGradeHistory;
use App\Models\AdmissionForm;
use Illuminate\Support\Facades\Log;
use App\Helpers\LifecycleLogger;  




class StudentPromotionController extends Controller
{

    public function index()
    {
        dd('eeeeee');
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'student added successfully',

        // ]);
    }
    public function update(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'promotion_data' => 'nullable|array',
                'promotion_data.*.id' => 'integer|exists:users,id',
                'promotion_data.*.std' => 'string',
                'promotion_data.*.sec' => 'string',
                'promotion_data.*.previous_academic_year' => 'string',
                'promotion_data.*.current_academic_year' => 'string',
                'promotion_data.*.grade_status' => 'string',
    
                'detention_data' => 'nullable|array', // Detention data is optional
                'detention_data.*.id' => 'integer|exists:users,id',
                'detention_data.*.std' => 'string',
                'detention_data.*.sec' => 'string',
                'detention_data.*.previous_academic_year' => 'string',
                'detention_data.*.current_academic_year' => 'string',
                'detention_data.*.grade_status' => 'string',
            ]);
    
            // Merge both promotion & detention data (detention_data may be empty)
            $allStudents = array_merge(
                $validatedData['promotion_data'] ?? [],
                $validatedData['detention_data'] ?? [] // Use empty array if detention_data is not provided
            );
    
            foreach ($allStudents as $student) {
                $existingUser = User::find($student['id']); // Fetch single row based on ID
    
                if ($existingUser) {
                    // Store history before updating
                    UserGradeHistory::create([
                        'student_id' => $existingUser->id,
                        'admission_no' => $existingUser->admission_no,
                        'previous_standard' => $existingUser->standard,
                        'previous_sec' => $existingUser->sec,
                        'previous_grade_status' => $existingUser->grade_status,
                        'new_standard' => $student['std'],
                        'new_sec' => $student['sec'],
                        'new_grade_status' => $student['grade_status'],
                        'previous_academic_year' => $student['previous_academic_year'],
                        'current_academic_year' => $student['current_academic_year'],
                    ]);
    
                    // Update Student table
                    $existingStudent = Student::where('admission_no', $existingUser->admission_no)->first();
                    if ($existingStudent) {
                        $existingStudent->std_sought  = $student['std'];
                        $existingStudent->sec  = $student['sec'];
                        $existingStudent->academic_year  = $student['current_academic_year'];
                        $existingStudent->grade_status = $student['grade_status'];
                        $existingStudent->save();
                    }
    
                    // Update User table
                    $existingUser->update([
                        'standard' => $student['std'],
                        'sec' => $student['sec'],
                        'academic_year'  => $student['current_academic_year'],
                        'grade_status' => $student['grade_status'],
                    ]);
                                try {
                                          // Log the lifecycle event
                                            LifecycleLogger::log(
                                                "Student Grade Updated to {$student['std']} ({$student['grade_status']})",
                                                $existingUser->id,
                                                'grade_update',
                                                [
                                                    'previous_standard' => $existingUser->standard,
                                                    'new_standard' => $student['std'],
                                                    'previous_sec' => $existingUser->sec,
                                                    'new_sec' => $student['sec'],
                                                    'previous_grade_status' => $existingUser->grade_status,
                                                    'new_grade_status' => $student['grade_status'],
                                                    'previous_academic_year' => $student['previous_academic_year'],
                                                    'current_academic_year' => $student['current_academic_year'],
                                                ]
                                            );
                                 } catch (\Exception $e) {
                                                                    // Log the error in case of any issues
                                        Log::error('Error occurred while logging lifecycle event', [
                                            'error_message' => $e->getMessage(),
                                            'student_id' => $existingUser->id,
                                            'event_type' => 'grade_update',
                                            'extra' => [
                                                'previous_standard' => $existingUser->standard,
                                                'new_standard' => $student['std'],
                                                'previous_sec' => $existingUser->sec,
                                                'new_sec' => $student['sec'],
                                                'previous_grade_status' => $existingUser->grade_status,
                                                'new_grade_status' => $student['grade_status'],
                                            ]
                                        ]);
                                           }

                }
            }
    
            return response()->json([
                'message' => 'Users updated successfully',
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors()); // this will show validation errors
        }
    }
    public function moveToDetention(Request $request)
    {
       // dd($request->all());
        try {
            $validatedData = $request->validate([
                'student_details' => 'nullable|array', // Detention data is optional
                'student_details.*.id' => 'integer|exists:users,id',
                'student_details.*.std' => 'nullable',
                'student_details.*.sec' => 'nullable|string',
                'student_details.*.group' => 'nullable|string',
                'student_details.*.previous_academic_year' => 'nullable|string',
                'student_details.*.grade_status' => 'nullable|string',
            ]);
            
            if (!empty($validatedData['student_details'])) {
                foreach ($validatedData['student_details'] as $history) {
                    // Fetch the existing user
                    $existingUser = User::find($history['id']);
            
                    if ($existingUser) {
                        // Filter out null values before updating
                        $userData = array_filter([
                            'standard' => $history['std'] ?? null,
                            'sec' => $history['sec'] ?? null,
                            'twe_group' => $history['group'] ?? null,
                            'academic_year' => $history['previous_academic_year'] ?? null,
                            'grade_status' => $history['grade_status'] ?? null,
                        ], fn($value) => !is_null($value)); // Remove null values
            
                        if (!empty($userData)) {
                            $existingUser->update($userData);
                        }
            
                        // Update Student table if applicable
                        $existingStudent = Student::where('admission_no', $existingUser->admission_no)->first();
                        if ($existingStudent) {
                            $studentData = array_filter([
                                'std_sought' => $history['std'] ?? null,
                                'sec' => $history['sec'] ?? null,
                                'group_first_choice' => $history['group'] ?? null,
                                'academic_year' => $history['previous_academic_year'] ?? null,
                                'grade_status' => $history['grade_status'] ?? null,
                            ], fn($value) => !is_null($value)); // Remove null values
            
                            if (!empty($studentData)) {
                                $existingStudent->update($studentData);
                                                                                    try {
                                                                LifecycleLogger::log(
                                                                    "Student Grade Updated to {$history['std']} ({$history['grade_status']})",
                                                                    $existingStudent->user_id ?? null,
                                                                    'grade_update',
                                                                    [
                                                                        'previous_standard' => $existingUser->standard ?? null,
                                                                        'new_standard' => $history['std'] ?? null,
                                                                        'previous_sec' => $existingUser->sec ?? null,
                                                                        'new_sec' => $history['sec'] ?? null,
                                                                        'previous_grade_status' => $existingUser->grade_status ?? null,
                                                                        'new_grade_status' => $history['grade_status'] ?? null,
                                                                        'previous_academic_year' => $history['previous_academic_year'] ?? null,
                                                                        'current_academic_year' => $history['current_academic_year'] ?? null,
                                                                    ]
                                                                );
                                                            } catch (\Exception $e) {
                                                                \Log::error('LifecycleLogger failed during student update.', [
                                                                    'message' => $e->getMessage(),
                                                                    'student_id' => $existingStudent->user_id ?? null,
                                                                    'context' => $history
                                                                ]);
                                                            }
                            }
                        }
                    }
                }
            }
            
            return response()->json([
                'message' => 'Students data updated successfully',
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors()); // this will show validation errors
        }
    }

}
