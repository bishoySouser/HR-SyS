<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Vacation;

class VacationRejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vacation;
    public $rejectedBy;

    public function __construct(Vacation $vacation, string $rejectedBy)
    {
        $this->vacation = $vacation;
        $this->rejectedBy = $rejectedBy;
    }

    public function build()
    {
        return $this->subject('Vacation Request Rejected')
                    ->view('emails.vacation_rejection');
    }
}
