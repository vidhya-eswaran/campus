<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class PushNotificationController extends Controller
{
    // public function sendPushNotification(Request $request)
    // {
    //     $deviceToken = $request->input('device_token');  // from mobile
    //     $title = $request->input('title');
    //     $body = $request->input('body');

    //     $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));
    //     $messaging = $factory->createMessaging();

    //     $message = [
    //         'token' => $deviceToken,
    //         'notification' => [
    //             'title' => $title,
    //             'body' => $body,
    //         ],
    //         'data' => [
    //             'custom_key' => 'custom_value'
    //         ]
    //     ];

    //     try {
    //         $messaging->send($message);
    //         return response()->json(['success' => true, 'message' => 'Notification sent']);
    //     } catch (\Throwable $e) {
    //         return response()->json(['success' => false, 'error' => $e->getMessage()]);
    //     }
    // }

    public static function sendPushNotification($title, $body, $deviceToken, $type = 'general', $data = [], $toUserId = null)
    {
        // $fromUserId = $request->input('from_user_id');
        // $toUserId = $request->input('to_user_id');
        // $title = $request->input('title');
        // $body = $request->input('body');
        // $type = $request->input('type', 'general'); // Optional type

        // Get recipient user
        $toUser = User::find($toUserId);

        if (!$toUser || !$toUser->device_token) {
            return response()->json(['success' => false, 'error' => 'Device token not found']);
        }

        // Build Firebase message
        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $message = [
            'token' => $toUser->device_token,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'custom_key' => 'custom_value'
            ]
        ];

        try {
            // Send push notification
            $messaging->send($message);

            // Insert notification log into the database
            DB::table('notification_users')->insert([
                'form_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'to_email' => $toUser->email,
                'title' => $title,
                'body' => $body,
                'is_sent' => 1,
                'created_by' => $fromUserId,
                'updated_by' => $fromUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Notification sent and saved.']);
        } catch (\Throwable $e) {
            // Save even if FCM fails
            DB::table('notification_users')->insert([
                'form_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'to_email' => $toUser->email,
                'title' => $title,
                'body' => $body,
                'is_sent' => 0,
                'created_by' => $fromUserId,
                'updated_by' => $fromUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
