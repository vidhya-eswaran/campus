<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassTeacher;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class ClassTeacherController extends Controller
{
    public function index()
    {
        $data = ClassTeacher::where("delete_status", 0) // Only fetch records where delete_status is 0
            ->get()
            ->map(function ($item) {
                $item->class_teacher = is_string($item->class_teacher)
                    ? json_decode($item->class_teacher, true)
                    : $item->class_teacher;
                $item->std_and_sub_details = is_string(
                    $item->std_and_sub_details
                )
                    ? json_decode($item->std_and_sub_details, true)
                    : $item->std_and_sub_details;
                return $item;
            });

        return response()->json($data);
    }

    // Store teacher details
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "teacher_id" => "required|integer",
            "class_teacher" => "required|array",
            // 'class_teacher.std' => 'required',
            // 'class_teacher.sec' => 'required',
            "class_teacher.group" => "nullable",
            "std_and_sub_details" => "required|array|min:1",
            "std_and_sub_details.*.std" => "required",
            "std_and_sub_details.*.sec" => "required",
            "std_and_sub_details.*.sub" => "required",
            "std_and_sub_details.group" => "nullable",
        ]);

        // Convert arrays to JSON before storing
        $teacher = ClassTeacher::create([
            "teacher_id" => $validatedData["teacher_id"],
            "class_teacher" => json_encode($validatedData["class_teacher"]),
            "std_and_sub_details" => json_encode(
                $validatedData["std_and_sub_details"]
            ),
        ]);

        return response()->json(
            [
                "message" => "Teacher details stored successfully",
                "data" => $teacher,
            ],
            201
        );
    }

    // Fetch teacher details by ID
    public function show(Request $request,$id)
    {
        $id = $request->id;
        $teacher = ClassTeacher::where("teacher_id", $id)->first();

        if (!$teacher) {
            return response()->json(["message" => "Teacher not found"], 404);
        }

        return response()->json($teacher);
    }

    // Update teacher details
    public function update(Request $request, $id)
    {
        $teacher = ClassTeacher::where("id", $id)->first();

        if (!$teacher) {
            return response()->json(["message" => "Teacher not found"], 404);
        }

        $validatedData = $request->validate([
            "class_teacher" => "nullable|array",
            "class_teacher.std" => "nullable",
            "class_teacher.sec" => "nullable",
            "class_teacher.group" => "nullable",
            "std_and_sub_details" => "nullable|array|min:1",
            "std_and_sub_details.*.std" => "required",
            "std_and_sub_details.*.sec" => "required",
            "std_and_sub_details.*.sub" => "required",
            "std_and_sub_details.*.class-teacher" => "nullable",
            "std_and_sub_details.group" => "nullable",
        ]);

        $teacher->update([
            "class_teacher" =>
                $validatedData["class_teacher"] ?? $teacher->class_teacher,
            "std_and_sub_details" =>
                $validatedData["std_and_sub_details"] ??
                $teacher->std_and_sub_details,
        ]);

        return response()->json([
            "message" => "Teacher details updated successfully",
            "data" => $teacher,
        ]);
    }

    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $teacher = ClassTeacher::where("teacher_id", $id)->first();

        if (!$teacher) {
            return response()->json(["message" => "Teacher not found"], 404);
        }

        // Update delete_status to 1 instead of deleting
        $teacher->update(["delete_status" => 1]);

        return response()->json([
            "message" => "Teacher soft deleted successfully",
        ]);
    }
}
