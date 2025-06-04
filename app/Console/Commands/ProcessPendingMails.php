<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\PendingMail;
use App\Models\Student;
use App\Models\User;
use App\Mail\HostelAdmissionMail;
use App\Helpers\SmsHelper;

class ProcessPendingMails extends Command
{
    protected $signature = 'mails:process-pending';
    protected $description = 'Send pending hostel admission mails in batches of 10 per minute';

    public function handle()
    {
        $pendingMails = PendingMail::where('status', 'pending')
                          ->where('heading', 'Hostel Application Sent')
                            ->orderBy('id')
                            ->limit(10)
                            ->get();

        foreach ($pendingMails as $mail) {
            $student = Student::find($mail->student_id);

            if (!$student) {
                Log::warning("Student not found for ID: {$mail->student_id}");
                continue;
            }

            $user = User::where('roll_no', $student->roll_no)
                        ->where('admission_no', $student->admission_no)
                        ->orderByDesc('id')
                        ->first();
                 $query = http_build_query([
                                        'slno'          => $user->slno,
                                        'id'            => $user->id,
                                        'admission_no'  => $user->admission_no,
                                        'roll_no'       => $user->roll_no,
                                        'name'          => $user->name,
                                        'gender'        => $user->gender,
                                        'standard'      => $user->standard,
                                        'twe_group'     => $user->twe_group,
                                        'sec'           => $user->sec,
                                        'academic_year' => $user->academic_year,
                                        'hostelOrDay'   => $user->hostelOrDay,
                                        'email'         => $user->email,
                                    ]);
                                
                     $formType = '/ArrivalForm';
                    $baseUrl = env('HOSTEL_FORM_URL', 'https://santhoshavidhyalaya.com/svsportaladmintest'); // fallback if not set
                $hostelAdmissionarrivalURL = rtrim($baseUrl, '/') . $formType . '?' . $query;
                $hostelAdmissionUrl = rtrim($baseUrl, '/') . '/hostel/application/create/edit/' . $user->roll_no . '/' . $user->admission_no. '/' .$mail->acad_year;
               $user->arrUrl = $hostelAdmissionarrivalURL;
                $user->url = $hostelAdmissionUrl;
                   Log::info('Hostel admission link generated------------------------------------------------------------>', [
                        '$user' =>$user,
                         ]);
                $fatherNumber = $student->father_contact_no ?? null;
                $motherNumber = $student->mother_contact_no ?? null;
                
                $mobileToSend = $fatherNumber ?? $motherNumber;
                
                SmsHelper::sendTemplateSms(
                    'E Hostel Admissions Open',
                    $mobileToSend,
                    [
                        'var' => $hostelAdmissionUrl  
                    ]
                );
      
                if ($user && !empty($mail->email)) {
                $user->acad_year = $mail->acad_year;
               

                try {
                    // Mail::to($mail->email)->send(new HostelAdmissionMail($user));
                     $usermaildata = [
                                        'slno'          => $user->slno,
                                        'id'            => $user->id,
                                        'admission_no'  => $user->admission_no,
                                        'roll_no'       => $user->roll_no,
                                        'name'          => $user->name,
                                        'gender'        => $user->gender,
                                        'standard'      => $user->standard,
                                        'twe_group'     => $user->twe_group,
                                        'sec'           => $user->sec,
                                        'academic_year' => $user->academic_year,
                                        'hostelOrDay'   => $user->hostelOrDay,
                                        'email'         => $user->email,
                                        'arrUrl'        => $hostelAdmissionarrivalURL,
                                        'url'           => $hostelAdmissionUrl,
                                        'acad_year'     => $mail->acad_year,  
                                    ];
                    Mail::to('civildinesh313@gmail.com')->send(new HostelAdmissionMail($usermaildata));

                    // Update the mail status
                    $mail->status = 'sent';
                    $mail->sent_at = now();
                    $mail->save();

                    // ✅ Log detailed information
                    Log::info('Hostel admission mail sent', [
                        'student_id' => $mail->student_id,
                        'email'      => $mail->email,
                        'acad_year'  => $mail->acad_year,
                        'sent_at'    => $mail->sent_at->toDateTimeString(),
                        'heading'    => $mail->heading
                    ]);

                } catch (\Exception $e) {
                    Log::error("Failed to send mail to {$mail->email}: " . $e->getMessage());
                }
            } else {
                Log::warning("Invalid user or missing email for student ID: {$mail->student_id}");
            }
        }

        return 0;
    }
}
