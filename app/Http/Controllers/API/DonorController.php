<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonarList;
use Illuminate\Support\Facades\Validator;

class DonorController extends Controller
{
    public function index()
    {
        $donors = DonarList::where('delete_status', 0)->get();
        return response()->json($donors);
    }

   public function store(Request $request)
   {
   // dd($request);
        $validator = Validator::make($request->all(), [
            'donor_id' => 'nullable',
            'donor_name' => 'nullable|string',
            'email' => 'nullable|email',
            'mobile_no' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city_2' => 'nullable|string',
            'state_2' => 'nullable|string',
            'country_2' => 'nullable|string',
            'pincode_2' => 'nullable|string',
            'pan_no' => 'nullable|string',
            'typeOfDonation' => 'nullable|string',
            'mode_of_payment' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'check_dd_trans_id' => 'nullable|string',
            'amount' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $validatedData = $validator->validated();

       // dd($validatedData['donor_name']);

        $donor = DonarList::create([
            'donor_id' => $validatedData['donor_id'] ?? null,
            'donor_name' => $validatedData['donor_name'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'mobile_no' => $validatedData['mobile_no'] ?? null,
            'address_line_1' => $validatedData['address_line_1'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'state' => $validatedData['state'] ?? null,
            'country' => $validatedData['country'] ?? null,
            'pincode' => $validatedData['pincode'] ?? null,
            'address_line_2' => $validatedData['address_line_2'] ?? null,
            'city_2' => $validatedData['city_2'] ?? null,
            'state_2' => $validatedData['state_2'] ?? null,
            'country_2' => $validatedData['country_2'] ?? null,
            'pincode_2' => $validatedData['pincode_2'] ?? null,
            'pan_no' => $validatedData['pan_no'] ?? null,
            'typeOfDonation' => $validatedData['typeOfDonation'] ?? null,
            'mode_of_payment' => $validatedData['mode_of_payment'] ?? null,
            'payment_type' => $validatedData['payment_type'] ?? null,
            'check_dd_trans_id' => $validatedData['check_dd_trans_id'] ?? null,
            'amount' => $validatedData['amount'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donor added successfully',
            'data' => $donor,
        ], 201);
    }



    public function update(Request $request, $id)
    {
        $id = $request->id;
        // Find the donor by ID or fail
        $donor = DonarList::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'donor_id' => 'nullable',
            'donor_name' => 'nullable|string',
            'email' => 'nullable|email',
            'mobile_no' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'country' => 'nullable|string',
            'pincode' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city_2' => 'nullable|string',
            'state_2' => 'nullable|string',
            'country_2' => 'nullable|string',
            'pincode_2' => 'nullable|string',
            'pan_no' => 'nullable|string',
            'typeOfDonation' => 'nullable|string',
            'mode_of_payment' => 'nullable|string',
            'payment_type' => 'nullable|string',
            'check_dd_trans_id' => 'nullable|string',
            'amount' => 'nullable',
        ]);

        // Update the record using validated data
        $donor->update([
            'donor_id' => $validatedData['donor_id'] ?? null,
            'donor_name' => $validatedData['donor_name'] ?? null,
            'email' => $validatedData['email'] ?? null,
            'mobile_no' => $validatedData['mobile_no'] ?? null,
            'address_line_1' => $validatedData['address_line_1'] ?? null,
            'city' => $validatedData['city'] ?? null,
            'state' => $validatedData['state'] ?? null,
            'country' => $validatedData['country'] ?? null,
            'pincode' => $validatedData['pincode'] ?? null,
            'address_line_2' => $validatedData['address_line_2'] ?? null,
            'city_2' => $validatedData['city_2'] ?? null,
            'state_2' => $validatedData['state_2'] ?? null,
            'country_2' => $validatedData['country_2'] ?? null,
            'pincode_2' => $validatedData['pincode_2'] ?? null,
            'pan_no' => $validatedData['pan_no'] ?? null,
            'typeOfDonation' => $validatedData['typeOfDonation'] ?? null,
            'mode_of_payment' => $validatedData['mode_of_payment'] ?? null,
            'payment_type' => $validatedData['payment_type'] ?? null,
            'check_dd_trans_id' => $validatedData['check_dd_trans_id'] ?? null,
            'amount' => $validatedData['amount'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donor updated successfully',
            'data' => $donor->fresh(), // Returns the updated model
        ], 200);
    }


    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $donor = DonarList::findOrFail($id);
        return response()->json([
            'message' => 'Donor retrieved successfully',
            'data' => $donor,
        ]);
    }

    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $donor = DonarList::findOrFail($id);
        $donor->delete_status = 1;
        $donor->save();
        return response()->json([
            'message' => 'Donor deleted successfully',
        ]);
    }
}
