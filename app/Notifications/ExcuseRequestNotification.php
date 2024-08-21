<?php

namespace App\Notifications;

use App\Models\Excuse;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ExcuseRequestNotification extends Notification
{
    use Queueable;

    protected $excuse;

    public function __construct(Excuse $excuse)
    {
        $this->excuse = $excuse;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Excuse Request')
                    ->line('A new excuse request has been submitted by ' . $this->excuse->employee->full_name)
                    ->line('Date: ' . $this->excuse->date)
                    ->line('Time: ' . $this->excuse->time);
    }
}
