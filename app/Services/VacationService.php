<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\Vacation;
use App\Models\VacationBalance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VacationService
{
    public function getBalanceIdForCurrentYear()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        $leaveBalance = VacationBalance::where('employee_id', $user->id)
            ->where('year', $currentYear)
            ->first();

        if (!$leaveBalance) {
            // Handle the case when the leave balance is not found for the current year
            // For example, you could create a new record with default values
            $leaveBalance = VacationBalance::create([
                'employee_id' => $user->id,
                'year' => $currentYear,
                'remaining_days' => 21, // Set the default remaining days as needed
            ]);
        }

        return $leaveBalance->id;
    }

    public function handleHrApproval(Vacation $vacation)
    {
        $leaveBalance = $vacation->leaveBalance;
        $leaveBalance->remaining_days -= $vacation->duration;
        $leaveBalance->save();
    }

    public function hasPendingVacation($userId)
    {
        return Vacation::whereHas('balance', function ($query) use ($userId) {
            $query->where('employee_id', $userId);
        })
        ->where('status', 'pending')
        ->exists();
    }
}
