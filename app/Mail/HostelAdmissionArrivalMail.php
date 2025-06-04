<?php
namespace App\Mail;

use App\Models\HostelAdmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HostelAdmissionArrivalMail extends Mailable
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
        $this->hostelSlipUrl = 'san.com';

        // Log when the mail object is created
        Log::info('HostelAdmissionArrivalMail arrival departure instance created.', [
            'student_id' => $hostelAdmission->student_id,
            'form_id' => $hostelAdmission->form_id ?? 'N/A',
            'arr_dep_status' => $hostelAdmission->arr_dep_status,
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

        return $this->subject('Hostel Arrival/Departure Slip')
                    ->view('emails.hostel_admission_arrdep')
                    ->with([
                'hostelSlipUrl' => $this->hostelSlipUrl,
            ]);
    }
}
