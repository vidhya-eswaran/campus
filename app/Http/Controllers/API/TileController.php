<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TileController extends Controller
{
    /**
     * Insert a new tile into the database.
     */
    public function insertTile(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'tile_name'   => 'required',
                'tile_route'  => 'nullable',
                'role'        => 'required',
                'tile_icon'   => 'nullable',
                'can_read'    => 'boolean',
                'can_write'   => 'boolean',
                'can_update'  => 'boolean',
                'can_delete'  => 'boolean',
            ]);

            // Insert the tile into the database
            DB::table('tile_permissions')->insert([
                'tile_name'   => $validatedData['tile_name'],
                'tile_route'  => $validatedData['tile_route'],
                'role'        => $validatedData['role'],
                'tile_icon'   => $validatedData['tile_icon'],
                'can_read'    => $validatedData['can_read'] ?? false,
                'can_write'   => $validatedData['can_write'] ?? false,
                'can_update'  => $validatedData['can_update'] ?? false,
                'can_delete'  => $validatedData['can_delete'] ?? false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return response()->json(['message' => 'Tile added successfully'], 201);
        } catch (\Exception $e) {
            Log::error('Error inserting tile: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add tile'], 500);
        }
    }

     public function updateTile(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                   'tile_name'   => 'required',
                'tile_route'  => 'nullable',
                'role'        => 'required',
                'tile_icon'   => 'nullable',
                'can_read'    => 'boolean',
                'can_write'   => 'boolean',
                'can_update'  => 'boolean',
                'can_delete'  => 'boolean',
            ]);

            $updateData = array_filter($validatedData, function ($value) {
                return $value !== null;
            });

            if (empty($updateData)) {
                return response()->json(['message' => 'No data to update'], 400);
            }

            $updateData['updated_at'] = now();

            $updated = DB::table('tile_permissions')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json(['message' => 'Tile updated successfully'], 200);
            } else {
                return response()->json(['message' => 'Tile not found or no changes made'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating tile: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update tile'], 500);
        }
    }
    public function getTiles(Request $request)
    {
        try {
                $userId = $request->user()->id;
    $userType = \App\Models\User::where('id', $userId)->value('user_type');

           // $role = $request->user()->role; // Assume the user's role is stored in the user model
 
            // Fetch tiles for the role
            $tiles = DB::table('tile_permissions')
                ->select('id','tile_name', 'tile_route', 'tile_icon', 'can_read', 'can_write', 'can_update', 'can_delete')
                ->where('role', $userType)
                ->get();

            return response()->json(['tiles' => $tiles], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tiles: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch tiles'], 500);
        }
    }
     public function getallTiles(Request $request)
    {
        try {
                 
           //  
            // Fetch tiles for the role
            $tiles = DB::table('tile_permissions')
                ->select('id','tile_name', 'tile_route','role', 'tile_icon', 'can_read', 'can_write', 'can_update', 'can_delete')
                 ->get();

            return response()->json(['tiles' => $tiles], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching tiles: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch tiles'], 500);
        }
    }
}
