<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserNotification;
use App\Models\User;
use App\Models\Student;
use Carbon\Carbon;

class SendAnnouncementNotifications extends Command
{
    protected $signature = 'notifications:send';
    protected $description = 'Send scheduled notifications via Twilio SMS';

    public function handle()
    {
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // Get pending notifications that are due
        // $notifications = UserNotification::where('send_status', 'pending')
        //     ->whereNotNull('schedule_time')
        //     ->where('schedule_time', '<=', Carbon::now())
        //     ->get();
        $notifications = UserNotification::where('send_status', 'pending')
            ->whereNotNull('schedule_time')
            ->where('schedule_time', '<=', Carbon::now())
            ->where('notification_type', '!=', 'Notice') // Exclude "Notice" type
            ->get();

        foreach ($notifications as $notification) {
            // $user = \App\Models\User::find($notification->user_id);
             $users = User::find($notification->user_id);
                $existingStudent = Student::where('admission_no', 'like', $users->admission_no)->first();
            if (!empty($existingStudent->MOBILE_NUMBER)) {
                // Fetch user phone number
                $users = User::find($userId);
                $existingStudent = Student::where('admission_no', 'like', $users->admission_no)->first();
                $mobile = $existingStudent->MOBILE_NUMBER;      /////////////<------------add this
                // Define the SMS template
                $smsTemplate = "Dear {#name#}, your OTP for Santhosha Vidhyalaya student portal is {#otp#}. This code is valid for 10 minutes. Please do not share it with anyone.";
                $otp = rand(10000, 99999);
                // Replace placeholders in the template with actual values
                $message = str_replace(
                    ['{#name#}', '{#otp#}'],
                    [$users->name, $otp],
                    $smsTemplate
                );
                // Send SMS via SMSCountry or any other SMS service
                 $smsResponse = Http::withHeaders([
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==', // Replace with your BASIC AUTH string
                        ])->post('https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/', [
                            "Text" => $message,
                            "Number" => "7904352006",
                            "SenderId" => "SANTHV",
                            "DRNotifyUrl" => "https://www.domainname.com/notifyurl",
                            "DRNotifyHttpMethod" => "POST",
                            "Tool" => "API",
                        ]);
                // Check SMS response status
                if ($smsResponse->status() != 202) {
                    Log::error('SMS sending failed. Response: ' . $smsResponse->body());
                    return response()->json(['status' => 'error', 'message' => 'Failed to send OTP via SMS'], 500);
                }
                $notification->update(['status' => 'sent']);
                Log::info('SMS sent successfully. OTP: ' . $otp);
            }
        }

        $this->info('Scheduled notifications sent successfully!');
    }
}
