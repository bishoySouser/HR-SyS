<?php

namespace App\Observers;

use App\Models\Employee;
use App\Jobs\SendWelcomeEmail;

class EmployeeObserver
{
    public function created(Employee $employee)
    {
        SendWelcomeEmail::dispatch($employee);
    }
}
