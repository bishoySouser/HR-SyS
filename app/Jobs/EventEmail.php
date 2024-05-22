<?php

namespace App\Jobs;

use App\Mail\Event as MailEvent;
use App\Mail\WelcomeJoin;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EventEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Event $event
    )
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new MailEvent($this->event);
        $event = Event::with('employees')->get();
        var_dump($event);
        dd('ss');
        Mail::to($this->employeeEmails)->send($email);
    }
}
