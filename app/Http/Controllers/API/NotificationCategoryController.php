<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationCategory;

class NotificationCategoryController extends Controller
{
    // Fetch all notification categories (only non-deleted if applicable)
    public function index()
    {
        $categories = NotificationCategory::where('delete_status', 0)->get();
        return response()->json($categories);
    }

    // Store a new notification category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'notification_category' => 'required|string|max:255',
        ]);

        $category = NotificationCategory::create($validated);
        return response()->json([
            'message' => 'Notification category created successfully',
            'category' => $category
        ], 201);
    }

    // Update an existing notification category
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'notification_category' => 'required|string|max:255',
        ]);

        $category = NotificationCategory::findOrFail($id);
        $category->update($validated);

        return response()->json([
            'message' => 'Notification category updated successfully',
            'category' => $category
        ]);
    }

    // Get a single notification category by ID
    public function viewbyid($id)
    {
        $category = NotificationCategory::findOrFail($id);

        return response()->json([
            'message' => 'Notification category retrieved successfully',
            'category' => $category
        ]);
    }

    // Soft delete: update delete_status instead of deleting
    public function destroy($id)
    {
        $category = NotificationCategory::findOrFail($id);
        $category->update(['delete_status' => 1]);

        return response()->json(['message' => 'Notification category deleted successfully']);
    }
}
