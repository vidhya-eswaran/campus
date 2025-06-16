<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Teachertype;

class TeachertypeMasterController extends Controller
{
    public function index()
    {
        $terms = Teachertype::where("delete_status", 0)->get(); // Fetch only non-deleted records
        return response()->json($terms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "teacherType" => "required",
        ]);

        $teachertype = Teachertype::create([
            "teacher_type" => $validated["teacherType"],
        ]);

        return response()->json(
            [
                "message" => "Teacher type created successfully",
                "Teachertype" => $teachertype,
            ],
            201
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "teacherType" => "required",
        ]);

        $term = Teachertype::findOrFail($id);
        $term->update(["teacher_type" => $validated["teacherType"]]);

        return response()->json([
            "message" => "Teacher type updated successfully",
            "Subject" => $term,
        ]);
    }
    public function viewbyid($id)
    {
        $term = Teachertype::findOrFail($id);

        return response()->json([
            "message" => "Teacher type got successfully",
            "Subject" => $term,
        ]);
    }

    public function destroy($id)
    {
        $subject = Teachertype::findOrFail($id);

        // Update delete_status to 1 instead of deleting
        $subject->update(["delete_status" => 1]);

        return response()->json([
            "message" => "Teacher type deleted successfully",
        ]);
    }
}
