<?php

namespace App\Jobs\V1;

use App\Models\Excuse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExcuseRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        protected $excuseId,
        protected $action,
        protected $managerId,
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $excuse = Excuse::findOrFail($this->excuseId);

        if($excuse->employee->manager_id !== $this->managerId) {
            throw new \Exception('Unauthorized. This employee is not in your team.');
        }

        if ($excuse->status !== 'Pending') {
            throw new \Exception('This request has already been processed.');
        }

        $excuse->status = $this->action === 'accept' ? 'accepted by manager' : 'cancelled';
        $excuse->save();
    }
}
