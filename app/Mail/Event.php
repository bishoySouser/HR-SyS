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
use Carbon\Carbon;

class Event extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(ModelsEvent $event)
    {
        $this->event = $event;


        $this->event->date = $this->parseDate();
    }

    public function build()
    {
        return $this->subject($this->event->name)
                    ->view('emails.event-mail')
                    ->with([
                        'event' => $this->event,
                    ]);
    }

    private function parseDate(): array
    {
        $date = Carbon::parse($this->event->date);

        return [
            'day' => $date->format('d'),
            'month_year' => $date->format('F Y'),
            'time' => $date->format('h:i a')
        ];
    }
}
