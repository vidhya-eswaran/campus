<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StandardSectionMapping;
use Illuminate\Support\Facades\Log;

class StandardSectionMappingController extends Controller
{
    public function index()
    {
        $data = StandardSectionMapping::where('delete_status', 0)->get();

        return response()->json([
            'message' => 'Mappings retrieved successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'standard' => 'required',
            'sections' => 'required|array', // ✅ Ensure sections is an array
            'group' => 'nullable|string',
        ]);

        // ✅ Laravel will automatically store it as JSON (no need for json_encode)
        $mapping = StandardSectionMapping::create($validated);

        return response()->json([
            'message' => 'Mapping created successfully',
            'data' => $mapping
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'standard' => 'required',
            'sections' => 'required|array', // ✅ Ensure sections is an array
            'group' => 'nullable|string',
        ]);

        $mapping = StandardSectionMapping::findOrFail($id);

        // ✅ Laravel will automatically convert array to JSON when saving
        $mapping->update($validated);

        return response()->json([
            'message' => 'Mapping updated successfully',
            'data' => $mapping
        ]);
    }

    public function viewbyid($id)
    {
        $mapping = StandardSectionMapping::findOrFail($id);

        return response()->json([
            'message' => 'Mapping retrieved successfully',
            'data' => $mapping
        ]);
    }

   public function getSectionsByStandardAndGroup(Request $request)
    {
        // dd('hi');
        $validated = $request->validate([
            'standard' => 'required|integer',
            'group' => 'nullable|string'
        ]);

        $standard = $validated['standard'];
        $group = $validated['group'] ?? null;

        $query = StandardSectionMapping::where('standard', $standard)
            ->where('delete_status', 0);

        // For standard 11 & 12, ensure group is provided
        if ($standard >= 11 && $standard <= 12) {
            if (!$group) {
                return response()->json(['message' => 'Group is required for standards 11 and 12'], 400);
            }
            $query->where('group', $group);
        }

        $data = $query->get();

        // If no data is found, return empty array instead of 404 error
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No data found for the given standard and group',
                'data' => []
            ], 200);
        }

        // Convert sections from JSON string to array
        $formattedData = $data->map(function ($item) {
            return [
                'standard' => $item->standard,
                'sections' => is_array($item->sections) ? $item->sections : json_decode($item->sections, true),
                'group' => $item->group ?? null
            ];
        });

        return response()->json([
            'message' => 'Data retrieved successfully',
            'data' => $formattedData
        ], 200);
    }


    public function destroy($id)
    {
        $mapping = StandardSectionMapping::findOrFail($id);
        $mapping->update(['delete_status' => 1]);

        return response()->json(['message' => 'Mapping deleted successfully']);
    }
}
