<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\VacationBalance;
use App\Models\Excuse;
use App\Models\Holiday;
use App\Models\Vacation;
use App\Models\WorkFromHome;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::now();
        $currentYear = $today->year;
        $currentMonth = $today->month;

        $vacationDaysLeft = VacationBalance::where('employee_id', $user->id)
            ->where('year', $currentYear)
            ->value('remaining_days') ?? 0;

        $excuseHoursUsed = Excuse::where('employee_id', $user->id)
            ->whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->get()
            ->sum(function ($excuse) {
                list($hours, $minutes) = explode(':', $excuse->time);
                return $hours + ($minutes / 60);
            });

        $excuseHoursLimit = 4;
        $excuseHoursRemaining = max(0, $excuseHoursLimit - $excuseHoursUsed);

        $pendingRequests = $this->getPendingRequests($user->id);

        $employeeInfo = $this->getEmployeeInfo($user->id);

        $workFromHomeDaysLeft = $this->getWorkFromHomeDaysLeft($user->id, $currentYear, $currentMonth);

        $nearestHoliday = $this->getNearestHoliday();

        $data = [
            'todayDate' => $today->format('d M Y'),
            'username' => $user->fname,
            'vacation_days_left' => $vacationDaysLeft,
            'excuse_hours_remaining' => round($excuseHoursRemaining, 2),
            'pending_requests' => $pendingRequests,
            'employee_info' => $employeeInfo,
            'work_from_home_days_left' => $workFromHomeDaysLeft,
            'nearest_holiday' => $nearestHoliday,
            'time' => date("h:i:sa")
        ];

        return response()->json([
            'status' => true,
            'status_code' => 200,
            'message' => 'dashboard information',
            'data' => $data
        ],200);
    }

    private function getPendingRequests($employeeId)
    {
        $vacations = Vacation::join('leave_balance', 'vacations.balance_id', '=', 'leave_balance.id')
            ->where('leave_balance.employee_id', $employeeId)
            ->where('vacations.status', 'pending')
            ->select('vacations.start_date as date', DB::raw("'Vacation' as type"))
            ->get();

        $workFromHome = WorkFromHome::where('employee_id', $employeeId)
            ->where('status', 'Pending')
            ->select('day as date', DB::raw("'Work from home' as type"))
            ->get();

        $excuses = Excuse::where('employee_id', $employeeId)
            ->where('status', 'Pending')
            ->select('date', DB::raw("'Excuse' as type"))
            ->get();

        $allRequests = $vacations->concat($workFromHome)->concat($excuses)
            ->sortByDesc('date')
            ->map(function ($item) {
                $date = Carbon::parse($item->date)->format('F j, Y');
                return "$date {$item->type}";
            });

        return $allRequests->first(); // Return only the first item
    }

    private function getEmployeeInfo($employeeId)
    {
        $employee = Employee::with(['job', 'socialInsurance', 'medicalInsurance'])
            ->findOrFail($employeeId);

        return [
            'profile_pic' => $employee->profile_pic,
            'full_name' => $employee->full_name,
            'job_title' => $employee->job->title ?? 'N/A',
            'join_date' => Carbon::parse($employee->hire_date)->format('F Y'),
            'medical_insurance' => $employee->medicalInsurance && $employee->medicalInsurance->status == 1 ? 'true' : 'false',
            'social_insurance' => $employee->socialInsurance && $employee->socialInsurance->status == 1 ? 'true' : 'false',
        ];
    }

    private function getWorkFromHomeDaysLeft($employeeId, $year, $month)
    {
        $workFromHomeDaysUsed = WorkFromHome::where('employee_id', $employeeId)
            ->whereYear('day', $year)
            ->whereMonth('day', $month)
            ->where('status', '!=', 'Cancelled')
            ->count();

        $workFromHomeDaysLimit = 2;
        return max(0, $workFromHomeDaysLimit - $workFromHomeDaysUsed);
    }

    private function getNearestHoliday()
    {
        $today = Carbon::today();
        $nearestHoliday = Holiday::where(function($query) use ($today) {
                $query->where('from_date', '>=', $today)
                      ->orWhere('to_date', '>=', $today);
            })
            ->orderBy('from_date')
            ->first();

        if ($nearestHoliday) {
            $holidayDate = Carbon::parse($nearestHoliday->from_date);
            return $holidayDate->format('l d F') . ' ' . $nearestHoliday->name;
        }

        return null;
    }

}
