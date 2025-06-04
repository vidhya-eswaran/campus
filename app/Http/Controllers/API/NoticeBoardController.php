<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NoticeBoard;
use App\Models\User;
use App\Models\UserNotification;

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
    public function index()
    {
        $notices = NoticeBoard::with('categoryDetails')->orderBy('id', 'desc')->where('delete_status', 0)->get();
        return response()->json($notices);
    }

    // Store a new notice with file upload
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|integer',
            'notice_message' => 'required|string',
            'file' => 'nullable|string', // Base64 file should be sent as string
        ]);

        // Handle file upload if present
        if ($request->has('file')) {
            $validated['file'] = $this->handleBase64File($request->file, $request->file_name, 'noticeboard');
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
        $notice = NoticeBoard::findOrFail($id);

        $validated = $request->validate([
            'category' => 'required|integer',
            'notice_message' => 'required|string',
            'file' => 'nullable|string', // Base64 file is sent as a string
        ]);

        // Handle Base64 file upload
        if ($request->has('file')) {
            // Delete the old file if exists
            if ($notice->file) {
                $this->deleteOldFile($notice->file, 'noticeboard');
            }

            $validated['file'] = $this->handleBase64File($request->file, $request->file_name, 'noticeboard');
        }

        // Update the notice
        $notice->update($validated);

        return response()->json([
            'message' => 'Notice updated successfully',
            'notice' => $notice
        ]);
    }

    // Get a single notice by ID
    public function viewbyid($id)
    {
        $notice = NoticeBoard::with('categoryDetails')->findOrFail($id);
        return response()->json([
            'message' => 'Notice retrieved successfully',
            'notice' => $notice
        ]);
    }

    // Soft delete: update delete_status instead of deleting
    public function destroy($id)
    {
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
