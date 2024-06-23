<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Vacation;
use Illuminate\Queue\SerializesModels;

class VacationApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $vacation;
    public $remainingDays;

    public function __construct(Vacation $vacation, $remainingDays)
    {
        $this->vacation = $vacation;
        $this->remainingDays = $remainingDays;
    }

    public function build()
    {
        return $this->subject('Vacation Request Approved')
                    ->view('emails.vacation_approval');
    }
}
