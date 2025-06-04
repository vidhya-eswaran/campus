<?php
namespace App\Mail;

use App\Models\HostelAdmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HostelAdmissionUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $hostelAdmission;

    /**
     * Create a new message instance.
     *
     * @param HostelAdmission $hostelAdmission
     * @return void
     */
    public function __construct(HostelAdmission $hostelAdmission)
    {
        $this->hostelAdmission = $hostelAdmission;

        // Log when the mail object is created
        Log::info('HostelAdmissionUpdatedMail instance created.', [
            'student_id' => $hostelAdmission->student_id,
            'form_id' => $hostelAdmission->form_id ?? 'N/A',
            'status' => $hostelAdmission->status,
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Log when the email is being built
        Log::info('Building HostelAdmissionUpdatedMail email.', [
            'student_name' => optional($this->hostelAdmission->student)->name,
            'student_email' => optional($this->hostelAdmission->student)->email,
        ]);

        return $this->subject('Hostel Admission Status Updated')
                    ->view('emails.hostel_admission_updated');
    }
}
