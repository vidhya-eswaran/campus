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
    // Retrieve the 'std' value from the request
    $std = $request->input('std');
    $data = $request->all();

    // Check if the 'std' value is provided in the request
    if (!$std) {
    $records = FeeMapArray::where('std', '')->where('Fee_Category', $data['Fee_Category'])->get();
        return response()->json($records, 200);

    }

    // Retrieve the record(s) based on the 'std' value
    $records = FeeMapArray::where('std', $std)->where('Fee_Category', $data['Fee_Category'])->get();

    // Check if any records are found
    if ($records->isEmpty()) {
        $records = FeeMapArray::where('std', '')->where('Fee_Category', $data['Fee_Category'])->get();
        return response()->json($records, 200);    }

    // Return the records as a JSON response
    return response()->json($records, 200);
}

     
}
