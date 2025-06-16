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
            "contactId" => "nullable",
            "name" => "required|max:155",
            "email" => "nullable|email|max:255",
            "mobileNo" => "nullable|max:155",
            "addressLine1" => "nullable|max:255",
            "addressLine2" => "nullable|max:255",
            "city" => "nullable|max:100",
            "state" => "nullable|max:100",
            "country" => "nullable",
            "pincode" => "nullable",
            "contactType" => "nullable|max:150",
        ]);

        $userId = null;

        // Create user if email is provided and does not exist
        if (isset($requestData["email"])) {
            $existingUser = User::where(
                "email",
                $requestData["email"]
            )->first();

            if (!$existingUser) {
                // Generate new user ID
                $lastUserId = User::latest("id")->value("id") + 1;

                $userData = [
                    "name" => $requestData["name"],
                    "email" => $requestData["email"],
                    "user_type" => "admin",
                    "id" => $lastUserId,
                    "password" => Hash::make("svs@123"),
                ];

                $user = User::create($userData);
                $userId = $user->id; // Store the user ID
            } else {
                $userId = $existingUser->id; // If user exists, use the existing user ID
            }
        }

        $contact = Contact::create([
            "contact_id" => $requestData["contactId"],
            "name" => $requestData["name"],
            "email" => $requestData["email"],
            "mobile_no" => $requestData["mobileNo"],
            "address_line_1" => $requestData["addressLine1"],
            "address_line_2" => $requestData["addressLine2"],
            "city" => $requestData["city"],
            "state" => $requestData["state"],
            "country" => $requestData["country"],
            "pincode" => $requestData["pincode"],
            "contact_type" => $requestData["contactType"],
            "user_id" => $userId,
        ]);

        return response()->json(
            ["message" => "Contact added successfully!", "contact" => $contact],
            201
        );
    }

    public function editStaff(Request $request, $id)
    {
        // Find staff by ID
        $staff = Contact::find($id);
        if (!$staff) {
            return response()->json(["message" => "Contact not found"], 404);
        }

        // Validate request data
        $requestData = $request->validate([
            "contactId" => "nullable",
            "name" => "required|max:155",
            "addressLine1" => "nullable|max:255",
            "addressLine2" => "nullable|max:255",
            "city" => "nullable|max:100",
            "state" => "nullable|max:100",
            "mobileNo" => "nullable|max:155",
            "pincode" => "nullable",
            "country" => "nullable|max:100",
            "email" => "nullable|email|max:255",
            "contactType" => "nullable|max:150",
        ]);

        // Update the contact
        $staff->update([
            "contact_id" => $requestData["contactId"],
            "name" => $requestData["name"],
            "email" => $requestData["email"] ?? null,
            "mobile_no" => $requestData["mobileNo"] ?? null,
            "address_line_1" => $requestData["addressLine1"] ?? null,
            "address_line_2" => $requestData["addressLine2"] ?? null,
            "city" => $requestData["city"] ?? null,
            "state" => $requestData["state"] ?? null,
            "country" => $requestData["country"] ?? null,
            "pincode" => $requestData["pincode"] ?? null,
            "contact_type" => $requestData["contactType"] ?? null,
        ]);

        // Update associated user if applicable
        if (isset($requestData["email"])) {
            $user = User::find($staff->user_id);
            if ($user) {
                $user->update([
                    "name" => $requestData["name"],
                    "email" => $requestData["email"],
                ]);
            }
        }

        return response()->json(
            [
                "message" => "Contact details updated successfully!",
                "contact" => $staff,
            ],
            200
        );
    }

    public function viewStaff($id)
    {
        $staff = Contact::find($id);

        if (!$staff) {
            return response()->json(["message" => "contact not found"], 404);
        }

        return response()->json(["contact" => $staff], 200);
    }

    public function viewAllStaff()
    {
        // Retrieve staff where 'isdeleted' is false or NULL
        $staff = Contact::where("delete_status", 0)->get();

        return response()->json(["contact" => $staff], 200);
    }

    public function destroy($id)
    {
        $dropdownType = Contact::findOrFail($id);

        // Soft delete by updating delete_status
        $dropdownType->update(["delete_status" => 1]);

        return response()->json(
            [
                "message" => "contact deleted successfully",
            ],
            200
        );
    }
}
