<?php

namespace App\Mail;

use App\Models\Excuse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExcuseStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $excuse;

    public function __construct(Excuse $excuse)
    {
        $this->excuse = $excuse;
    }

    public function build()
    {
        return $this->view('emails.excuse-status');
    }
}
