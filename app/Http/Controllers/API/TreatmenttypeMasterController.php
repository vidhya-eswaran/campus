<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Teachertype;

class TeachertypeMasterController extends Controller
{
 
//   public function index()
//     {
//         $terms = Subject::all();
//         return response()->json($terms);
//     }
    public function index()
    {
        $terms = Teachertype::where('delete_status', 0)->get(); // Fetch only non-deleted records
        return response()->json($terms);
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_type' => 'required',
        ]);

        $term = Teachertype::create($validated);
        return response()->json(['message' => 'Teacher type created successfully', 'Teachertype' => $term], 201);
    }
 
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $validated = $request->validate([
            'teacher_type' => 'required',
        ]);

        $term = Teachertype::findOrFail($id);
        $term->update($validated);

        return response()->json(['message' => 'Teacher type updated successfully', 'Subject' => $term]);
    }
    public function viewbyid(Request $request,$id)
    {
       $id = $request->id; 

        $term = Teachertype::findOrFail($id);
 
        return response()->json(['message' => 'Teacher type got successfully', 'Subject' => $term]);
    }
 
    // public function destroy($id)
    // {
    //     $term = Subject::findOrFail($id);
    //     $term->delete();

    //     return response()->json(['message' => 'Subject deleted successfully']);
    // }
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $subject = Teachertype::findOrFail($id);
    
        // Update delete_status to 1 instead of deleting
        $subject->update(['delete_status' => 1]);
    
        return response()->json(['message' => 'Teacher type deleted successfully']);
    }
 
}
