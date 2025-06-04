<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Term;

class TermController extends Controller
{
 
    public function index()
    {
        $terms = Term::where('delete_status', 0)->get();
        return response()->json($terms);
    }
 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $term = Term::create($validated);
        return response()->json(['message' => 'Term created successfully', 'term' => $term], 201);
    }
 
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $term = Term::findOrFail($id);
        $term->update($validated);

        return response()->json(['message' => 'Term updated successfully', 'term' => $term]);
    }
    public function viewbyid($id)
    {
        

        $term = Term::findOrFail($id);
 
        return response()->json(['message' => 'Term got successfully', 'term' => $term]);
    }
 
    public function destroy_old($id)
    {
        $term = Term::findOrFail($id);
        $term->delete();

        return response()->json(['message' => 'Term deleted successfully']);
    }
    public function destroy($id)
    {
        try {
        // Find the term
        $term = Term::findOrFail($id);

        // Toggle status (0 → 1, 1 → 0)
        $term->delete_status = 1;
        $term->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $term
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error updating status',
            'error' => $e->getMessage()
        ], 500);
    }
    }
 
}
