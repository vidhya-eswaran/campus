<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Helpers\Autogeneratenumber;
// require_once app_path('Autogeneratenumber.php');
class StaffController extends Controller
{
    public function addStaff(Request $request)
    {
        $requestData = $request->validate([
            "staffName" => "required|string|max:155",
            // 'staff_id' => 'nullable',
            "designation" => "nullable|string|max:255",
            "email" => "nullable",
            "permanentAddress" => "array",
            "permanentAddress.addressLine1" => "nullable",
            "permanentAddress.addressLine2" => "nullable",
            "permanentAddress.city" => "nullable",
            "permanentAddress.state" => "nullable",
            "permanentAddress.pincode" => "nullable",
            "permanentAddress.country" => "nullable",
            "communicationAddress" => "array",
            "communicationAddress.addressLine1" => "nullable",
            "communicationAddress.addressLine2" => "nullable",
            "communicationAddress.city" => "nullable",
            "communicationAddress.state" => "nullable",
            "communicationAddress.pincode" => "nullable",
            "communicationAddress.country" => "nullable",
            "communicationAddress.spouseName" => "nullable",
            "communicationAddress.spouseWorking" => "nullable",
            "communicationAddress.spouseMobileNo" => "nullable",
            "communicationAddress.spouseMail" => "nullable",
            "staff_photo" => "nullable",
            "date_of_joining" => "nullable",
            "isdeleted" => "nullable|boolean",
            "gender" => "nullable",
            "dob" => "nullable",
            "qualification" => "nullable",
            "teacher_type" => "nullable",
            "previous_experience" => "nullable",
            "date_of_joining" => "nullable",
            "staff_photo" => "nullable",
            "mobile_no" => "nullable",
            "marital_status" => "nullable",
            "no_of_children" => "nullable",
            "father_name" => "nullable",
            "mother_name" => "nullable",
            "emergency_contact_no" => "nullable",
            "epf_no" => "nullable",
            "aadhaar_no" => "nullable",
            "pan" => "nullable",
            "staff_status" => "nullable",
            "date_of_resignation" => "nullable",
        ]);

        if (isset($requestData["permanentAddress"])) {
            $requestData["permanentAddress"] = json_encode(
                $requestData["permanentAddress"]
            );
        }

        if (isset($requestData["communicationAddress"])) {
            $requestData["communicationAddress"] = json_encode(
                $requestData["communicationAddress"]
            );
        }
        $requestData["staff_id"] = Autogeneratenumber::generateStaffId(
            $requestData["date_of_joining"]
        );

        $staff = Staff::create($requestData);
        if ($request->has("staff_photo")) {
            // request, $staff, fieldname on API, file path, db column name
            $this->handleImageUpdate(
                $request,
                $staff,
                "staff_photo",
                "staff_photo",
                "staff_photo"
            );
        }

        if ($staff) {
            $lastid = User::latest("id")->value("id") + 1;

            $userData = [
                "name" => $requestData["staffName"],
                "email" => $requestData["email"] ?? null,
                "user_type" => "staff",
                "id" => $lastid,
                "roll_no" => $requestData["staff_id"],
                "password" => Hash::make("svs@123"),
            ];

            User::create($userData);
        }

        return response()->json(
            ["message" => "Staff added successfully!", "staff" => $staff],
            201
        );
    }

    // Edit staff details
    public function editStaff(Request $request, $id)
    {
        // Find staff by ID
        $id = $request->id;
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(["message" => "Staff not found"], 404);
        }

