<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Targetannouncement;

class TargetannouncementMasterController extends Controller
{
    public function index()
    {
        $announcements = Targetannouncement::where('delete_status', 0)
            ->get(['id', 'target_audience', 'target_group']); // Select only required fields
    
        // Decode JSON `target_group` field to return it as an array
        $announcements->transform(function ($announcement) {
            $announcement->target_group = json_decode($announcement->target_group, true);
            return $announcement;
        });
    
        return response()->json($announcements);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_audience' => 'required|string',
            'target_group' => 'required|array',
            'user_details' => 'required|array',
        ]);

        try {
            $announcement = Targetannouncement::create([
                'target_audience' => $validated['target_audience'],
                'target_group' => json_encode($validated['target_group']),
                'user_details' => json_encode($validated['user_details']),
            ]);

            return response()->json(['message' => 'Target announcement created successfully', 'data' => $announcement], 201);
        } catch (\Exception $e) {
            Log::error('Error creating target announcement: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create target announcement'], 500);
        }
    }

    public function update(Request $request, $id)
    {
         $id = $request->id;
        $validated = $request->validate([
            'target_audience' => 'required|string',
            'target_group' => 'required|array',
            'user_details' => 'required|array',
        ]);

        try {
            $announcement = Targetannouncement::findOrFail($id);
            $announcement->update([
                'target_audience' => $validated['target_audience'],
                'target_group' => json_encode($validated['target_group']),
                'user_details' => json_encode($validated['user_details']),
            ]);

            return response()->json(['message' => 'Target announcement updated successfully', 'data' => $announcement]);
        } catch (\Exception $e) {
            Log::error('Error updating target announcement: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update target announcement'], 500);
        }
    }

    public function viewbyid(Request $request,$id)
    {
         $id = $request->id;
        $announcement = Targetannouncement::findOrFail($id);
    
        // Decode `target_group` and `user_details` JSON fields to return as arrays
        $announcement->target_group = json_decode($announcement->target_group, true);
        $announcement->user_details = json_decode($announcement->user_details, true);
    
        return response()->json([
            'message' => 'Target announcement retrieved successfully',
            'data' => $announcement
        ]);
    }


    public function destroy(Request $request,$id)
    {
         $id = $request->id;
        $announcement = Targetannouncement::findOrFail($id);
        $announcement->update(['delete_status' => 1]);

        return response()->json(['message' => 'Target announcement deleted successfully']);
    }
}
