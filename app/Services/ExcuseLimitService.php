<?php

// app/Services/ExcuseLimitService.php

namespace App\Services;

use App\Models\Excuse;

class ExcuseLimitService
{
    static $LIMIT = 14400;
    protected $monthlyUsage;

    public function __construct($employeeId, $month, $year)
    {
        $this->monthlyUsage = Excuse::forTimeInMonthBySecound($employeeId, $month, $year);
    }

    /**
     * Check if the given excuse time exceeds the monthly limit for the employee.
     *
     * @param  int  $excuseTimeInSeconds
     * @return bool
     */
    public function exceedsMonthlyLimit($excuseTimeInSeconds)
    {
        return $this->monthlyUsage + $excuseTimeInSeconds > static::$LIMIT;
    }

    public function remainingSeconds()
    {
        return static::$LIMIT - $this->monthlyUsage;
    }
}
