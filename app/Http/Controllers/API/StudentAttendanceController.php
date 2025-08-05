<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PushNotificationController;


class StudentAttendanceController extends Controller
{
    // Get all attendance records
    public function index(Request $request)
    {
        try {
            $query = StudentAttendance::query();

            // Apply filters only if the field is present in the query
            if ($request->filled("roll_no")) {
                $query->where("roll_no", $request->roll_no);
            }

            if ($request->filled("name")) {
                $query->where("name", "like", "%" . $request->name . "%");
            }

            if ($request->filled("standard")) {
                $query->where("standard", $request->standard);
            }

            if ($request->filled("sec")) {
                $query->where("sec", $request->sec);
            }

            if ($request->filled("twe_group")) {
                $query->where("twe_group", $request->twe_group);
            }

            if ($request->filled("attendance")) {
                $query->where("attendance", $request->attendance);
            }

            if ($request->filled("remarks")) {
                $query->where("remarks", "like", "%" . $request->remarks . "%");
            }
            if ($request->filled("date")) {
                $query->whereDate("date", $request->date);
            }      

            $attendances = $query->with('student:roll_no,profile_image')->orderBy("roll_no")->get();

            return response()->json(["data" => $attendances], 200);
        } catch (\Exception $e) {
            Log::error(
                "Error fetching attendance records: " . $e->getMessage()
            );
            return response()->json(
                ["message" => "Something went wrong."],
                500
            );
        }
    }

    // Store a single attendance record
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "roll_no" => "required|integer",
                "name" => "required|string|max:100",
                "standard" => "nullable",
                "sec" => "nullable",
                "twe_group" => "nullable",
                "attendance" => "nullable|string|max:10",
                "remarks" => "nullable|string",
                "created_by" => "nullable|string|max:100",
                "count" => "nullable|string|max:50",
                "date" => "nullable|date",
            ]);

            $attendance = StudentAttendance::create($validated);
            return response()->json(
                [
                    "message" => "Attendance saved successfully.",
                    "data" => $attendance,
                ],
                201
            );
        } catch (\Exception $e) {
            Log::error("Error storing attendance: " . $e->getMessage());
            return response()->json(
                ["message" => "Failed to store attendance."],
                500
            );
        }
    }

    // Show a single record
    public function show(Request $request, $id)
    {
        try {
            $id = $request->id;
            $attendance = StudentAttendance::find($id);

            if (!$attendance) {
                return response()->json(
                    ["message" => "Record not found."],
                    404
                );
            }

            return response()->json(["data" => $attendance], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching attendance by ID: " . $e->getMessage());
            return response()->json(
                ["message" => "Something went wrong."],
                500
            );
        }
    }

    // Update a single record
    public function update(Request $request, $id)
    {
        try {
            $id = $request->id;
            $attendance = StudentAttendance::find($id);

            if (!$attendance) {
                return response()->json(
                    ["message" => "Record not found."],
                    404
                );
            }

            $attendance->update($request->all());
            return response()->json(
                [
                    "message" => "Attendance updated successfully.",
                    "data" => $attendance,
                ],
                200
            );
        } catch (\Exception $e) {
            Log::error("Error updating attendance: " . $e->getMessage());
            return response()->json(
                ["message" => "Failed to update attendance."],
                500
            );
        }
    }

    // Delete a record
    public function destroy(Request $request,$id)
    {
        try {
            $id = $request->id;
            $attendance = StudentAttendance::find($id);

            if (!$attendance) {
                return response()->json(
                    ["message" => "Record not found."],
                    404
                );
            }

            $attendance->delete();
            return response()->json(
                ["message" => "Attendance deleted successfully."],
                200
            );
        } catch (\Exception $e) {
            Log::error("Error deleting attendance: " . $e->getMessage());
            return response()->json(
                ["message" => "Failed to delete attendance."],
                500
            );
        }
    }


    public function storeBulk(Request $request)
    {
        try {
            $data = $request->all();

            // Log the entire incoming data to see what you're working with
            Log::info("Bulk data received:", ["data" => $data]);

            // Loop through each record
            foreach ($data as $item) {
                // Log the current item being processed
                Log::info("Processing item:", ["item" => $item]);

                // Get current timestamp
                $now = now();
                Log::info("Current timestamp:", ["timestamp" => $now]);

                // Match on these fields to find existing records
                $match = [
                    "roll_no" => $item["roll_no"] ?? null,
                    "name" => $item["name"] ?? null,
                    "standard" => $item["standard"] ?? null,
                    "sec" => $item["sec"] ?? null,
                    "twe_group" => $item["twe_group"] ?? null,
                    "date" => $item["date"] ?? null,
                ];

                // Log the match conditions for each item
                Log::info("Match conditions:", ["match" => $match]);

                // Check if necessary fields are missing
                if (
                    !$item["roll_no"] ||
                    !$item["name"] ||
                    !$item["standard"] ||
                    !$item["date"]
                ) {
                    // Log the record and skip it
                    Log::warning("Missing required fields, skipping record:", [
                        "item" => $item["roll_no"],
                    ]);
                    continue;
                }

                // Fields to insert or update
                $update = [
                    "attendance" => $item["attendance"] ?? null,
                    "remarks" => $item["remarks"] ?? null,
                    "roll_no" => $item["roll_no"],
                    "name" => $item["name"],
                    "standard" => $item["standard"],
                    "sec" => $item["sec"],
                    "twe_group" => $item["twe_group"],
                    "date" => $item["date"],
                    "academic_year" => $item["academic_year"] ?? null,
                    "created_at" => $now, // used only on insert
                    "updated_at" => $now, // used for both insert and update
                ];

                // Log the update data
                Log::info("Update data:", ["update" => $update]);

                // Attempt to update or insert the record
                try {
                    StudentAttendance::updateOrInsert($match, $update);
                    Log::info("Successfully processed record:", [
                        "match" => $match,
                        "update" => $update,
                    ]);

                    //push notification
                    $user_details = User::where("roll_no", $item["roll_no"])->first();

                    $title = 'Student Attendance Update';
                    $body = 'Your Attendance update.';
                    $deviceToken = $user_details->device_token;
                    $type = 'Attendance';
                    $toUserId = $user_details->id;
                    $data = [
                        'student_id' => $user_details->id,
                        'date' => now()->toDateString(),
                    ];

                    $response = PushNotificationController::sendPushNotification(
                        $title,
                        $body,
                        $type,
                        $data,
                        $toUserId,
                        $deviceToken
                    );
                } catch (\Exception $e) {
                    // Log any errors during the insert or update
                    Log::error("Error in updateOrInsert:", [
                        "match" => $match,
                        "update" => $update,
                        "error" => $e->getMessage(),
                    ]);
                }
            }

            return response()->json(
                ["message" => "Records processed successfully."],
                201
            );
        } catch (\Exception $e) {
            // Log the top-level exception if it occurs
            Log::error("Bulk upsert error: " . $e->getMessage(), [
                "error" => $e,
            ]);

            return response()->json(
                ["message" => "Bulk operation failed."],
                500
            );
        }
    }
}
