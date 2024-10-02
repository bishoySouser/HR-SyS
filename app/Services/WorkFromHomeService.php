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
    private $limitInMonth;

    /**
     * Get the value of limitInMonth
     */ 
    public function getLimitInMonth()
    {
        $this->limitInMonth = Setting::get('work_from_home_count');
        return $this->limitInMonth;
    }

    public function canRequest($requestData): array
    {
        $employee = Employee::findOrFail($requestData['employee_id']);
        $duration = Employee::findOrFail($requestData['employee_id']);

        if ($this->hasPending($employee->id)) {
            return [false, 'You already have a pending Work from home request.'];
        }

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

    private function hasReachedLimit($requestData)
    {
        $employeeId = $requestData['employee_id'];
        $day = $requestData['day'];
        $month = Carbon::parse($day)->month;

        $hasRecords = WorkFromHome::where('employee_id', $employeeId)->whereMonth('day', $month);

        if (!$hasRecords->exists()) {
            return false;
        }

        $requestsPerMonth = $hasRecords->select(DB::raw('YEAR(day) as year, MONTH(day) as month, COUNT(*) as request_count'))
            ->groupBy('year', 'month')  // Group by year and month
            ->get();

        $monthRequests = $requestsPerMonth->firstWhere('month', $month);

        return $monthRequests->request_count >= $this->getLimitInMonth();
    }

    
}
