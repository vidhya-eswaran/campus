<?php

namespace App\Http\Controllers;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Services\RazorpayService;

class RazorpayPaymentController extends Controller
{
    protected $razorpayService;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function createOrder(Request $request, $school)
    {
        $this->razorpayService->configureRazorpayForSchool($school);

        $api = new \Razorpay\Api\Api(config('razorpay.key'), config('razorpay.secret'));

        $order = $api->order->create([
            'receipt' => 'RCPT-' . uniqid(),
            'amount' => $request->amount * 100,
            'currency' => 'INR',
        ]);

        return response()->json([
            'order_id' => $order['id'],
            'razorpay_key' => config('razorpay.key'),
        ]);
    }


    // public function createOrder(Request $request)
    // {
    //     $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    //     $order = $api->order->create([
    //         'receipt'         => 'rcptid_' . time(),
    //         'amount'          => $request->amount * 100, // Amount in paise
    //         'currency'        => 'INR',
    //         'payment_capture' => 1,
    //     ]);

    //     return response()->json([
    //         'order_id' => $order->id,
    //         'key' => config('services.razorpay.key'),
    //         'amount' => $request->amount,
    //     ]);
    // }

    public function verifyPayment(Request $request)
    {
        $signature = $request->razorpay_signature;
        $orderId = $request->razorpay_order_id;
        $paymentId = $request->razorpay_payment_id;

        $generated_signature = hash_hmac('sha256', "$orderId|$paymentId", config('services.razorpay.secret'));

        if ($generated_signature === $signature) {
            // Save to DB or mark as paid
            return response()->json(['message' => 'Payment verified successfully']);
        }

        return response()->json(['message' => 'Payment verification failed'], 400);
    }
}

