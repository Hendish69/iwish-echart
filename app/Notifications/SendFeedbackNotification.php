<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendFeedbackNotification extends Notification
{
    use Queueable;

    private $data;
    
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'notif_id' => uniqid(),
            'title' => $this->data['title'],
            'descriptions' => $this->data['descriptions']
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'notif_id' => uniqid(),
            'title' => $this->data->title,
            'descriptions' => $this->data->descriptions
        ];
    }
}
