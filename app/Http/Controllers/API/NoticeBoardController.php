<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NoticeBoard;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class NoticeBoardController extends Controller
{
    private $base_url = "https://www.santhoshavidhyalaya.com/SVSTEST/"; // Base URL without public/

    // Allowed MIME types and corresponding extensions
    private $mimeToExtension = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/octet-stream' => 'xlsx', // XLSX sometimes returns octet-stream
        'application/zip' => 'xlsx', // XLSX files may be zip-encoded internally
    ];

    // Fetch all notices (only non-deleted)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $notices = NoticeBoard::with('categoryDetails')->orderBy('id', 'desc')->where('delete_status', 0)->paginate($perPage);
        return response()->json($notices);
    }

    // Store a new notice with file upload
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required',
            'notice_message' => 'required|string',
            'file' => 'nullable', // Base64 file should be sent as string
            'createdBy' => 'required',
        ]);

        $schoolSlug = request()->route('school');

        // Handle file upload if present
        if ($request->has('file')) {
                $file = $request->file('file');
                $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/NoticeBoard/' . $filename;       

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $validated['file'] = Storage::disk('s3')->url($path);
        }

        $notice = NoticeBoard::create($validated);
        $notifications = [];

        // Send notifications to all active users
        $announcementrolesusers = User::where('status', 1)->get();
        foreach ($announcementrolesusers as $usersdata) {
            $notifications[] = [
                'user_id' => $usersdata->id,
                'notification_type' => "Notice",
                'notification_category' => $request->category,
                'notification_text' => $request->notice_message,
                'schedule_time' => null,
                'send_status' => 'sent',
                'status' => 'unread',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        UserNotification::insert($notifications);

        return response()->json([
            'message' => 'Notice created successfully',
            'notice' => $notice
        ], 201);
    }

    // Update an existing notice with file upload
    public function update(Request $request, $id)
    {
        $id = $request->id;
        $notice = NoticeBoard::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required',
            'notice_message' => 'required|string',
            'file' => 'nullable', // Base64 file is sent as a string
            'createdBy' => 'required',
        ]);

        $schoolSlug = request()->route('school');

        // Handle Base64 file upload
        if ($request->has('file')) {
                $file = $request->file('file');
                $filename = now()->format('Ymd_His') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $path = 'documents/' . $schoolSlug . '/NoticeBoard/' . $filename;              

                // Upload to S3
                Storage::disk('s3')->put($path, file_get_contents($file));

                // Get public URL
                $validated['file'] = Storage::disk('s3')->url($path);
        }

        // Update the notice
        $notice->update($validated);

        return response()->json([
            'message' => 'Notice updated successfully',
            'notice' => $notice
        ]);
    }

    // Get a single notice by ID
    public function viewbyid(Request $request,$id)
    {
        $id = $request->id;
        $notice = NoticeBoard::with('categoryDetails')->findOrFail($id);
        return response()->json([
            'message' => 'Notice retrieved successfully',
            'notice' => $notice
        ]);
    }

    // Soft delete: update delete_status instead of deleting
    public function destroy(Request $request,$id)
    {
        $id = $request->id;
        $notice = NoticeBoard::findOrFail($id);

        // Delete file if exists
        if ($notice->file) {
            $this->deleteOldFile($notice->file, 'noticeboard');
        }

        // Soft delete by updating delete_status
        $notice->update(['delete_status' => 1]);

        return response()->json(['message' => 'Notice deleted successfully']);
    }

    // Handle base64 file upload
    private function handleBase64File($base64File, $fileName, $folder)
    {
        if (preg_match('/^data:(\w+\/[\w\-.+]+);base64,/', $base64File, $matches)) {
            $fileType = $matches[1]; // Get MIME type (e.g., image/jpeg, application/pdf)

            // Check if the MIME type is allowed and get the correct extension
            if (array_key_exists($fileType, $this->mimeToExtension)) {
                $extension = $this->mimeToExtension[$fileType];
            } else {
                return response()->json(['error' => 'Invalid file type'], 400);
            }

            // Decode Base64 data
            $decodedData = base64_decode(preg_replace('/^data:\w+\/[\w\-.+]+;base64,/', '', $base64File));

            // Generate a unique file name with a timestamp
            // $fileName = time() . '_noticeboard.' . $extension;

            // Define the destination path in public_html/noticeboard
            $destinationPath = $_SERVER['DOCUMENT_ROOT'] . "/SVSTEST/$folder/";

            // Save the decoded data to the target path
            file_put_contents($destinationPath . $fileName, $decodedData);

            // Generate the public URL for the file
            return $this->base_url . "$folder/" . $fileName;
        }

        return null;
    }

    // Delete old file from server
    private function deleteOldFile($fileUrl, $folder)
    {
        $fileName = basename($fileUrl);
        $filePath = $_SERVER['DOCUMENT_ROOT'] . "/SVSTEST/$folder/" . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
