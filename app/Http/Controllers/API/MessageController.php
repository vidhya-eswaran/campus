<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

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

        // Handle the file uploads if they exist
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Get original filename and extension
                $originalName = $file->getClientOriginalName();

                // Create unique filename to prevent overwriting
                $fileName = time() . '_' . $originalName;

                // Define upload path - use absolute path for your server structure
                // This path should be directly accessible via web
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/public/message_attachments';

                // Create directory if it doesn't exist
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }

                // Move the file to the destination
                $file->move($uploadPath, $fileName);

                // Save attachment record with the correct URL path that works
                $message->attachments()->create([
                    'file_name' => $fileName,
                    'file_path' => '/SVSTEST/public/message_attachments/' . $fileName,
                ]);
            }
        }

        // Return a success response with the message data and its attachments
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
    $baseUrl = 'https://www.santhoshavidhyalaya.com'; // Your domain

    foreach ($messages as $message) {
        // Format parent message attachments
        $messageAttachments = [];
        foreach ($message->attachments as $attachment) {
            $messageAttachments[] = [
                'file_name' => $attachment->file_name,
                // 'file_url' => $baseUrl . $attachment->file_path,
                'download_url' => route('download.file', $attachment->file_name)
            ];
        }

        // Format replies
        $replies = [];
        $replyList = json_decode($message->replies, true) ?? [];

        foreach ($replyList as $reply) {
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

            $replies[] = [
                'user_id' => $reply['user_id'],
                'replied_to_user_id' => $reply['replied_to_user_id'] ?? null,
                'message' => $reply['message'],
                'attachments' => $replyAttachments,
                'timestamp' => $reply['created_at'] ?? null,
                'type' => 'reply'
            ];
        }
  $about = DB::table('message_category_master')
                ->where('id', $message->about)
                ->value('messageCategory');
        // Final parent message with replies in single entry
        $result[] = [
            'id' => $message->id,
            'about' => $message->about,
            'status' => $message->status,
            'about_name' => $about,
            'user_id' => $message->user_id,
            'user' => $message->user,
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
    public function downloadFile($fileName)
    {
        // Use the correct absolute path that works with your server
        $path = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/public/message_attachments/' . $fileName;

        if (!File::exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        // Get file content
        $file = File::get($path);

        // Get MIME type
        $type = File::mimeType($path);

        // Force download for all file types
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        // This header forces download for all file types
        $response->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}









// <?php

// namespace App\Http\Controllers\API;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Message;
// use App\Models\MessageAttachment;
// use Illuminate\Support\Facades\Storage;
// use App\Helpers\DownloadHelper;

// class MessageController extends Controller
// {
//  public function allMessages(Request $request)
// {
//     $query = Message::with(['attachments', 'user'])->orderByDesc('created_at');

//     if ($request->has('user_id')) {
//         $userId = $request->input('user_id');

//         $query->where(function ($q) use ($userId) {
//             $q->where('user_id', $userId)
//               ->orWhereJsonContains('replies', ['user_id' => (int) $userId]);
//         });
//     }

//     $messages = $query->get();

//     // Flattened array to store both parent messages and replies
//     $flattened = [];
//     $baseUrl = env('APP_URL'); // Assuming you store the base URL in the .env file

//     foreach ($messages as $message) {
//         // Add the parent message with its attachments
//         $flattened[] = [
//             'id' => $message->id,
//             'user_id' => $message->user_id,
//             'user' => $message->user,
//             'subject' => $message->subject,
//             'message' => $message->message,
//             'attachments' => $this->getAttachmentsWithUrl($message->attachments, $baseUrl),  // Generate the URL for parent attachments
//             'created_at' => $message->created_at,
//             'type' => 'message',
//         ];

//         // Decode replies from JSON if available
//         $replies = json_decode($message->replies, true) ?? [];

//         // Iterate through each reply
//         foreach ($replies as $reply) {
//             // Separate the attachments for the reply
//             $replyAttachments = $reply['attachments'] ?? [];  // Generate the URL for reply attachments

//             // Add the reply with its own attachments
//             $flattened[] = [
//                 'user_id' => $reply['user_id'],
//                 'replied_to_user_id' => $reply['replied_to_user_id'] ?? null,
//                 'message' => $reply['message'],
//                 'attachments' => $replyAttachments, // Attachments for the reply
//                 'timestamp' => $reply['created_at'] ?? null, // Assuming 'created_at' is the timestamp
//                 'type' => 'reply',
//                 'parent_id' => $message->id,
//             ];
//         }
//     }

//     return response()->json(['data' => $flattened]);
// }

// // Helper function to generate attachment URLs
// private function getAttachmentsWithUrl($attachments, $baseUrl)
// {
//     $formattedAttachments = [];

//     foreach ($attachments as $attachment) {
//         // Generate the download URL for each attachment
//         $formattedAttachments[] = [
//             'file_name' => $attachment['file_name'],
//             'file_url' => $baseUrl . '/message_attachments/' . $attachment['file_name'], // Build the file URL based on your setup
//         ];
//     }

//     return $formattedAttachments;
// }




// public function viewSingleMessage($id)
// {
//     $message = Message::with(['attachments', 'user'])->findOrFail($id);

//     $flattened = [];

//     // Add the parent message to the flattened array
//     $flattened[] = [
//         'id' => $message->id,
//         'user_id' => $message->user_id,
//         'user' => $message->user,
//         'subject' => $message->subject,
//         'message' => $message->message,
//         'attachments' => $message->attachments,
//         'created_at' => $message->created_at,
//         'type' => 'message',
//     ];

//     // Decode replies from JSON if available
//     $replies = json_decode($message->replies, true) ?? []; // Decode JSON to array

//     // Check if replies exist and iterate
//     foreach ($replies as $reply) {
//         $flattened[] = [
//             'user_id' => $reply['user_id'],
//             'replied_to_user_id' => $reply['replied_to_user_id'] ?? null,
//             'message' => $reply['message'],
//             'attachments' => $reply['attachments'] ?? [], // Attachments for the reply
//             'timestamp' => $reply['created_at'] ?? null, // Assuming 'created_at' is the timestamp
//             'type' => 'reply',
//             'parent_id' => $message->id,
//         ];
//     }

//     return response()->json(['data' => $flattened]);
// }



//     // ✅ Store new message (already provided by you)
// public function store(Request $request)
// {
//     // Validate the incoming request
//     $validated = $request->validate([
//         'user_id'     => 'required|exists:users,id',
//         'subject'     => 'required|string|max:255',
//         'message'     => 'required|string',
//         'about'       => 'nullable|integer',
//         'attachments.*' => 'nullable|file|max:5120'
//     ]);

//     // Create the message record
//     $message = Message::create([
//         'user_id' => $validated['user_id'],
//         'subject' => $validated['subject'],
//         'message' => $validated['message'],
//         'about'   => $validated['about'] ?? null,
//         'replies' => json_encode([]), // Store the replies as an empty array (serialized)
//     ]);

//     // Handle the file uploads if they exist
//     if ($request->hasFile('attachments')) {
//         foreach ($request->file('attachments') as $file) {
//             // Generate a unique name and store the file
//             $originalName = $file->getClientOriginalName();
//             $path = $file->storeAs('public/message_attachments', $originalName);  // Store in the desired folder

//             // Save the attachment info to the database
//             $message->attachments()->create([
//                 'file_name' => $originalName,
//                 'file_path' => $path,
//             ]);
//         }
//     }

//     // Return a success response with the message data and its attachments
//     return response()->json([
//         'message' => 'Message sent successfully',
//         'data' => $message->load('attachments') // Include the attachments in the response
//     ], 201);
// }


//     // ✅ Reply to an existing message
// public function reply(Request $request, $id)
// {
//     $validated = $request->validate([
//         'user_id' => 'required|exists:users,id',
//         'message' => 'required|string',
//         'attachments.*' => 'nullable|file|max:5120'
//     ]);

//     // Get the parent message
//     $parent = Message::findOrFail($id);

//     // Prepare the reply data
//     $replyData = [
//         'user_id' => $validated['user_id'],
//         'message' => $validated['message'],
//         'created_at' => now(),
//         'updated_at' => now(),
//     ];

//     // Check if attachments exist
//     if ($request->hasFile('attachments')) {
//         $attachments = [];

//         foreach ($request->file('attachments') as $file) {
//             // Generate a unique file name using the current timestamp and original name
//             $fileName = time() . '_' . $file->getClientOriginalName();

//             // Define the custom directory to store the file
//             $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/SVSTEST/message_attachments/'; // Adjust this path as necessary

//             // Move the file to the custom directory
//             $file->move($destinationPath, $fileName);

//             // Generate the URL for the file based on your base URL and the directory
//             $baseUrl = env('APP_URL');  // Or specify the base URL manually if not using the .env file
//             $fullFileUrl = $baseUrl . '/message_attachments/' . $fileName;

//             // Save the attachment data with the URL
//             $attachments[] = [
//                 'file_name' => $file->getClientOriginalName(),
//                 'file_url' => $fullFileUrl,  // Store the generated file URL
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ];
//         }

//         // Add attachments to reply data
//         $replyData['attachments'] = $attachments;
//     }

//     // Decode existing replies and add the new reply
//     $replies = json_decode($parent->replies, true) ?? [];
//     $replies[] = $replyData;  // Add the new reply

//     // Update the parent message with the updated replies
//     $parent->update([
//         'replies' => json_encode($replies),
//     ]);

//     return response()->json([
//         'message' => 'Reply added to the message successfully',
//         'data' => $parent->load('attachments')  // Ensure parent message attachments are loaded
//     ]);
// }


// public function downloadFile($fileName)
// {
//     // Use the helper to handle the download logic
//     return downloadFile($fileName);
// }


// }
