<?php

namespace App\Observers;

use App\Mail\Holiday as MailHoliday;
use App\Models\Employee;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;


class HolidayObserver
{
    /**
     * Handle the Holiday "created" event.
     */
    public function created(Holiday $holiday): void
    {


        // $event = $event->();
        $employeeEmails = Employee::all();
        foreach ($employeeEmails as $employee) {
            $employeeEmails[] = $employee->email;
        }

        // MailHoliday::dispatch($holiday, $employeeEmails);
        Mail::to($employeeEmails)->send(new MailHoliday($holiday));

    }


}
