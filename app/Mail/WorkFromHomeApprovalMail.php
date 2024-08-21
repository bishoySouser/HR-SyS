<?php

namespace App\Mail;

use App\Models\WorkFromHome;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkFromHomeApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $workFromHome;

    public function __construct(WorkFromHome $workFromHome)
    {
        $this->workFromHome = $workFromHome;
    }

    public function build()
    {
        return $this->view('emails.work-from-home-approval');
    }
}
