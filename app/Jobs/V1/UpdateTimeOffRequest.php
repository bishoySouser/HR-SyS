<?php

namespace App\Jobs\V1;

use App\Models\Vacation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateTimeOffRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $timeOffId,
        protected $action,
        protected $managerId,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $timeOff = Vacation::findOrFail($this->timeOffId);

        if($timeOff->balance->employee->manager_id !== $this->managerId) {
            throw new \Exception('Unauthorized. This employee is not in your team.');
        }

        if ($timeOff->status !== 'pending') {
            throw new \Exception('This request has already been processed.');
        }

        $timeOff->status = $this->action === 'accept' ? 'manager_confirm' : 'rejected_from_manager';
        $timeOff->save();
    }
}
