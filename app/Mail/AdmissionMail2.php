<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionMail2 extends Mailable
{
    use Queueable, SerializesModels;

    public $admissionData2;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admissionData2)
    {
        
        $this->$admissionData2 = $admissionData2;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admissionMail2');
    }
   

}
