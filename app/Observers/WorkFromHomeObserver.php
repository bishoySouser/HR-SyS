<?php

namespace App\Observers;

use App\Models\WorkFromHome;
use App\Notifications\WorkFromHomeRequestNotification;
use App\Notifications\WorkFromHomeApprovalNotification;
use App\Notifications\WorkFromHomeStatusNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WorkFromHomeApprovalMail;
use App\Mail\WorkFromHomeStatusMail;

class WorkFromHomeObserver
{
    /**
     * Handle the WorkFromHome "created" event.
     *
     * @param  \App\Models\WorkFromHome  $workFromHome
     * @return void
     */
    public function created(WorkFromHome $workFromHome)
    {
        // Notify the manager about the new work from home request
        $workFromHome->employee->manager->notify(new WorkFromHomeRequestNotification($workFromHome));
    }

    // /**
    //  * Handle the WorkFromHome "updated" event.
    //  *
    //  * @param  \App\Models\WorkFromHome  $workFromHome
    //  * @return void
    //  */
    public function updated(WorkFromHome $workFromHome)
    {
        // If the status is approved or cancelled, send an email to the employee
        if ($workFromHome->status === 'Approved' || $workFromHome->status === 'Cancelled') {
            Mail::to($workFromHome->employee->email)->send(new WorkFromHomeStatusMail($workFromHome));
        }
    }
}
