<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class WorkFromHomeLimitHandler
{
    /**
     * Check if the employee has reached the work from home limit for the current month.
     *
     * @param  \App\Models\Employee  $employee
     * @return bool
     */
    public function hasReachedLimit(Employee $employee)
    {
        $currentMonth = Carbon::now()->month;

        $workFromHomeCountThisMonth = $employee->workFromHomes()
            ->whereMonth('day', $currentMonth)
            ->count();

        return $workFromHomeCountThisMonth >= 2;
    }
}
