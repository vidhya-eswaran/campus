<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    // Store new message with attachments
    public function store(Request $request)
    {
        // dd($validated);

        // Validate the incoming request
        $validated = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'subject'     => 'required|string|max:255',
            'message'     => 'required|string',
            'about'       => 'nullable|integer',
            'attachments.*' => 'nullable|file|max:5120'
        ]);
        // Create the message record
        $message = Message::create([
            'user_id' => $validated['user_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'about'   => $validated['about'] ?? null,
            'replies' => json_encode([]),
            'status' => 'Pending',
        ]);       

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Get original filename
                $originalName = $file->getClientOriginalName();

                // Create unique filename
                $fileName = time() . '_' . $originalName;

                // Store the file in storage/app/public/message_attachments
                $path = $file->storeAs('public/message_attachments', $fileName);

                // Save only the relative public path for URL access
                $publicPath = Storage::url($path); // returns /storage/message_attachments/filename

                $message->attachments()->create([
                    'file_name' => $fileName,
                    'file_path' => $publicPath,
                ]);
            }
        }

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load('attachments')
        ], 201);
    }

    // Reply to an existing message with attachments
    public function reply(Request $request, $id)
    {
        $id = $request->id;
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        // Get the parent message
        $parent = Message::findOrFail($id);

        // Prepare the reply data
        $replyData = [
            'user_id' => $validated['user_id'],
            'message' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
            'status' => 'Closed'
        ];

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];

            foreach ($request->file('attachments') as $file) {
                // Get original filename
                $originalName = $file->getClientOriginalName();

                // Create unique filename
                $fileName = time() . '_' . $originalName;

                // Define upload path - use absolute path
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/public/message_attachments';

                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Move the file
                $file->move($uploadPath, $fileName);

                // Store attachment info with the correct URL path
                $attachments[] = [
                    'file_name' => $fileName,
                    'file_path' => '/SVSTEST/public/message_attachments/' . $fileName,
                ];
            }

            // Add attachments to reply data
            $replyData['attachments'] = $attachments;
        }

        // Decode existing replies and add the new reply
        $replies = json_decode($parent->replies, true) ?? [];
        $replies[] = $replyData;

        // Update the parent message
        $parent->update([
            'replies' => json_encode($replies),
        ]);

        return response()->json([
            'message' => 'Reply added successfully',
            'data' => $parent->load('attachments')
        ]);
    }

    // Get all messages with proper file URLs
   public function allMessages(Request $request)
    {
        $query = Message::with(['attachments', 'user' => function ($q) {
            $q->select(
                'id',
                'admission_no',
                'roll_no',
                'name',
                'gender',
                'standard',
                'twe_group',
                'sec',
                'academic_year',
                'hostelOrDay',
                'email'
            );
        }])->orderByDesc('created_at');

        if ($request->has('user_id')) {
            $userId = $request->input('user_id');

            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                ->orWhereJsonContains('replies', ['user_id' => (int) $userId]);
            });
        }

        $messages = $query->get();
        $result = [];
        $baseUrl = 'https://euctocampus.blaccdot.com'; // Your base URL

        foreach ($messages as $message) {
            // Parent message attachments
            $messageAttachments = collect($message->attachments)->map(function ($attachment) use ($baseUrl) {
                return [
                    'file_name' => $attachment->file_name,
                    'file_url' => $baseUrl . $attachment->file_path,
                    'download_url' => route('download.file', $attachment->file_name),
                ];
            })->toArray();

            // Format replies
            $replies = [];
            $replyList = json_decode($message->replies, true) ?? [];

            foreach ($replyList as $reply) {
                $replyAttachments = collect($reply['attachments'] ?? [])->map(function ($attachment) use ($baseUrl) {
                    return [
                        'file_name' => $attachment['file_name'],
                        'file_url' => $baseUrl . $attachment['file_path'],
                        'download_url' => route('download.file', $attachment['file_name']),
                    ];
                })->toArray();

                $replies[] = [
                    'user_id' => $reply['user_id'],
                    'replied_to_user_id' => $reply['replied_to_user_id'] ?? null,
                    'message' => $reply['message'],
                    'attachments' => $replyAttachments,
                    'timestamp' => $reply['created_at'] ?? null,
                    'type' => 'reply'
                ];
            }

            // Get about name from category master
            $about = DB::table('message_category_master')
                ->where('id', $message->about)
                ->value('messageCategory');

            // Append to result
            $result[] = [
                'id' => $message->id,
                'about' => $message->about,
                'about_name' => $about,
                'status' => $message->status,
                'user_id' => $message->user_id,
                'user' => optional($message->user), // In case user relation is null
                'subject' => $message->subject,
                'message' => $message->message,
                'attachments' => $messageAttachments,
                'created_at' => $message->created_at,
                'type' => 'message',
                'replies' => $replies
            ];
        }

        return response()->json(['data' => $result]);

    }


    // View single message with proper file URLs
    public function viewSingleMessage(Request $request,$id)
    {
        $id = $request->id;
        $message = Message::with(['attachments', 'user'])->findOrFail($id);
        $flattened = [];
        $baseUrl = 'https://www.santhoshavidhyalaya.com'; // Your domain

        // Process parent message attachments
        $messageAttachments = [];
        foreach ($message->attachments as $attachment) {
            $messageAttachments[] = [
                'file_name' => $attachment->file_name,
                'file_url' => $baseUrl . $attachment->file_path,
                'download_url' => route('download.file', $attachment->file_name)
            ];
        }

        // Add parent message
        $flattened[] = [
            'id' => $message->id,
            'user_id' => $message->user_id,
            'user' => $message->user,
            'subject' => $message->subject,
            'message' => $message->message,
            'attachments' => $messageAttachments,
            'created_at' => $message->created_at,
            'type' => 'message',
            'status' => $message->status,
        ];

        // Process replies
        $replies = json_decode($message->replies, true) ?? [];
        foreach ($replies as $reply) {
            // Process reply attachments if any
            $replyAttachments = [];
            if (isset($reply['attachments']) && is_array($reply['attachments'])) {
                foreach ($reply['attachments'] as $attachment) {
                    $replyAttachments[] = [
                        'file_name' => $attachment['file_name'],
                        'file_url' => $baseUrl . $attachment['file_path'],
                        'download_url' => route('download.file', $attachment['file_name'])
                    ];
                }
            }

            // Add reply
            $flattened[] = [
                'user_id' => $reply['user_id'],
                'replied_to_user_id' => $reply['replied_to_user_id'] ?? null,
                'message' => $reply['message'],
                'attachments' => $replyAttachments,
                'timestamp' => $reply['created_at'] ?? null,
                'type' => 'reply',
                'parent_id' => $message->id,
            ];
        }

        return response()->json(['data' => $flattened]);
    }

    // Direct file download method for ALL file types
    public function downloadFile(Request $request)
    {
        $fileName = $request->fileName;
        $relativePath = 'public/message_attachments/' . $fileName;

        // Check if file exists in storage
        if (!Storage::exists($relativePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Get file content and mime type
        $file = Storage::get($relativePath);
        $type = Storage::mimeType($relativePath);

        // Return file as download response
        return Response::make($file, 200)
            ->header("Content-Type", $type)
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}