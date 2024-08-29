<?php

namespace App\Notifications;

use App\Models\ItTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketInProgressNotification extends Notification
{
    use Queueable;

    protected $itTicket;

    public function __construct(ItTicket $itTicket)
    {
    // Notify IT department about new ticket
        $this->itTicket = $itTicket;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your IT ticket is now in progress.')
                    ->line('Title: ' . $this->itTicket->title)
                    ->line('Category: ' . $this->itTicket->category)
                    ->line('We will update you when there are further developments.');
    }
}
