<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $name;
    public $newPassword;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $name
     * @param string $newPassword
     * @return void
     */
    public function __construct($email, $name, $newPassword)
    {
        $this->email = $email;
        $this->name = $name;
        $this->newPassword = $newPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reset_notification');
    }
}
