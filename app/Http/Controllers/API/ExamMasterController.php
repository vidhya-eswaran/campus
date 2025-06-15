<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamMaster;
use App\Models\ClassSubjectMapping;

class ExamMasterController extends Controller
{
    // Get all non-deleted exam records
  public function index(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
            'class' => 'required|string',
        ]);

        $standards = ExamMaster::where('delete_status', 0)
            ->where('term', $validated['term'])
            ->where('class', $validated['class'])
            ->get();

        return response()->json($standards);
    }

    public function addList(Request $request,$id)
    {
        $exams =  ClassSubjectMapping::where("class", $id)->first();
        return response()->json(['message' => 'Exam records fetched successfully', 'data' => $exams]);
    }

    // Create a new exam record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
            'class' => 'required|string',
            'subjects_mark' => 'required|array',
            'total' => 'required|string',
        ]);

        $exam = ExamMaster::create([
            'term' => $validated['term'],
            'class' => $validated['class'],
            'subjects_mark' => json_encode($validated['subjects_mark']),
            'total' => $validated['total'],
            'delete_status' => 0,
        ]);

        return response()->json(['message' => 'Exam record created successfully', 'data' => $exam], 201);
    }

    // Update an existing exam record
    public function update(Request $request, $id)
    {
        $exam = ExamMaster::findOrFail($id);

        $validated = $request->validate([
            'term' => 'required|string',
            'class' => 'required|string',
            'subjects_mark' => 'required|array',
            'total' => 'required|string',
        ]);

        $exam->update([
            'term' => $validated['term'],
            'class' => $validated['class'],
            'subjects_mark' => json_encode($validated['subjects_mark']),
            'total' => $validated['total'],
        ]);

        return response()->json(['message' => 'Exam record updated successfully', 'data' => $exam]);
    }

    // Get exam record by ID
    public function viewbyid($id)
    {
        $exam = ExamMaster::findOrFail($id);
        return response()->json(['message' => 'Exam record fetched successfully', 'data' => $exam]);
    }

    // Soft delete (set delete_status = 1)
    public function destroy($id)
    {
        $exam = ExamMaster::findOrFail($id);
        $exam->delete_status = 1;
        $exam->save();
        return response()->json(['message' => 'Exam record deleted successfully']);
    }
}

