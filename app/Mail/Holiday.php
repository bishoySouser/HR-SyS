<?php

namespace App\Mail;

use App\Models\Holiday as ModelsHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class Holiday extends Mailable
{
    use Queueable, SerializesModels;

    public $item;

    public function __construct(ModelsHoliday $holiday)
    {
        $this->item = $holiday;


        $this->item->from_date = $this->parseFromDate();
        $this->item->to_date = $this->parseToDate();
    }

    public function build()
    {
        return $this->subject($this->item->name)
                    ->view('emails.holiday')
                    ->with([
                        'holiday' => $this->item,
                    ]);
    }

    private function parseFromDate(): String
    {
        // Create a Carbon instance
        $date = Carbon::parse($this->item->from_date);

        // Format the date
        $formatted_date = $date->format('l, F j, Y');

        return $formatted_date;
    }

    private function parseToDate(): String
    {
        // Create a Carbon instance
        $date = Carbon::parse($this->item->to_date);

        // Format the date
        $formatted_date = $date->format('l, F j, Y');

        return $formatted_date;
    }
}
