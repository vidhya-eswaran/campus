<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Dropdowntype;

class DropdowntypeMasterController extends Controller
{
 
//   public function index()
//     {
//         $terms = Subject::all();
//         return response()->json($terms);
//     }
    public function index()
    {
        $terms = Dropdowntype::where('delete_status', 0)->get();
    
        // Decode options JSON
        $terms->transform(function ($term) {
            $term->options = json_decode($term->options, true); // Convert JSON string to array
            return $term;
        });
    
        return response()->json($terms);
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'options' => 'required|array', // Ensure it's an array
        ]);
    
        $type = Dropdowntype::create([
            'title' => $request->title,
            'options' => json_encode($request->options), // Convert array to JSON before storing
        ]);
    
        return response()->json([
            'message' => 'Dropdown type created successfully',
            'data' => $type
        ], 201);
    }
 
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'options' => 'required|array', // Ensure it's an array
        ]);
    
        $dropdownType = Dropdowntype::findOrFail($id);
    
        // Convert options to JSON and update
        $dropdownType->update([
            'title' => $validatedData['title'],
            'options' => json_encode($validatedData['options']), // Store as JSON
        ]);
    
        return response()->json([
            'message' => 'Dropdown type updated successfully',
            'DropdownType' => $dropdownType
        ], 200);
    }
    
   public function viewbyid($id)
    {
        $dropdownType = Dropdowntype::findOrFail($id);
    
        // Decode options JSON
        $dropdownType->options = json_decode($dropdownType->options, true);
    
        return response()->json([
            'message' => 'Dropdown type retrieved successfully',
            'DropdownType' => $dropdownType
        ], 200);
    }
    
    public function destroy($id)
    {
        $dropdownType = Dropdowntype::findOrFail($id);
    
        // Soft delete by updating delete_status
        $dropdownType->update(['delete_status' => 1]);
    
        return response()->json([
            'message' => 'Dropdown type deleted successfully'
        ], 200);
    }
 
}
