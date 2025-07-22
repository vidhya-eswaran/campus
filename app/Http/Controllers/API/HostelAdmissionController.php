<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HostelAdmission;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\HostelAdmissionMail;
use Illuminate\Support\Facades\DB;
use App\Mail\HostelAdmissionUpdatedMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Mail\OtpMailHostelAdmission;
use App\Mail\HostelAdmissionArrivalMail;
use App\Mail\HostelAdmissionSubmitted;
use App\Helpers\LifecycleLogger;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendHostelAdmissionMailsJob;
use Illuminate\Support\Facades\Http;
use App\Helpers\SmsHelper;
use App\Helpers\HelperEmail;

class HostelAdmissionController extends Controller
{
    /**
     * Display a listing of all hostel admissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = HostelAdmission::orderBy("created_at", "desc");

        if ($request->has("acad_year") && $request->acad_year != "") {
            $query->where("acad_year", $request->acad_year);
        }

        $admissions = $query->get();

        return response()->json(["data" => $admissions], 200);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            "email_id" => "required|email",
            "sender_email_id" => "required|email",
        ]);

        $otp = rand(100000, 999999);
        $email = $request->email_id;
        $senderEmail = $request->sender_email_id;

        // Cache OTP for 10 minutes
        Cache::put("otp_" . $email, $otp, now()->addMinutes(10));

        // Send mail
        Mail::to($email)->send((new OtpMailHostelAdmission($otp))->from($senderEmail, 'Hostel Admission'));

        return response()->json(
            ["message" => "OTP sent successfully to " . $email],
            200
        );
    }

    public function verifyOtpAndStore(Request $request)
    {
        $request->validate([
            "otp" => "required|numeric",
            "email_id" => "required|email", // Corrected this to match the cache key and input field
        ]);

        // Retrieve the OTP from cache using the correct key
        $cachedOtp = Cache::get("otp_" . $request->email_id);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return response()->json(
                ["message" => "Invalid or expired OTP"],
                403
            );
        }

        // Validate the rest of the form data
        $validatorStore = Validator::make($request->all(), [
            "student_name" => "required",
            "student_id" => "required|string|max:255",
            "student_class" => "nullable",
            "student_section" => "nullable",
            "father_name" => "nullable|string|max:255",
            "mother_name" => "nullable",
            "father_mobileNo" => "nullable",
            "mother_mobileNo" => "nullable",
            "pa_address_line1" => "nullable|string|max:255",
            "pa_address_line2" => "nullable|string|max:255",
            "pa_city" => "nullable|string|max:100",
            "pa_state" => "nullable|string|max:100",
            "pa_country" => "nullable|string|max:100",
            "pa_pincode" => "nullable|string|max:20",
            "co_address_line1" => "nullable",
            "co_address_line2" => "nullable",
            "co_city" => "nullable",
            "co_state" => "nullable|string|max:100",
            "co_country" => "nullable|string|max:100",
            "co_pincode" => "nullable",
            "gaurdian_name" => "nullable|string|max:255",
            "declaration" => "nullable",
            "father_email_id" => "nullable",
            "mother_email_id" => "nullable",
            "terms_condition" => "nullable",
            "gaurdian_email_id" => "nullable",
            "acad_year" => "nullable",
        ]);

        if ($validatorStore->fails()) {
            return response()->json(
                ["errors" => $validatorStore->errors()],
                422
            );
        }

        // Save the data
        $admission = HostelAdmission::create($validatorStore->validated());
        try {
            // Get student and user
            $student = \App\Models\Student::find($admission->student_id);
            $user = \App\Models\User::where("roll_no", $student->roll_no)
                ->where("admission_no", $student->admission_no)
                ->orderByDesc("id")
                ->first();

            // Insert into payment_notification_datas
            DB::table("payment_notification_datas")->insert([
                "student_id" => $student->id,
                "email" => $student->email_id,
                "txnId" => null,
                "paidAmount" => null,
                "invoice_nos" => null,
                "status" => 1,
                "show_hide" => 1,
                "notification_type" => "web", // default type
                "notification_category" => "submitted_hostel_admission", // updated category
                "urllink" => $admission->id,
                "content" =>
                    "We have received your hostel admission application. Our team will review it and get back to you shortly.",
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            $fatherNumber = $student->father_contact_no ?? null;
            $motherNumber = $student->mother_contact_no ?? null;

            $mobileToSend = $fatherNumber ?? $motherNumber;

            SmsHelper::sendTemplateSms(
                "E Hostel Admission - application by parent",
                $mobileToSend,
                []
            );
            if ($student) {
                // Queue the mail
                // Mail::to($student->email)->send(new HostelAdmissionSubmitted($student));
                Mail::to("civildinesh313@gmail.com")->send(
                    new HostelAdmissionSubmitted($student)
                );
            }
        } catch (\Exception $e) {
            \Log::error("Notification insert failed: " . $e->getMessage());
        }

        // OTP used — remove from cache
        Cache::forget("otp_" . $request->gaurdian_email_id);

        return response()->json(
            [
                "data" => $admission,
                "message" =>
                    "Hostel admission created successfully after OTP verification.",
            ],
            201
        );
    }

    /**
     * Display the specified hostel admission.
     *
     * @param  \App\Models\HostelAdmission  $hostelAdmission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(HostelAdmission $hostelAdmission)
    {
        return response()->json(["data" => $hostelAdmission], 200);
    }

    /**
     * Update the specified hostel admission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HostelAdmission  $hostelAdmission
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $donor = HostelAdmission::findOrFail($id);
        return response()->json([
            'message' => 'HostelAdmission retrieved successfully',
            'data' => $donor,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "student_name" => "required",
            "student_id" => "required|string|max:255",
            "student_class" => "nullable|string|max:255",
            "student_section" => "nullable|string|max:255",
            "father_name" => "nullable|string|max:255",
            "mother_name" => "nullable|string|max:255",
            "father_mobileNo" => "nullable|string|max:20",
            "mother_mobileNo" => "nullable|string|max:20",
            "pa_address_line1" => "nullable|string|max:255",
            "pa_address_line2" => "nullable|string|max:255",
            "pa_city" => "nullable|string|max:100",
            "pa_state" => "nullable|string|max:100",
            "pa_country" => "nullable|string|max:100",
            "pa_pincode" => "nullable|string|max:20",
            "co_address_line1" => "nullable|string|max:255",
            "co_address_line2" => "nullable|string|max:255",
            "co_city" => "nullable|string|max:100",
            "co_state" => "nullable|string|max:100",
            "co_country" => "nullable|string|max:100",
            "co_pincode" => "nullable|string|max:20",
            "gaurdian_name" => "nullable|string|max:255",
            "declaration" => "nullable|string|max:255",
            "father_email_id" => "nullable|email",
            "mother_email_id" => "nullable|email",
            "terms_condition" => "nullable|string",
            "status" => "nullable|in:pending,approved,rejected",
        ]);

        $hostelAdmission = HostelAdmission::create($validated);

        return response()->json([
            'data' => $hostelAdmission,
            'message' => 'Hostel admission created successfully.',
        ], 201);
    }

   public function update(Request $request, $id)
    {
        $id = $request->id;
        $hostelAdmission = HostelAdmission::findOrFail($id);

        $validated = $request->validate([
            "student_name" => "required",
            "student_id" => "required|string|max:255",
            "student_class" => "nullable|string|max:255",
            "student_section" => "nullable|string|max:255",
            "father_name" => "nullable|string|max:255",
            "mother_name" => "nullable|string|max:255",
            "father_mobileNo" => "nullable|string|max:20",
            "mother_mobileNo" => "nullable|string|max:20",
            "pa_address_line1" => "nullable|string|max:255",
            "pa_address_line2" => "nullable|string|max:255",
            "pa_city" => "nullable|string|max:100",
            "pa_state" => "nullable|string|max:100",
            "pa_country" => "nullable|string|max:100",
            "pa_pincode" => "nullable|string|max:20",
            "co_address_line1" => "nullable|string|max:255",
            "co_address_line2" => "nullable|string|max:255",
            "co_city" => "nullable|string|max:100",
            "co_state" => "nullable|string|max:100",
            "co_country" => "nullable|string|max:100",
            "co_pincode" => "nullable|string|max:20",
            "gaurdian_name" => "nullable|string|max:255",
            "declaration" => "nullable|string|max:255",
            "father_email_id" => "nullable|email",
            "mother_email_id" => "nullable|email",
            "terms_condition" => "nullable|string",
            "status" => "nullable|in:pending,approved,rejected",
        ]);

        $hostelAdmission->update($validated);

        return response()->json([
            'data' => $hostelAdmission,
            'message' => 'Hostel admission updated successfully.',
        ]);
    }


    /**
     * Remove the specified hostel admission from storage.
     *
     * @param  \App\Models\HostelAdmission  $hostelAdmission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $hostelAdmission = HostelAdmission::findOrFail($id);
        $hostelAdmission->delete();

        return response()->json([
            "message" => "Hostel admission deleted successfully."
        ], 200); // 204 is No Content – you can't send JSON with that.
    }


    /**
     * Update the status of multiple hostel admissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdateStatus(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            "form_ids" => "required|array|min:1",
            "status" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $updatedCount = 0;

        foreach ($request->form_ids as $formId) {
            // Get the hostel admission record by form_id
            $hostelAdmission = HostelAdmission::where("id", $formId)->first();

            if ($hostelAdmission) {
                // Update status
                $hostelAdmission->status = $request->status;
                $hostelAdmission->save();
                $updatedCount++;
                try {
                    LifecycleLogger::log(
                        "Hostel Application {$hostelAdmission->status}",
                        $hostelAdmission->student_id,
                        "hostel_slip_status_update",
                        [
                            "form_id" => $formId,
                            "status" => $hostelAdmission->status,
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error(
                        "Failed to log hostel accept/reject lifecycle.",
                        [
                            "form_id" => $formId,
                            "student_id" => $hostelAdmission->student_id,
                            "error" => $e->getMessage(),
                        ]
                    );
                }
                // Get student details and send email
                $student = User::find($hostelAdmission->student_id);
                $hostelAdmission->student = $student;

                if ($student && $hostelAdmission->status == "Approved") {
                    DB::table("payment_notification_datas")->insert([
                        "student_id" => $student->id,
                        "email" => $student->email_id,
                        "txnId" => null,
                        "paidAmount" => null,
                        "invoice_nos" => null,
                        "status" => 1,
                        "show_hide" => 1,
                        "show_admin" => 0,
                        "notification_type" => "web", // default type
                        "notification_category" =>
                            "submitted_hostel_admission_confirmed", // updated category
                        "urllink" => $admission->id,
                        "content" =>
                            "Your child’s hostel admission has been confirmed. Further details will be shared shortly.",
                        "created_at" => now(),
                        "updated_at" => now(),
                    ]);
                    $fatherNumber = $student->father_contact_no ?? null;
                    $motherNumber = $student->mother_contact_no ?? null;

                    $mobileToSend = $fatherNumber ?? $motherNumber;

                    SmsHelper::sendTemplateSms(
                        "E Hostel Admission - update Status of hostel application",
                        $mobileToSend,
                        []
                    );
                    // Mail::to($student->email)->send(new HostelAdmissionUpdatedMail($hostelAdmission));
                }
                Mail::to("civildinesh313@gmail.com")->send(
                    new HostelAdmissionUpdatedMail($hostelAdmission)
                );
            }
        }

        return response()->json(
            [
                "message" => "Status updated for {$updatedCount} form(s) and emails queued.",
            ],
            200
        );
    }
    public function bulkArrialDepature(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            "form_ids" => "required|array|min:1",
            "status" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $updatedCount = 0;

        foreach ($request->form_ids as $formId) {
            // Get the hostel admission record by form_id
            $hostelAdmission = HostelAdmission::where("id", $formId)->first();

            if ($hostelAdmission) {
                // Update status
                $hostelAdmission->arr_dep_status = $request->status;
                $hostelAdmission->save();
                $updatedCount++;
                try {
                    if ($hostelAdmission->arr_dep_status == 1) {
                        $statusText = "Arrival";
                    } elseif ($hostelAdmission->arr_dep_status == 2) {
                        $statusText = "Departure";
                    } else {
                        $statusText = "Unknown Arrival/Departure";
                    }
                    LifecycleLogger::log(
                        "Hostel {$statusText}",
                        $hostelAdmission->student_id,
                        "hostel_slip_status_update",
                        [
                            "form_id" => $formId,
                            "status" => $hostelAdmission->arr_dep_status,
                            "status_text" => $statusText,
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error(
                        "Failed to log hostel Arrival/ Departure slip lifecycle.",
                        [
                            "form_id" => $formId,
                            "student_id" => $hostelAdmission->student_id,
                            "error" => $e->getMessage(),
                        ]
                    );
                }
                // Get student details and send email
                $student = User::find($hostelAdmission->student_id);
                $hostelAdmission->student = $student;
                if ($student) {
                    // Determine sub-path based on status
                    $formType = "";
                    if ($hostelAdmission->arr_dep_status == 1) {
                        $formType = "/ArrivalForm";
                    } elseif ($hostelAdmission->arr_dep_status == 2) {
                        $formType = "/DepartureForm";
                    }

                    // Build the query params
                    $query = http_build_query([
                        "slno" => $student->slno,
                        "id" => $student->id,
                        "admission_no" => $student->admission_no,
                        "roll_no" => $student->roll_no,
                        "name" => $student->name,
                        "gender" => $student->gender,
                        "standard" => $student->standard,
                        "twe_group" => $student->twe_group,
                        "sec" => $student->sec,
                        "academic_year" => $student->academic_year,
                        "hostelOrDay" => $student->hostelOrDay,
                        "email" => $student->email,
                    ]);

                    // Use base URL from .env
                    $baseUrl = env(
                        "HOSTEL_FORM_URL",
                        "https://santhoshavidhyalaya.com/svsportaladmintest"
                    ); // fallback if not set

                    // Final URL with sub-path
                    $hostelAdmission->url =
                        rtrim($baseUrl, "/") . $formType . "?" . $query;

                    // Optional: Log it
                    \Log::info("Generated Hostel Form URL", [
                        "student_id" => $student->id,
                        "url" => $hostelAdmission->url,
                    ]);
                }
                if ($student) {
                    // Queue the mail
                    // Mail::to($student->email)->send(new HostelAdmissionArrivalMail($hostelAdmission));
                    Mail::to("dhinakaran.p@eucto.com")->send(
                        new HostelAdmissionArrivalMail($hostelAdmission)
                    );
                }
            }
        }

        return response()->json(
            [
                "message" => "Status updated for {$updatedCount} form(s) and emails queued.",
            ],
            200
        );
    }

    public function storeSendForm(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            "student_ids" => "required|array|min:1",
            "acad_year" => "required",
        ]);

        // Fetch the student IDs from the request
        $studentIds = $request->input("student_ids");
        DB::table("sent_hostel_Admission_form_students")->insert([
            "student_ids" => json_encode($studentIds), // store as JSON array
            "acad_year" => $request->acad_year,
            // 'created_by' => Auth::id(),
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        // Loop through the student IDs and create PendingMail entries
        foreach ($studentIds as $studentId) {
            // Find the student by ID
            $student = Student::find($studentId);

            // If the student doesn't exist, skip to the next
            if (!$student) {
                continue;
            }

            // Fetch the user associated with the student
            $user = User::where("roll_no", $student->roll_no)
                ->where("admission_no", $student->admission_no)
                ->orderByDesc("id")
                ->first();

            if (!empty($user->email)) {
                \App\Models\PendingMail::create([
                    "student_id" => $studentId,
                    "email" => $user->email,
                    "heading" => "Hostel Application Sent",
                    "status" => "pending", // Default status
                    "acad_year" => $request->acad_year,
                ]);
            } else {
                // Handle the case where email is invalid or missing
                Log::warning(
                    "User email is missing or invalid for student ID from Hostel admiison send function storeSendForm : $studentId"
                );
            }
        }

        // Return a success message
        return response()->json(
            ["message" => "Pending mail entries created successfully"],
            200
        );
    }

    public function sendMailsBackground($filename)
    {
        $path = storage_path("app/$filename");

        if (!file_exists($path)) {
            Log::error("File not found: $filename");
            return response()->noContent();
        }

        $data = json_decode(file_get_contents($path), true);
        if (!$data) {
            Log::error("Failed to decode JSON data from file: $filename");
            return response()->noContent();
        }

        // Get the current state
        $studentIds = $data["student_ids"];
        $acadYear = $data["acad_year"];
        $batchSize = 5;
        $totalBatches = $data["total_batches"];
        $currentBatch = $data["current_batch"];
        $processedIds = $data["processed_ids"] ?? [];

        // If all batches are processed, we're done
        if ($currentBatch >= $totalBatches) {
            Log::info("All batches completed for $filename");
            $data["status"] = "completed";
            $data["completed_at"] = now()->toDateTimeString();
            file_put_contents($path, json_encode($data));
            return response()->noContent();
        }

        // Calculate which IDs to process in this batch
        $remainingIds = array_values(array_diff($studentIds, $processedIds));
        $batchIds = array_slice($remainingIds, 0, $batchSize);

        // Update status to processing
        $currentBatch++;
        $data["status"] = "processing";
        $data["current_batch"] = $currentBatch;
        $data["last_updated"] = now()->toDateTimeString();
        file_put_contents($path, json_encode($data));

        Log::info("Processing batch $currentBatch of $totalBatches");

        $sentCount = $data["sent_count"] ?? 0;
        $failedCount = $data["failed_count"] ?? 0;
        $results = $data["results"] ?? [];

        // Process this batch
        foreach ($batchIds as $studentId) {
            $student = \App\Models\Student::find($studentId);
            if (!$student) {
                Log::warning("Student not found: ID $studentId");
                $failedCount++;
                $results[] = [
                    "student_id" => $studentId,
                    "status" => "failed",
                    "reason" => "Student not found",
                    "timestamp" => now()->toDateTimeString(),
                ];
                $processedIds[] = $studentId;
                continue;
            }

            Log::info(
                "Processing student: Roll No: " .
                    $student->roll_no .
                    ", Admission No: " .
                    $student->admission_no
            );
            $user = \App\Models\User::where("roll_no", $student->roll_no)
                ->where("admission_no", $student->admission_no)
                ->orderByDesc("id")
                ->first();

            if ($user) {
                $user->acad_year = $acadYear;
                try {
                    Log::info(
                        "Sending email to: " . $user->email . " (Attempt 1)"
                    );
                    \Mail::to("civildinesh313@gmail.com")->send(
                        new \App\Mail\HostelAdmissionMail($user)
                    );
                    $sentCount++;
                    Log::info(
                        "Email sent to: " .
                            $user->email .
                            " (Sent $sentCount of " .
                            count($studentIds) .
                            ")"
                    );

                    $results[] = [
                        "student_id" => $studentId,
                        "email" => $user->email,
                        "status" => "sent",
                        "timestamp" => now()->toDateTimeString(),
                    ];
                } catch (\Exception $e) {
                    if (strpos($e->getMessage(), "Ratelimit") !== false) {
                        // If we hit a rate limit, save our progress and stop processing
                        // The next batch will pick up where we left off
                        Log::warning(
                            "Ratelimit hit. Stopping batch processing: " .
                                $e->getMessage()
                        );

                        $results[] = [
                            "student_id" => $studentId,
                            "email" => $user->email,
                            "status" => "rate_limited",
                            "message" => $e->getMessage(),
                            "timestamp" => now()->toDateTimeString(),
                        ];

                        // Save our progress but don't mark this ID as processed
                        // so we'll retry it in the next batch
                        $data["status"] = "rate_limited";
                        $data["current_batch"] = $currentBatch - 1; // Reprocess this batch
                        $data["sent_count"] = $sentCount;
                        $data["failed_count"] = $failedCount;
                        $data["results"] = $results;
                        $data["processed_ids"] = $processedIds;
                        $data["rate_limited_at"] = now()->toDateTimeString();
                        $data["retry_after"] = now()
                            ->addMinutes(5)
                            ->toDateTimeString();
                        file_put_contents($path, json_encode($data));

                        // Schedule the next batch after a longer delay
                        $this->scheduleNextBatch($filename, 300); // 5 minutes

                        return response()->noContent();
                    } else {
                        // Other error
                        Log::error(
                            "Failed to send email to: " .
                                $user->email .
                                " - " .
                                $e->getMessage()
                        );
                        $failedCount++;

                        $results[] = [
                            "student_id" => $studentId,
                            "email" => $user->email,
                            "status" => "failed",
                            "reason" => $e->getMessage(),
                            "timestamp" => now()->toDateTimeString(),
                        ];
                    }
                }
            } else {
                Log::warning("User not found for hostel admission", [
                    "roll_no" => $student->roll_no,
                    "admission_no" => $student->admission_no,
                ]);

                $failedCount++;
                $results[] = [
                    "student_id" => $studentId,
                    "status" => "failed",
                    "reason" => "User not found",
                    "timestamp" => now()->toDateTimeString(),
                ];
            }

            $processedIds[] = $studentId;

            // Add a small delay between emails
            sleep(2);
        }

        // Update the file with our progress
        $data["sent_count"] = $sentCount;
        $data["failed_count"] = $failedCount;
        $data["results"] = $results;
        $data["processed_ids"] = $processedIds;
        $data["last_batch_completed"] = now()->toDateTimeString();
        file_put_contents($path, json_encode($data));

        // If we've processed all batches, we're done
        if ($currentBatch >= $totalBatches) {
            Log::info("All batches completed for $filename");
            $data["status"] = "completed";
            $data["completed_at"] = now()->toDateTimeString();
            file_put_contents($path, json_encode($data));
            return response()->noContent();
        }

        // Schedule the next batch
        Log::info("Batch $currentBatch completed. Scheduling next batch.");
        $this->scheduleNextBatch($filename, 60); // 60 seconds delay between batches

        return response()->noContent();
    }

    private function scheduleNextBatch($filename, $delay = 60)
    {
        // Schedule the next batch using a fire-and-forget HTTP request
        $url = url("/api/process-mail-batch/$filename?token=abc123");

        // Use exec to make a background curl request that won't time out
        $cmd = "sleep $delay && curl -s '$url' > /dev/null 2>&1 &";
        Log::info("Scheduling next batch with command: $cmd");
        exec($cmd);

        return true;
    }

    public function checkMailStatus($filename)
    {
        $path = storage_path("app/$filename");

        if (!file_exists($path)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Batch not found",
                ],
                404
            );
        }

        $data = json_decode(file_get_contents($path), true);

        $progress = [
            "current_batch" => $data["current_batch"] ?? 0,
            "total_batches" => $data["total_batches"] ?? 0,
            "sent_count" => $data["sent_count"] ?? 0,
            "failed_count" => $data["failed_count"] ?? 0,
            "total_emails" => $data["total_emails"] ?? 0,
            "percent_complete" =>
                $data["total_batches"] > 0
                    ? round(
                        ($data["current_batch"] / $data["total_batches"]) * 100,
                        2
                    )
                    : 0,
        ];

        return response()->json([
            "status" => $data["status"] ?? "unknown",
            "progress" => $progress,
            "created_at" => $data["created_at"] ?? null,
            "last_updated" => $data["last_updated"] ?? null,
            "completed_at" => $data["completed_at"] ?? null,
            "retry_after" => $data["retry_after"] ?? null,
        ]);
    }
}
