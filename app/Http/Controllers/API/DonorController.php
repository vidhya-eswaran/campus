<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonarList;

class DonorController extends Controller
{
    public function index()
    {
        $donors = DonarList::where('delete_status', 0)->get();
        return response()->json($donors);
    }

   public function store(Request $request)
    {
        // Validate the request data directly
        $validatedData = $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
            'phoneNumber' => 'nullable',
            'addressline1' => 'nullable',
            'addressline2' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'country' => 'nullable',
            'pincode' => 'nullable',
            'panNo' => 'nullable',
            'typeofDonation' => 'nullable',
            'modeofPayment' => 'nullable',
            'amount' => 'nullable',
            'checkddTransid' => 'nullable',
        ]);

        // Create the record using validated data
        $donor = DonarList::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'phone_number' => $validatedData['phoneNumber'] ?? null,
            'address_1' => $validatedData['addressline1'] ?? null,
            'address_2' => $validatedData['addressline2'] ?? null,
            'city_1' => $validatedData['city'] ?? null,
            'state_1' => $validatedData['state'] ?? null,
            'country_1' => $validatedData['country'] ?? null,
            'pincode_1' => $validatedData['pincode'] ?? null,
            'pan_aadhar' => $validatedData['panNo'] ?? null,
            'type_of_donation' => $validatedData['typeofDonation'] ?? null,
            'mode_of_payment' => $validatedData['modeofPayment'],
            'amount' => $validatedData['amount'],
            'check_dd_trans_id' => $validatedData['checkddTransid'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donor added successfully',
            'data' => $donor,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        // Find the donor by ID or fail
        $donor = DonarList::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'addressline1' => 'nullable|string|max:255',
            'addressline2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'panNo' => 'nullable|string|max:50',
            'typeofDonation' => 'nullable|string|max:100',
            'modeofPayment' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'checkddTransid' => 'nullable|string|max:100',
        ]);

        // Update the record using validated data
        $donor->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'phone_number' => $validatedData['phoneNumber'] ?? null,
            'address_1' => $validatedData['addressline1'] ?? null,
            'address_2' => $validatedData['addressline2'] ?? null,
            'city_1' => $validatedData['city'] ?? null,
            'state_1' => $validatedData['state'] ?? null,
            'country_1' => $validatedData['country'] ?? null,
            'pincode_1' => $validatedData['pincode'] ?? null,
            'pan_aadhar' => $validatedData['panNo'] ?? null,
            'type_of_donation' => $validatedData['typeofDonation'] ?? null,
            'mode_of_payment' => $validatedData['modeofPayment'],
            'amount' => $validatedData['amount'],
            'check_dd_trans_id' => $validatedData['checkddTransid'] ?? null,
        ]);

        return response()->json([
            'message' => 'Donor updated successfully',
            'data' => $donor->fresh(), // Returns the updated model
        ], 200);
    }


    public function viewbyid($id)
    {
        $donor = DonarList::findOrFail($id);
        return response()->json([
            'message' => 'Donor retrieved successfully',
            'data' => $donor,
        ]);
    }

    public function destroy($id)
    {
        $donor = DonarList::findOrFail($id);
        $donor->delete_status = 1;
        $donor->save();
        return response()->json([
            'message' => 'Donor deleted successfully',
        ]);
    }
}
