<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMailHostelAdmission extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $name;
    public $fromEmail;

    public function __construct($otp, $name = 'Parent/Guardian', $fromEmail = null)
    {
        $this->otp = $otp;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('OTP for Hostel Admission Verification')
                    ->view('emails.otp_mail_addmision');

        if ($this->fromEmail) {
            $mail->from($this->fromEmail, $this->name);
        }
        return $mail;
    }
}
