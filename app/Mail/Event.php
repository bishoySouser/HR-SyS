<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\Event as ModelsEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Event extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(ModelsEvent $event)
    {
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Email Invitation, ')
                    ->view('emails.event-mail')
                    ->with([
                        'event' => $this->event,
                    ]);
    }
}
