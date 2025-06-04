<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use App\Models\StoreMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    //


public function sendMail(Request $request) 
{
    // $validator = Validator::make($request->all(), [
    //      'email' => 'required|email|unique:store_mails'
    // ]);

    // if ($validator->fails()) {
    //     return new JsonResponse(['success' => false, 'message' => $validator->errors()], 422);
    // }  

     $email = "udhayatech19@gmail.com";
    //     $storeMail = StoreMail::create([
    //         'email' => $email
    //     ]
    // ); 

    //if ($storeMail) {
        Mail::to($email)->send(new SendMail($email));
        return new JsonResponse(
            [
                'success' => true, 
                'message' => "Thank you for subscribing to our email, please check your inbox"
            ], 
            200
        );
    //}
}

}
