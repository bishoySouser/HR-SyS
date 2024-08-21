<?php

namespace App\Observers;

use App\Models\Excuse;
use App\Notifications\ExcuseRequestNotification;
use App\Notifications\ExcuseApprovalNotification;
use App\Notifications\ExcuseStatusNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExcuseApprovalMail;
use App\Mail\ExcuseStatusMail;

class ExcuseObserver
{
    /**
     * Handle the Excuse "created" event.
     *
     * @param  \App\Models\Excuse  $excuse
     * @return void
     */
    public function created(Excuse $excuse)
    {
        // Notify the manager about the new excuse request
        $excuse->employee->manager->notify(new ExcuseRequestNotification($excuse));
    }

    /**
     * Handle the Excuse "updated" event.
     *
     * @param  \App\Models\Excuse  $excuse
     * @return void
     */
    // public function updated(Excuse $excuse)
    // {
    //     // If the status is approved, send an email to HR
    //     // if ($excuse->status === 'Approved') {
    //     //     Mail::to('hr@example.com')->send(new ExcuseApprovalMail($excuse));
    //     // }

    //     // If the status is approved or cancelled, send an email to the employee
    //     if ($excuse->status === 'Approved' || $excuse->status === 'Cancelled') {
    //         Mail::to($excuse->employee->email)->send(new ExcuseStatusMail($excuse));
    //     }
    // }
}
