<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageCategoryMaster;

class MessageCategoryMasterController extends Controller
{
    public function index()
    {
        $data = MessageCategoryMaster::leftJoin('users', 'users.id', '=', 'message_category_master.created_by')
            ->select('message_category_master.*', 'users.name as created_by_name')
            ->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'messageCategory' => 'required|string|max:255',
            'created_by' => 'nullable'
        ]);

        $record = MessageCategoryMaster::create($validated);
        return response()->json(['message' => 'Created successfully', 'result' => $record], 201);
    }

    public function update(Request $request, $id)
    {
        $id = $request->id;
        $validated = $request->validate([
            'messageCategory' => 'required|string|max:255',
            'created_by' => 'nullable'
        ]);

        $record = MessageCategoryMaster::findOrFail($id);
        $record->update($validated);

        return response()->json(['message' => 'Updated successfully', 'result' => $record]);
    }

    public function viewbyid(Request $request, $id)
    {
        $id = $request->id;
        $record = MessageCategoryMaster::leftJoin('users', 'users.id', '=', 'message_category_master.created_by')
            ->select('message_category_master.*', 'users.name as created_by_name')
            ->where('message_category_master.id', $id)
            ->firstOrFail();

        return response()->json(['message' => 'Retrieved successfully', 'result' => $record]);
    }

    public function destroy(Request $request, $id)
    {
        $id = $request->id;
        MessageCategoryMaster::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
