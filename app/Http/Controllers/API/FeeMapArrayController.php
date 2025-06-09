<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FeeMapArray;
use Illuminate\Support\Facades\Validator;

class FeeMapArrayController extends Controller
{


public function insert(Request $request)
{
    $data = $request->all();

    // Check if 'std' field is present in the request data
    if (!isset($data['std'])) {
        return response()->json(['error' => 'The std field is required.'], 400);
    } else {
        // Check if a record with the same 'std' exists
        $existingRecord = FeeMapArray::where('std', $data['std'])->where('Fee_Category', $data['Fee_Category'])->first();

        if ($existingRecord) {
            // If a record already exists, update it
            $existingRecord->update($data);
            $response = $existingRecord;
        } else {
            // If no record exists, create a new one
                         $data['Sub_Fees'] = json_encode( $data['Sub_Fees'], true);

            $user = FeeMapArray::create($data);
            $data['created_by'] = User::find($request->created_by)->name ?? '';
            $data['id'] = $user->id;



            $response = $data;
        }
    }

    return response()->json($response, 200);
}


public function read(Request $request)
{
    $std = $request->input('std');
    $data = $request->all();

    // Default fallback records (when no std or no records found)
    $fallbackRecords = FeeMapArray::where('std', '')->where('Fee_Category', $data['Fee_Category'])->get();

    if (!$std) {
        $records = $fallbackRecords;
    } else {
        $records = FeeMapArray::where('std', $std)->where('Fee_Category', $data['Fee_Category'])->get();

        if ($records->isEmpty()) {
            $records = $fallbackRecords;
        }
    }

    // Decode Sub_Fees for each record
    $records->transform(function ($record) {
        $record->Sub_Fees = json_decode($record->Sub_Fees, true);
        return $record;
    });

    return response()->json($records, 200);
}



}
