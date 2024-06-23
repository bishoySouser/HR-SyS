<?php

namespace App\Observers;

use App\Models\Vacation;
use App\Models\VacationBalance;
use App\Mail\VacationRejectionNotification;
use App\Mail\VacationApprovalNotification;
use Illuminate\Support\Facades\Mail;

class VacationObserver
{

    /**
     * Handle the Vacation "updated" event.
     */
    public function updated(Vacation $vacation): void
    {
        $oldStatus = $vacation->getOriginal('status');
        $newStatus = $vacation->status;

        if ($oldStatus !== 'hr_approved' && $newStatus === 'hr_approved') {
            $this->deductLeaveBalance($vacation);
            $this->sendApprovalEmail($vacation);
        } elseif ($oldStatus === 'hr_approved' && ($newStatus === 'rejected_from_hr' || $newStatus === 'rejected_from_manager')) {
            $this->returnLeaveBalance($vacation);
            $this->sendRejectionEmail($vacation, 'HR');
        } elseif ($newStatus === 'rejected_from_manager') {
            $this->sendRejectionEmail($vacation, 'Manager');
        }
    }

    private function deductLeaveBalance(Vacation $vacation)
    {
        $leaveBalance = VacationBalance::findOrFail($vacation->balance_id);

        if ($leaveBalance->remaining_days >= $vacation->duration) {
            $leaveBalance->remaining_days -= $vacation->duration;
            $leaveBalance->save();
        } else {
            // Handle insufficient balance (e.g., throw an exception or log an error)
            throw new \Exception('Insufficient leave balance');
        }
    }

    private function returnLeaveBalance(Vacation $vacation)
    {
        $leaveBalance = VacationBalance::findOrFail($vacation->balance_id);
        $leaveBalance->remaining_days += $vacation->duration;
        $leaveBalance->save();
    }

    private function sendRejectionEmail(Vacation $vacation, string $rejectedBy)
    {
        $employee = $vacation->balance->employee;
        Mail::to($employee->email)->send(new VacationRejectionNotification($vacation, $rejectedBy));
    }

    private function sendApprovalEmail(Vacation $vacation)
    {
        $employee = $vacation->balance->employee;
        $remainingDays = $vacation->balance->remaining_days;
        Mail::to($employee->email)->send(new VacationApprovalNotification($vacation, $remainingDays));
    }



}
