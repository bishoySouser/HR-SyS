<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\Vacation;
use App\Models\VacationBalance;
use App\Services\Interfaces\LeaveRequestInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VacationService implements LeaveRequestInterface
{
    public function getBalanceForCurrentYear()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        return VacationBalance::where('employee_id', $user->id)
            ->where('year', $currentYear)
            ->first();
    }

    public function canRequest($requestedDays): array
    {
        $user = Auth::user();
        $balance = $this->getBalanceForCurrentYear();

        if (!$balance) {
            return [false, 'No balance found. Please check with HR.'];
        }

        if ($this->hasPending($user->id)) {
            return [false, 'You already have a pending vacation request.'];
        }

        if (!$this->hasEnoughBalance($balance, $requestedDays)) {
            return [false, 'Insufficient balance for the requested vacation days.'];
        }

        return [true, ''];
    }

    public function handleHrApproval(Vacation $vacation)
    {
        $leaveBalance = $vacation->leaveBalance;
        $leaveBalance->remaining_days -= $vacation->duration;
        $leaveBalance->save();
    }

    public function hasPending($employeeId): bool
    {
        return Vacation::whereHas('balance', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId);
        })
        ->where('status', 'pending')
        ->exists();
    }

    private function hasEnoughBalance(VacationBalance $balance, $requestedDays)
    {
        return $balance->remaining_days >= $requestedDays;
    }

    public function createVacation(array $data)
    {
        $user = Auth::user();
        $balance = $this->getBalanceForCurrentYear();

        if (!$balance) {
            throw new \Exception('No balance found. Please check with HR.');
        }

        [$canRequest, $message] = $this->canRequest($data['duration']);

        if (!$canRequest) {
            throw new \Exception($message);
        }

        return Vacation::create([
            'balance_id' => $balance->id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'duration' => $data['duration'],
            'status' => 'pending',
        ]);
    }
}
