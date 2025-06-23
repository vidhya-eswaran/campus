<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Standard;

class StandardMasterController extends Controller
{
    public function index()
    {
        $data = Standard::where('delete_status', 0)->get();

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|in:standard,section,group',
            'options' => 'required|array', // Ensure options is an array
        ]);

        // Create a new record
        $data = Standard::create($validated);

        return response()->json([
            'message' => ucfirst($validated['title']) . ' created successfully',
            'data' => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $id = $request->id;
        $validated = $request->validate([
            'title' => 'required|string|in:standard,section,group',
            'options' => 'required|array',
        ]);

        $data = Standard::findOrFail($id);

        $data->update($validated);

        return response()->json([
            'message' => ucfirst($validated['title']) . ' updated successfully',
            'data' => $data
        ]);
    }

    public function viewbyid(Request $request, $id)
    {
       
        $id = $request->id;       
        $data = Standard::findOrFail($id);
        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Standard::findOrFail($id);
        $data->update(['delete_status' => 1]);

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
