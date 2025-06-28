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
        $id = $request->id;
        $exams =  ClassSubjectMapping::where("class", $id)->first();
        return response()->json(['message' => 'Exam records fetched successfully', 'data' => $exams]);
    }

    // Create a new exam record
    public function store(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string',
            'class' => 'required|string',
            'subject' => 'required|array',
            'mark' => 'required|string',
            'group_no' => 'nullable',
            'acad_year' => 'nullable',

        ]);

        $exam = ExamMaster::create([
            'term' => $validated['term'],
            'class' => $validated['class'],
            'subject' => json_encode($validated['subject']),
            'mark' => $validated['mark'],
            'group_no' => $validated['group_no'],
            'acad_year' => $validated['acad_year'],
            'delete_status' => 0,
        ]);

        return response()->json(['message' => 'Exam record created successfully', 'data' => $exam], 201);
    }

    // Update an existing exam record
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $exam = ExamMaster::findOrFail($id);

        $validated = $request->validate([
             'term' => 'required|string',
            'class' => 'required|string',
            'subject' => 'required|array',
            'mark' => 'required|string',
            'group_no' => 'nullable',
            'acad_year' => 'nullable',
        ]);

        $exam->update([
           'term' => $validated['term'],
            'class' => $validated['class'],
            'subject' => json_encode($validated['subject']),
            'mark' => $validated['mark'],
            'group_no' => $validated['group_no'],
            'acad_year' => $validated['acad_year'],
        ]);

        return response()->json(['message' => 'Exam record updated successfully', 'data' => $exam]);
    }

    // Get exam record by ID
    public function viewbyid(Request $request, $id)
    {
        $id = $request->id;
        $exam = ExamMaster::findOrFail($id);
        return response()->json(['message' => 'Exam record fetched successfully', 'data' => $exam]);
    }

    // Soft delete (set delete_status = 1)
    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        $exam = ExamMaster::findOrFail($id);
        $exam->delete_status = 1;
        $exam->save();
        return response()->json(['message' => 'Exam record deleted successfully']);
    }
}

