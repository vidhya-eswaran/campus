<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{

     public function addStaff(Request $request)
        {
    // Validate request data
    $requestData = $request->validate([
        'id' => 'nullable',
        'name' => 'required|max:155',
        'mobileNo' => 'nullable|max:155',
        'addressLine1' => 'nullable|max:255',
        'addressLine2' => 'nullable|max:255',
        'city' => 'nullable|max:100',
        'state' => 'nullable|max:100',
        'pincode' => 'nullable',
        'countryCode' => 'nullable',
        'email' => 'nullable|email|max:255',
        'contactType' => 'nullable|max:150',
    ]);

    $userId = null;

    // Create user if email is provided and does not exist
    if (isset($requestData['email'])) {
        $existingUser = User::where('email', $requestData['email'])->first();

        if (!$existingUser) {
            // Generate new user ID
            $lastUserId = User::latest('id')->value('id') + 1;

            $userData = [
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'user_type' => 'admin',
                'id' => $lastUserId,
                'password' => Hash::make('svs@123'),
            ];

            $user = User::create($userData);
            $userId = $user->id;  // Store the user ID
        } else {
            $userId = $existingUser->id;  // If user exists, use the existing user ID
        }
    }

    // Add user_id to the request data
    $requestData['user_id'] = $userId;

    // Create the staff record
    $staff = Contact::create($requestData);

    return response()->json(['message' => 'Contact added successfully!', 'contact' => $staff], 201);
}


  public function editStaff(Request $request, $id)
        {
    // Find staff by ID
    $staff = Contact::find($id);
    if (!$staff) {
        return response()->json(['message' => 'Contact not found'], 404);
    }

    // Validate request data
    $requestData = $request->validate([
        'name' => 'required|max:155',
        'addressLine1' => 'nullable|max:255',
        'addressLine2' => 'nullable|max:255',
        'city' => 'nullable|max:100',
        'state' => 'nullable|max:100',
        'mobileNo' => 'nullable|max:155',
        'pincode' => 'nullable',
        'countryCode' => 'nullable',
        'email' => 'nullable|email|max:255',
        'contactType' => 'nullable|max:150',
        'isdeleted' => 'nullable|boolean',
    ]);

    // Update the staff record
    $staff->update($requestData);

    // If the email is updated, also update the user record
    if (isset($requestData['email'])) {
        $user = User::find($staff->user_id);

        if ($user) {
            // Update the user's email if it's changed
            $user->update([
                'name' => $requestData['name'],
                'email' => $requestData['email'],
            ]);
        }
    }

    return response()->json(['message' => 'Contact details updated successfully!', 'contact' => $staff], 200);
}


    public function viewStaff($id)
    {
        $staff = Contact::find($id);

        if (!$staff) {
            return response()->json(['message' => 'contact not found'], 404);
        }

        return response()->json(['contact' => $staff], 200);
    }

    public function viewAllStaff()
    {
        // Retrieve staff where 'isdeleted' is false or NULL
        $staff = Contact::where('isdeleted', false)->orWhereNull('isdeleted')->get();

        return response()->json(['contact' => $staff], 200);
    }
}