        // Validate incoming request data
        $requestData = $request->validate([
            "staffName" => "required|string|max:155",
            "staff_id" => "nullable",
            "designation" => "nullable|string|max:255",
            "email" => "nullable",
            "permanentAddress" => "array",
            "permanentAddress.addressLine1" => "nullable",
            "permanentAddress.addressLine2" => "nullable",
            "permanentAddress.city" => "nullable",
            "permanentAddress.state" => "nullable",
            "permanentAddress.pincode" => "nullable",
            "permanentAddress.country" => "nullable",
            "communicationAddress" => "array",
            "communicationAddress.addressLine1" => "nullable",
            "communicationAddress.addressLine2" => "nullable",
            "communicationAddress.city" => "nullable",
            "communicationAddress.state" => "nullable",
            "communicationAddress.pincode" => "nullable",
            "communicationAddress.country" => "nullable",
            "communicationAddress.spouseName" => "nullable",
            "communicationAddress.spouseWorking" => "nullable",
            "communicationAddress.spouseMobileNo" => "nullable",
            "communicationAddress.spouseMail" => "nullable",
            "staff_photo" => "nullable",
            "date_of_joining" => "nullable",
            "isdeleted" => "nullable|boolean",
            "gender" => "nullable",
            "dob" => "nullable",
            "qualification" => "nullable",
            "teacher_type" => "nullable",
            "previous_experience" => "nullable",
            "date_of_joining" => "nullable",
            "staff_photo" => "nullable",
            "mobile_no" => "nullable",
            "marital_status" => "nullable",
            "no_of_children" => "nullable",
            "father_name" => "nullable",
            "mother_name" => "nullable",
            "emergency_contact_no" => "nullable",
            "epf_no" => "nullable",
            "aadhaar_no" => "nullable",
            "pan" => "nullable",
            "staff_status" => "nullable",
            "date_of_resignation" => "nullable",
        ]);

        // Encode the address data to JSON format
        if (isset($requestData["permanentAddress"])) {
            $requestData["permanentAddress"] = json_encode(
                $requestData["permanentAddress"]
            );
        }
        if (isset($requestData["communicationAddress"])) {
            $requestData["communicationAddress"] = json_encode(
                $requestData["communicationAddress"]
            );
        }

        if ($request->has("staff_photo")) {
            // request, $staff, fieldname on API, file path, db column name
            $this->handleImageUpdate(
                $request,
                $staff,
                "staff_photo",
                "staff_photo",
                "staff_photo"
            );
        }

        // Update the staff record with the new data
        $staff->update($requestData);

