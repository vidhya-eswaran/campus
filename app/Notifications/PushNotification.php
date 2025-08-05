<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\FcmMessage;

class PushNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;

    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        return (new FcmMessage)
            ->notification([
                'title' => $this->title,
                'body' => $this->body,
            ])
            ->data([
                'type' => 'login'
            ]);
    }
}
