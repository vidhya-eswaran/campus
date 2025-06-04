<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HostelAdmissionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct($student)
    {
        $this->student = $student;
            Log::info('Hostel mail data ddddddddddddddddddd.', [
            'student_id' => $student,
            
        ]);
    }

public function build()
{
    return $this->subject('Hostel Admission Confirmation')
                ->with('student', $this->student)
                ->view('emails.hostel_admission');
}

}