        // Return response with the updated staff details
        return response()->json(
            [
                "message" => "Staff details updated successfully!",
                "staff" => $staff,
            ],
            200
        );
    }

    // View staff details
    public function viewStaff(Request $request, $id)
    {
        $id = $request->id;
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(["message" => "Staff not found"], 404);
        }
        if ($staff->permanentAddress) {
            $staff->permanentAddress = json_decode(
                $staff->permanentAddress,
                true
            );
        }
        if ($staff->staff_photo) {
            $staff->staff_photo = asset(
                "storage/app/public/" . $staff->staff_photo
            );
        } else {
            $staff->staff_photo = null; // If no photo, set to null
        }
        if ($staff->communicationAddress) {
            $staff->communicationAddress = json_decode(
                $staff->communicationAddress,
                true
            );
        }
        return response()->json(["staff" => $staff], 200);
    }

    // View all staff
    public function viewAllStaff(Request $request)
    {
        // Retrieve staff where 'isdeleted' is false or NULL
        $query = Staff::query();

        // Apply date filters for date_of_joining if provided
        if (
            $request->has("from_date_joining") &&
            $request->has("to_date_joining")
        ) {
            $fromDateJoining = $request->input("from_date_joining");
            $toDateJoining = $request->input("to_date_joining");

            // Ensure the dates are properly formatted before querying
            $query->whereBetween("date_of_joining", [
                date("Y-m-d", strtotime($fromDateJoining)),
                date("Y-m-d", strtotime($toDateJoining)),
            ]);
        }

        // Apply date filters for date_of_resignation if provided
        if (
            $request->has("from_date_resignation") &&
            $request->has("to_date_resignation")
        ) {
            $fromDateResignation = $request->input("from_date_resignation");
            $toDateResignation = $request->input("to_date_resignation");

            // Ensure correct date format (Y-m-d)
            $fromDateResignation = date(
                "Y-m-d",
                strtotime($fromDateResignation)
            );
            $toDateResignation = date("Y-m-d", strtotime($toDateResignation));

            // Log the query before executing it
            \Log::info("Generated Query:", ["query" => $query->toSql()]);
            \Log::info("Bindings:", ["bindings" => $query->getBindings()]);

            // Force the date format and use raw SQL to ensure correct comparison
            $query->whereRaw("DATE(date_of_resignation) BETWEEN ? AND ?", [
                $fromDateResignation,
                $toDateResignation,
            ]);
        }

        // Get the staff records
        $staff = $query
            ->where("isdeleted", false)
            ->orWhereNull("isdeleted")
            ->get();

        // Decode JSON fields back to arrays
        foreach ($staff as $staffMember) {
            if ($staffMember->permanentAddress) {
                $staffMember->permanentAddress = json_decode(
                    $staffMember->permanentAddress,
                    true
                );
            }

            if ($staffMember->communicationAddress) {
                $staffMember->communicationAddress = json_decode(
                    $staffMember->communicationAddress,
                    true
                );
            }

            if ($staffMember->staff_photo) {
                $staffMember->staff_photo = asset(
                    "storage/app/public/" . $staffMember->staff_photo
                );
            } else {
                $staffMember->staff_photo = null; // If no photo, set to null
            }
        }

        return response()->json(["staff" => $staff], 200);
    }

    // request, $staff, fieldname on API, file path, db column name

    private function handleImageUpdate(
        $request,
        $staff,
        $fieldNameApi,
        $filePath,
        $dbName
    ) {
        try {
            if ($request->has($fieldNameApi)) {
                $base64Image = $request->input($fieldNameApi);

                // Ensure it's a base64 image
                if (
                    preg_match(
                        "/^data:image\/(\w+);base64,/",
                        $base64Image,
                        $matches
                    )
                ) {
                    $extension = $matches[1]; // Extract file extension (e.g., jpg, png)
                    $base64Image = substr(
                        $base64Image,
                        strpos($base64Image, ",") + 1
                    ); // Strip metadata
                    $base64Image = base64_decode($base64Image);

                    if ($base64Image === false) {
                        throw new \Exception(
                            "Base64 decoding failed for field: $fieldNameApi"
                        );
                    }

                    // Generate a unique file name
                    $timestamp = now()->format("Ymd_His");
                    $fileName = uniqid() . "_{$timestamp}." . $extension;
                    $filePathWithName = $filePath . "/" . $fileName;

                    // Save the image to storage
                    $fullPath = Storage::disk("public")->path(
                        $filePathWithName
                    );
                    $isWritten = file_put_contents($fullPath, $base64Image);

                    if ($isWritten === false) {
                        throw new \Exception(
                            "Failed to write file for field: $fieldNameApi"
                        );
                    }

                    // Store only the image name in the database
                    $staff->update([$dbName => $filePathWithName]);

                    // Log success
                    Log::info(
                        "Successfully updated {$fieldNameApi} with file path: {$filePathWithName}"
                    );
                } else {
                    throw new \Exception(
                        "Invalid base64 string for field: $fieldNameApi"
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error("Error updating {$fieldNameApi}: " . $e->getMessage());
        }
    }

    public function deleteStaff(Request $request,$id)
    {
        $id = $request->id;
        // Find the staff by ID
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        // Soft delete by setting isdeleted to true
        $staff->isdeleted = true;
        $staff->save();

        // Optionally, also deactivate or delete the corresponding user
        $user = User::where('roll_no', $staff->staff_id)->first();
        if ($user) {
            // Soft delete or flag the user as inactive, as needed
            $user->delete(); // or set a status column if you prefer not to delete
        }

        return response()->json(['message' => 'Staff deleted successfully'], 200);
    }

}
