<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCalendar;
use App\Models\EventCategoryMaster;

class EventCalendarController extends Controller
{
    // Fetch all event calendars with category details
    public function index()
    {
        $events = EventCalendar::with('categoryDetails')->get();
        return response()->json($events);
    }

    // Store a new event calendar
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|exists:event_category_masters,id', // Ensuring category exists
            'isStart' => 'required|date',
            'isEnd' => 'required|date|after_or_equal:isStart',
        ]);

        $event = EventCalendar::create($validated);

        return response()->json([
            'message' => 'Event calendar created successfully',
            'event' => $event
        ], 201);
    }

    // Update an existing event calendar
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|exists:event_category_masters,id',
            'isStart' => 'required|date',
            'isEnd' => 'required|date|after_or_equal:isStart',
        ]);

        $event = EventCalendar::findOrFail($id);
        $event->update($validated);

        return response()->json([
            'message' => 'Event calendar updated successfully',
            'event' => $event
        ]);
    }

    // Get a single event by ID
    public function viewbyid($id)
    {
        $event = EventCalendar::with('categoryDetails')->findOrFail($id);

        return response()->json([
            'message' => 'Event calendar retrieved successfully',
            'event' => $event
        ]);
    }

    // Delete event calendar (soft delete optional)
    public function destroy($id)
    {
        $event = EventCalendar::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event calendar deleted successfully']);
    }
}
