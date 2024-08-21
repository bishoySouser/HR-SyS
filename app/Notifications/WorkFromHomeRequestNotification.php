<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\WorkFromHome;
use Illuminate\Notifications\Notification;

class WorkFromHomeRequestNotification extends Notification
{
    use Queueable;

    protected $workFromHome;

    public function __construct(WorkFromHome $workFromHome)
    {
        $this->workFromHome = $workFromHome;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                        ->line('A new work-from-home request has been made.')
                        ->line('Request Details:')
                        ->line('Employee Name: ' . $this->workFromHome->employee->full_name)
                        ->line('Requested Date: ' . \Carbon\Carbon::parse($this->workFromHome->day)->format('d-m-Y'))
                        ->line('Thank you for reviewing this request.');
    }

    public function toArray($notifiable)
    {

    }
}
