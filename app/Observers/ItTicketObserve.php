<?php

namespace App\Observers;

use App\Models\ItTicket;
use App\Notifications\NewItTicketNotification;
use App\Notifications\TicketEscalatedToFinanceNotification;
use App\Notifications\TicketInProgressNotification;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Support\Facades\Notification;

class ItTicketObserve
{
    /**
     * Handle the ItTicket "created" event.
     */
    public function created(ItTicket $itTicket): void
    {
        $itEmail = Setting::get('it_email');
        // Notify IT department about new ticket
        Notification::route('mail', $itEmail)
            ->notify(new NewItTicketNotification($itTicket));

    }

    /**
     * Handle the ItTicket "updated" event.
     */
    public function updated(ItTicket $itTicket): void
    {
        // Check if status changed to 'in progress'
        if ($itTicket->isDirty('status') && $itTicket->status === 'in progress') {
            $itTicket->employee->notify(new TicketInProgressNotification($itTicket));
        }

        // Check if wait_accountant changed to true
        if ($itTicket->isDirty('wait_accountant') && $itTicket->wait_accountant) {
            $accountingEmail = Setting::get('accounting_email');

            if ($accountingEmail) {
                Notification::route('mail', $accountingEmail)
                    ->notify(new TicketEscalatedToFinanceNotification($itTicket));
            }

            // Notify employee
            $itTicket->employee->notify(new TicketEscalatedToFinanceNotification($itTicket));
        }
    }
}
