<?php

namespace App\Services;

use App\Services\Interfaces\LeaveRequestInterface;
use App\Models\Excuse;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExcuseLimitService implements LeaveRequestInterface
{
    protected $limit;
    protected $monthlyUsage;
    protected $employeeId;
    protected $month;
    protected $year;

    public function canRequest($requestData): array
    {
        $excuseTimeInSeconds = $requestData['duration'] ?? 0;

        if ($this->hasPending($requestData['employee_id'])) {
            return [false, 'You already have a pending Work from home request.'];
        }

        if ($this->exceedsMonthlyLimit($requestData, $excuseTimeInSeconds)) {
            return [false, 'Exceeds monthly limit for excuses.'];
        }

        return [true, ''];
    }

    private function getLimit(): int
    {
        return Setting::get('excuses_count') * 60 * 60;
    }

    public function hasPending($employeeId): bool
    {
        // Implement logic to check for pending excuse requests
        // This is a placeholder implementation
        return Excuse::where('employee_id', $employeeId)
            ->where('status', 'pending')
            ->exists();
    }

    private function exceedsMonthlyLimit($requestData, $excuseTimeInSeconds)
    {
        // $this->monthlyUsage = Excuse::forTimeInMonthBySecound($requestData['employee_id'], now()->month, now()->year);

        $month = Carbon::parse($requestData['date'])->month;
        $hasRecords = Excuse::where('employee_id', $requestData['employee_id'])->whereMonth('date', $month);

        if (!$hasRecords->exists()) {
            return false;
        }

        $requestsPerMonth = $hasRecords->select(DB::raw('YEAR(date) as year, MONTH(date) as month, SUM(TIME_TO_SEC(time)) as request_count'))
            ->where('status', 'Approved')
            ->groupBy(DB::raw('YEAR(date)'))
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();


            $monthRequests = $requestsPerMonth->firstWhere('month', $month);

        return $monthRequests->request_count + $excuseTimeInSeconds > $this->getLimit();
    }

    public function remainingSeconds()
    {
        return $this->getLimit() - $this->monthlyUsage;
    }
}
