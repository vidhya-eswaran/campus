<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Subject;

class subjectMasterController extends Controller
{
 
//   public function index()
//     {
//         $terms = Subject::all();
//         return response()->json($terms);
//     }
    public function index()
    {
        $terms = Subject::where('delete_status', 0)->get(); // Fetch only non-deleted records
        return response()->json($terms);
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $term = Subject::create($validated);
        return response()->json(['message' => 'Subject created successfully', 'Subject' => $term], 201);
    }
 
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $term = Subject::findOrFail($id);
        $term->update($validated);

        return response()->json(['message' => 'Subject updated successfully', 'Subject' => $term]);
    }
    public function viewbyid($id)
    {
        

        $term = Subject::findOrFail($id);
 
        return response()->json(['message' => 'Subject got successfully', 'Subject' => $term]);
    }
 
    // public function destroy($id)
    // {
    //     $term = Subject::findOrFail($id);
    //     $term->delete();

    //     return response()->json(['message' => 'Subject deleted successfully']);
    // }
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
    
        // Update delete_status to 1 instead of deleting
        $subject->update(['delete_status' => 1]);
    
        return response()->json(['message' => 'Subject deleted successfully']);
    }
 
}
