<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategoryMaster;

class EventCategoryMasterController extends Controller
{
    // Fetch all event categories (only non-deleted if applicable)
    public function index()
    {
        $categories = EventCategoryMaster::where('delete_status', 0)->get();
        return response()->json($categories);
    }

    // Store a new event category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'eventCategory' => 'required|string|max:255',
            'eventColor' => 'required|string|max:7',
        ]);

        $category = EventCategoryMaster::create($validated);
        return response()->json([
            'message' => 'Event category created successfully',
            'category' => $category
        ], 201);
    }

    // Update an existing event category
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $validated = $request->validate([
            'eventCategory' => 'required|string|max:255',
            'eventColor' => 'required|string|max:7',
        ]);

        $category = EventCategoryMaster::findOrFail($id);
        $category->update($validated);

        return response()->json([
            'message' => 'Event category updated successfully',
            'category' => $category
        ]);
    }

    // Get a single event category by ID
    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $category = EventCategoryMaster::findOrFail($id);

        return response()->json([
            'message' => 'Event category retrieved successfully',
            'category' => $category
        ]);
    }

    // Soft delete: update delete_status instead of deleting
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $category = EventCategoryMaster::findOrFail($id);
        $category->update(['delete_status' => 1]);

        return response()->json(['message' => 'Event category deleted successfully']);
    }
}
