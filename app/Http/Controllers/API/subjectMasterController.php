<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Subject;

class subjectMasterController extends Controller
{
    public function index()
    {
        $terms = Subject::where("delete_status", 0)->get(); // Fetch only non-deleted records
        return response()->json($terms);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
        ]);

        $term = Subject::create($validated);
        return response()->json(
            ["message" => "Subject created successfully", "Subject" => $term],
            201
        );
    }

    public function update(Request $request, $id)
    {
         $id = $request->id;
        // dd($id);
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json(["message" => "Not Found"], 404);
        }

        $validated = $request->validate([
            "name" => "required",
        ]);

        $subject->update($validated);
        return response()->json([
            "message" => "Subject updated",
            "data" => $subject,
        ]);
    }
    public function show(Request $request,$id)
    {
         $id = $request->id;
        // dd($id);
        $subject = Subject::findOrFail($id);
        if (!$subject) {
            return response()->json(["message" => "Not Found"], 404);
        }
        return response()->json($subject);
    }

    public function destroy(Request $request,$id)
    {
         $id = $request->id;
        $subject = Subject::findOrFail($id);

        // Update delete_status to 1 instead of deleting
        $subject->update(["delete_status" => 1]);

        return response()->json(["message" => "Subject deleted successfully"]);
    }
}
