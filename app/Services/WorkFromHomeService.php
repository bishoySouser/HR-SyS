<?php

namespace App\Services;

use App\Services\Interfaces\LeaveRequestInterface;
use App\Models\Employee;
use App\Models\WorkFromHome;
use Backpack\Settings\app\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkFromHomeService implements LeaveRequestInterface
{
    private int $limitInMonth;

    /**
     * Get the value of limitInMonth as int (with default).
     */
    public function getLimitInMonth(): int
    {
        $limit = Setting::get('work_from_home_count');

        // ensure int and fallback to 1 if not set or invalid
        $this->limitInMonth = intval($limit ?: 1);

        return $this->limitInMonth;
    }

    /**
     * Determines if the request can proceed.
     * Returns [bool $allowed, string $message]
     */
    public function canRequest($requestData): array
    {
        // basic existence checks (avoid crashing on missing keys)
        if (!isset($requestData['employee_id']) || !isset($requestData['day'])) {
            return [false, 'Missing employee_id or day in request data.'];
        }

        $employee = Employee::findOrFail($requestData['employee_id']);

        // check pending
        if ($this->hasPending($employee->id)) {
            return [false, 'You already have a pending Work from home request.'];
        }

        // check limit
        if ($this->hasReachedLimit($requestData)) {
            return [false, 'You have reached the maximum work from home requests for this month.'];
        }

        return [true, ''];
    }

    public function hasPending($employeeId): bool
    {
        return WorkFromHome::where('employee_id', $employeeId)
            ->where('status', 'Pending')
            ->exists();
    }

    /**
     * Check if the employee reached the monthly limit for the given day.
     */
    private function hasReachedLimit($requestData): bool
    {
        $employeeId = $requestData['employee_id'];
        $day = $requestData['day'];

        try {
            $date = Carbon::parse($day);
        } catch (\Exception $e) {
            // invalid date -> treat as cannot request (or you may prefer to throw)
            return true;
        }

        $year = $date->year;
        $month = $date->month;

        $limit = $this->getLimitInMonth();

        // count requests for the same employee in the same year+month
        $count = WorkFromHome::where('employee_id', $employeeId)
            ->whereYear('day', $year)
            ->whereMonth('day', $month)
            ->count();

        return $count >= $limit;
    }
}
