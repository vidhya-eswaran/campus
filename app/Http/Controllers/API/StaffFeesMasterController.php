<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffFeeMasters;
 
class StaffFeesMasterController extends Controller
{
   public function index()
{
    $terms = StaffFeeMasters::leftJoin('users', 'users.id', '=', 'staff_fee_masters.created_by')
        ->select('staff_fee_masters.*', 'users.name as created_by_name')
        ->get();

    return response()->json($terms);
}
    public function store(Request $request)
    {
        $term = StaffFeeMasters::create($request->validate(['feesType' => 'required|string|max:255', 'created_by' => 'nullable']));
        return response()->json(['message' => 'Created successfully', 'result' => $term], 201);
    }

    public function update(Request $request, $id)
    {
        $term = StaffFeeMasters::findOrFail($id);
        $term->update($request->validate(['feesType' => 'required|string|max:255', 'created_by' => 'nullable']));
        return response()->json(['message' => 'Updated successfully', 'result' => $term]);
    }
public function viewbyid($id)
{
    $term = StaffFeeMasters::leftJoin('users', 'users.id', '=', 'staff_fee_masters.created_by')
        ->select('staff_fee_masters.*', 'users.name as created_by_name')
        ->where('staff_fee_masters.id', $id)
        ->firstOrFail();

    return response()->json(['message' => 'Retrieved successfully', 'result' => $term]);
}


    public function destroy($id)
    {
        StaffFeeMasters::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
