<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\Vacation;
use Illuminate\Queue\SerializesModels;

class VacationRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vacation;
    public $remainingDays;

    public function __construct(Vacation $vacation)
    {
        $this->vacation = $vacation;
    }

    public function build()
    {
        return $this->subject($this->vacation->employee->fname.' - Vacation Request')
                    ->view('emails.vacation-request');
    }

}
