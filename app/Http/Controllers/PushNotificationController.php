<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class PushNotificationController extends Controller
{
    public function sendPushNotification(Request $request)
    {
        $deviceToken = $request->input('device_token');  // from mobile
        $title = $request->input('title');
        $body = $request->input('body');

        $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));
        $messaging = $factory->createMessaging();

        $message = [
            'token' => $deviceToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
            'data' => [
                'custom_key' => 'custom_value'
            ]
        ];

        try {
            $messaging->send($message);
            return response()->json(['success' => true, 'message' => 'Notification sent']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
