<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;

class PaymentNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $paymentNotificationData;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $paymentNotificationData
     */
    public function __construct($user, $paymentNotificationData)
    {
        $this->user = $user;
        $this->paymentNotificationData = $paymentNotificationData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('Payment Notification')
        //     ->view('emails.invoice.payment_notification');
        $pdf = PDF::loadView('emails.invoice.payment_notification', [
            'user' => $this->user,
            'paymentNotificationData' => $this->paymentNotificationData,
        ]);

        $pdfFileName = 'payment_notification_' . time() . '.pdf';

        // Save the PDF to storage
        Storage::put('pdf/' . $pdfFileName, $pdf->output());

        return $this->subject('Payment Notification')
            ->view('emails.invoice.payment_notification')
            ->attachData($pdf->output(), $pdfFileName, [
                'mime' => 'application/pdf',
            ]);
    }
}
