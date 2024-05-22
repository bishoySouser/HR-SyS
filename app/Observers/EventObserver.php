<?php

namespace App\Observers;

use App\Jobs\EventEmail;
use App\Models\Event;

class EventObserver
{
    public function created(Event $event)
    {
        // $event = $event->();
        var_dump($event);
    $employeeEmails = [];
    foreach ($event->employees as $employee) {
        $employeeEmails[] = $employee->email;
    }

    var_dump($employeeEmails);
    dd('ss');
    EventEmail::dispatch($event, $employeeEmails);
    }
}
