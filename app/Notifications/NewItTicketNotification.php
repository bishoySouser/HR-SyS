<?php

namespace App\Notifications;

use App\Models\ItTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewItTicketNotification extends Notification
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
                    ->line('A new IT ticket has been created.')
                    ->line('Name: ' . $this->itTicket->employee->fname)
                    ->line('Title: ' . $this->itTicket->title)
                    ->line('Category: ' . $this->itTicket->category)
                    ->action('View Ticket', url('/admin/it-ticket/' . $this->itTicket->id . '/show'))
                    ->line('Thank you for your attention to this matter.');
    }
}
