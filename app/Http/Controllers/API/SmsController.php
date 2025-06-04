<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
//  use App\Models\User;
// use App\Models\SponserMaster;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\PasswordResetNotification;
// use App\Models\Student;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function sendTestSms()
    {
        // Your SMS message
        $message =  "Dear *, This is to inform you that your ward's fee has been generated . Your invoice number is * and the due date for payment is * . If you have any questions or require support, please feel free to contact the Santhosha Vidhyalaya administrator. Pay Online using  https://santhoshavidhyalaya.com/svsportaladmin/ -  Santhosha Vidhyalaya";

        // Phone number to send the SMS to
        $phone_no = "7904352006"; // Replace with the recipient's phone number

        // Define your BASIC AUTH string
        // $basicAuth = base64_encode('YOUR_USERNAME:YOUR_PASSWORD'); // Replace with your SMS service username and password
        // 'Authorization' => 'Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==', // Replace with your BASIC AUTH string

        // Send the SMS
        $smsResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            // 'Authorization' => 'Basic ' . $basicAuth,
           'Authorization' => 'Basic MXpnQjhHdHZMNm5DR2ZaeEpKZ1E6Q1o4ZDVBNWNta2k1R0dZaWZlcE5tSG02ZGh1Z0Rwb3haT29TRWRMMQ==', // Replace with your BASIC AUTH string

        ])->post('https://restapi.smscountry.com/v0.1/Accounts/1zgB8GtvL6nCGfZxJJgQ/SMSes/', [
            "Text" => $message,
            "Number" => $phone_no,
            "SenderId" => "SVHSTL",
            "DRNotifyUrl" => "https://www.domainname.com/notifyurl",
            "DRNotifyHttpMethod" => "POST",
            "Tool" => "API",
        ]);

        // Check the response
        if ($smsResponse->successful()) {
            // SMS sent successfully
            return "SMS sent successfully!";
        } else {
            // SMS sending failed
            return "Failed to send SMS: " . $smsResponse->status();
        }
    }
}