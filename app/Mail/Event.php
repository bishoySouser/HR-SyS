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
    public $googleCalendarLink;
    public $appleCalendarLink;

    public function __construct(ModelsEvent $event)
    {
        $this->event = $event;

        // Generate calendar links
        $this->googleCalendarLink = $this->generateGoogleCalendarLink();
        $this->appleCalendarLink = $this->generateAppleCalendarLink();

        $this->event->date = $this->parseDate();
    }

    public function build()
    {
        return $this->subject($this->event->name)
                    ->view('emails.event-mail')
                    ->with([
                        'event' => $this->event,
                        'googleCalendarLink' => $this->googleCalendarLink,
                        'appleCalendarLink' => $this->appleCalendarLink,
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

    private function generateGoogleCalendarLink(): string
    {
        $startTime = Carbon::parse($this->event->date)->format('Ymd\THis\Z');
        $endTime = Carbon::parse($this->event->date)->addHour()->format('Ymd\THis\Z'); // Assuming 1-hour event

        return "https://www.google.com/calendar/render?action=TEMPLATE" .
               "&text=" . urlencode($this->event->name) .
               "&details=" . urlencode($this->event->description) .
               "&location=" . urlencode($this->event->location) .
               "&dates=" . $startTime . "/" . $endTime;
    }

    private function generateAppleCalendarLink(): string
    {
        $startTime = Carbon::parse($this->event->date)->format('Y-m-d\TH:i:s');
        $endTime = Carbon::parse($this->event->date)->addHour()->format('Y-m-d\TH:i:s'); // Assuming 1-hour event

        return "data:text/calendar;charset=utf8," . urlencode(
                "BEGIN:VCALENDAR\r\n" .
                "VERSION:2.0\r\n" .
                "BEGIN:VEVENT\r\n" .
                "UID:" . uniqid() . "\r\n" .
                "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n" .
                "DTSTART:$startTime\r\n" .
                "DTEND:$endTime\r\n" .
                "SUMMARY:" . addslashes($this->event->name) . "\r\n" .
                "LOCATION:" . addslashes($this->event->location) . "\r\n" .
                "DESCRIPTION:" . addslashes($this->event->description) . "\r\n" .
                "END:VEVENT\r\n" .
                "END:VCALENDAR"
            );
    }
}
