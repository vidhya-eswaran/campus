<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class PushNotificationController extends Controller
{
        public static function sendPushNotification(
            $title,
            $body,
            $type,
            $data,
            $toUserId,
            $deviceToken
        )    {
        // $deviceToken = $request->input('device_token');  // from mobile
        // $title = $request->input('title');
        // $body = $request->input('body');

        // $factory = (new Factory)->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));
        // $messaging = $factory->createMessaging();

        // $message = [
        //     'token' => $deviceToken,
        //     'notification' => [
        //         'title' => $title,
        //         'body' => $body,
        //     ],
        //     'data' => [
        //         'custom_key' => 'custom_value'
        //     ]
        // ];
        $fromUserId = $toUserId;

        $toUser = User::find($toUserId);

        if (!$deviceToken) {
            return ['success' => false, 'error' => 'Device token not found'];
        }

        try {

            $response = Http::post('https://exp.host/--/api/v2/push/send', [
                'to' => $deviceToken, // ExponentPushToken[xxx]
                'title' => $title,
                'body' => $body,
                'data' => [
                    'custom_key' => 'custom_value',
                ],
            ]);

            //dd($response->json());

            DB::table('notification_users')->insert([
                'form_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'to_email' => $toUser->email ?? '',
                'title' => $title,
                'body' => $body,
                'is_sent' => 1,
                'created_by' => $fromUserId,
                'updated_by' => $fromUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            DB::table('notification_users')->insert([
                'form_user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'to_email' => $toUser->email ?? '',
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