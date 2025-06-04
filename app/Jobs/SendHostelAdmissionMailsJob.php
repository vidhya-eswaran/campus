<?php
namespace App\Jobs;

use App\Models\Student;
use App\Models\User;
use App\Mail\HostelAdmissionMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendHostelAdmissionMailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $studentIds;
    public $acadYear;

    public function __construct($studentIds, $acadYear)
    {
        $this->studentIds = $studentIds;
        $this->acadYear = $acadYear;
    }

    public function handle()
    {
        $students = Student::whereIn('id', $this->studentIds)->get();

        $students->chunk(100)->each(function ($chunk) {
            foreach ($chunk as $student) {
                $user = User::where('roll_no', $student->roll_no)
                    ->where('admission_no', $student->admission_no)
                    ->orderByDesc('id')
                    ->first();

                if ($user) {
                    $user->acad_year = $this->acadYear;

                    Mail::to($user->email ?? 'civildinesh313@gmail.com')
                        ->queue(new HostelAdmissionMail($user));
                } else {
                    Log::warning('User not found for hostel admission', [
                        'roll_no' => $student->roll_no,
                        'admission_no' => $student->admission_no
                    ]);
                }
            }
        });
    }
}
