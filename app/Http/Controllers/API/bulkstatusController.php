<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\AdmissionForm;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class bulkstatusController extends Controller
{
    public function status(Request $request)
    {
        $requestData = $request->validate([
            "ids" => "nullable|array",
            "status" => "nullable|string",
        ]);

        $ids = $requestData["ids"];
        $status = $requestData["status"];

        // Get today's date in Indian format (dd/mm/yyyy)
        $todayDate = Carbon::now()->format("d/m/Y");

        try {
            DB::transaction(function () use ($ids, $status, $todayDate) {
                foreach ($ids as $id) {
                    $student = Student::find($id);
                    if ($student) {
                        $student->status = $status;
                        $student->save(); // Always update the student

                        // Log the student update
                        Log::info(
                            "Student ID {$id} status updated to {$status} on {$todayDate}"
                        );

                        // Update AdmissionForm only if it exists
                        $admission = AdmissionForm::find(
                            $student->admission_id
                        );
                        if ($admission) {
                            $admission->status = $status;
                            $admission->save();
                            if (strtolower((string) $status) === "active") {
                                //   if (strtolower($request->status) === 'active') {
                                // Check if admission_no already exists
                                if (!$student->roll_no) {
                                    // Generate a new unique admission number
                                    $currentYear = now()->format("Y"); // Current year
                                    $yearCode = substr($currentYear, -2); // Last 2 digits of the year

                                    // Determine class code
                                    $classOfJoining = strtolower(
                                        $student->SOUGHT_STD
                                    );
                                    if ($classOfJoining === "lkg") {
                                        $classCode = "13";
                                    } elseif ($classOfJoining === "ukg") {
                                        $classCode = "14";
                                    } else {
                                        $classCode = str_pad(
                                            $classOfJoining,
                                            2,
                                            "0",
                                            STR_PAD_LEFT
                                        ); // Pad numeric classes
                                    }

                                    // Generate base admission number (YYCC)
                                    $baseAdmissionNo = $yearCode . $classCode;

                                    // Check the last admission number for the current year and class
                                    $lastAdmissionNo = Student::where(
                                        "roll_no",
                                        "like",
                                        $baseAdmissionNo . "%"
                                    )
                                        ->orderBy("admission_no", "desc")
                                        ->value("roll_no");

                                    // Extract the serial number and increment
                                    if ($lastAdmissionNo) {
                                        $lastSerial = (int) substr(
                                            $lastAdmissionNo,
                                            -2
                                        ); // Get the last 2 digits
                                        $newSerial = str_pad(
                                            $lastSerial + 1,
                                            2,
                                            "0",
                                            STR_PAD_LEFT
                                        ); // Increment and pad to 2 digits
                                    } else {
                                        $newSerial = "01"; // Start with 01 if no existing records
                                    }

                                    // Combine base admission number with the new serial number
                                    $newAdmissionNo =
                                        $baseAdmissionNo . $newSerial;

                                    // Update the student's admission_no
                                    $student->update([
                                        "roll_no" => $newAdmissionNo,
                                    ]);
                                }

                                if (!$student->admission_no) {
                                    $lastAdmissionNo = User::where(
                                        "admission_no",
                                        "like",
                                        "%SV%"
                                    )
                                        ->whereRaw("LENGTH(admission_no) = 12")
                                        ->orderByRaw(
                                            "STR_TO_DATE(SUBSTRING(admission_no, 3, 6), '%d%m%y') DESC"
                                        )
                                        ->orderByRaw(
                                            "CAST(SUBSTRING(admission_no, 9, 4) AS UNSIGNED) DESC"
                                        )
                                        ->first();

                                    // Define the format for the new admission number
                                    $format = "SV" . date("dmy");

                                    if ($lastAdmissionNo) {
                                        // Extract the numeric part of the last admission number
                                        $lastNumber = intval(
                                            substr(
                                                $lastAdmissionNo->admission_no,
                                                8
                                            )
                                        );

                                        // Check if the last number is 9999, reset to 0001 if it is
                                        if ($lastNumber === 9999) {
                                            $newNumber = 1;
                                        } else {
                                            // Increment the last number by 1
                                            $newNumber = $lastNumber + 1;
                                        }
                                    } else {
                                        // If no previous admission numbers found, start with 0001
                                        $newNumber = 1;
                                    }

                                    // Pad the new number with leading zeros to make it 4 digits
                                    $newNumberPadded = str_pad(
                                        $newNumber,
                                        4,
                                        "0",
                                        STR_PAD_LEFT
                                    );

                                    // Combine the format and new number to create the new admission number
                                    $newAdmissionNo =
                                        $format . $newNumberPadded;

                                    // Assuming you have a function to generate a unique serial number
                                    // $serialNo = generateSerialNumber(); // Replace this with your serial number generation logic

                                    $admissionId = $newAdmissionNo;
                                    $student->update([
                                        "admission_no" => $admissionId,
                                    ]);
                                }

                                if (
                                    !User::where("roll_no", $student->roll_no)
                                        ->where("name", $student->STUDENT_NAME)
                                        ->exists()
                                ) {
                                    $user = new User();

                                    // Get the last ID and increment it
                                    $lastid =
                                        User::latest("id")->value("id") ?? 0;
                                    $lastid = $lastid + 1;

                                    $user->id = $lastid;
                                    $user->name =
                                        $student->STUDENT_NAME ?? null;
                                    $user->gender = $student->SEX ?? null;
                                    $user->email = $student->EMAIL_ID ?? null;
                                    $user->standard =
                                        $student->SOUGHT_STD ?? null;
                                    $user->sec = $student->sec ?? null;
                                    $user->hostelOrDay = "hostel";
                                    $user->password = Hash::make("svs@123");
                                    $user->admission_no =
                                        $student->admission_no ?? null;
                                    $user->roll_no = $student->roll_no ?? null;

                                    $user->save();
                                }

                                try {
                                    $userId = User::where(
                                        "roll_no",
                                        $student->roll_no
                                    )
                                        ->latest("id")
                                        ->value("id");

                                    LifecycleLogger::log(
                                        "Application Status Updated to Active",
                                        $userId,
                                        "application_status_activation",
                                        [
                                            "student_name" =>
                                                $student->STUDENT_NAME,
                                            "admission_no" =>
                                                $student->admission_no,
                                            "roll_no" => $student->roll_no,
                                        ]
                                    );
                                } catch (\Exception $e) {
                                    \Log::error(
                                        "Failed to log application active lifecycle.  Status Updated to Active",
                                        [
                                            "user_id" => $userId ?? "N/A",
                                            "error" => $e->getMessage(),
                                        ]
                                    );
                                }
                            }

                            // Log the AdmissionForm update

                            Log::info(
                                "AdmissionForm ID {$admission->id} status updated to {$status} on {$todayDate}"
                            );
                        } else {
                            // Log if AdmissionForm is not found
                            Log::warning(
                                "AdmissionForm not found for Student ID {$id} on {$todayDate}"
                            );
                        }
                    } else {
                        // Log if Student is not found
                        Log::error(
                            "Student ID {$id} not found on {$todayDate}"
                        );
                    }
                }
            });

            return response()->json(
                ["message" => "Status updated successfully"],
                200
            );
        } catch (\Exception $e) {
            // Log the error
            Log::error(
                "Error updating status on {$todayDate}: " . $e->getMessage()
            );

            return response()->json(
                [
                    "message" => "Error updating status",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
