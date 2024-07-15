<?php

namespace App\Observers;

use App\Jobs\EventEmail;
use App\Models\Event;

class EventObserver
{
    public function created(Event $event)
    {
        // $event = $event->();
        $employeeEmails = [];
        foreach ($event->employees as $employee) {
            $employeeEmails[] = $employee->email;
        }

        EventEmail::dispatch($event, $employeeEmails);
    }
}
