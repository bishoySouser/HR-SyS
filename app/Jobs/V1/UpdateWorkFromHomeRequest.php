<?php

namespace App\Jobs\V1;

use App\Models\WorkFromHome;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateWorkFromHomeRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $workFromHomeId,
        protected $action,
        protected $managerId,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $workFromHome = WorkFromHome::findOrFail($this->workFromHomeId);

        if($workFromHome->employee->manager_id !== $this->managerId) {
            throw new \Exception('Unauthorized. This employee is not in your team.');
        }

        if ($workFromHome->status !== 'Pending') {
            throw new \Exception('This request has already been processed.');
        }

        $workFromHome->status = $this->action === 'accept' ? 'accepted by manager' : 'cancelled';
        $workFromHome->save();
    }
}
