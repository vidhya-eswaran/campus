<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\GroupMaster;

class GroupMasterController extends Controller
{
    public function index()
    {
        $standards = GroupMaster::where('delete_status', 0)->get();
        return response()->json($standards);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required', // Ensuring integer input
        ]);

        $standard = GroupMaster::create($validated);
        return response()->json(['message' => 'Group created successfully', 'Standard' => $standard], 201);
    }

    public function update(Request $request, $id)
    {
        $id = $request->id;
        $id = (int) $id; // Ensure ID is an integer

        $validated = $request->validate([
            'group' => 'required',
        ]);

        $standard = GroupMaster::findOrFail($id);
        $standard->update($validated);

        return response()->json(['message' => 'Group updated successfully', 'Standard' => $standard]);
    }

    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $id = (int) $id; // Ensure ID is an integer

        $standard = GroupMaster::findOrFail($id);

        return response()->json(['message' => 'Group retrieved successfully', 'Standard' => $standard]);
    }

    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $id = (int) $id; // Ensure ID is an integer
        $standard = GroupMaster::findOrFail($id);

        // Update delete_status instead of deleting
        $standard->update(['delete_status' => 1]);

        return response()->json(['message' => 'Group deleted successfully']);
    }
}