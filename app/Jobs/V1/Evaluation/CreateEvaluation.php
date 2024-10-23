<?php

namespace App\Jobs\V1\Evaluation;

use App\Models\EmployeeEvaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateEvaluation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $evaluatorId;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data, int $evaluatorId)
    {
        $this->data = $data;
        $this->evaluatorId = $evaluatorId;
    }

    /**
     * Execute the job.
     */
    public function handle(): EmployeeEvaluation
    {
        try {
            // Merge evaluator_id with evaluation data
            $this->data['evaluator_id'] = $this->evaluatorId;

            // Create the evaluation
            $evaluation = EmployeeEvaluation::create($this->data);

            // \Log::info('Evaluation from job: ', ['evaluation' => $evaluation]);

            return $evaluation;

        } catch (\Exception $e) {
            // Log the error
            Log::error('Failed to create employee evaluation', [
                'error' => $e->getMessage(),
                'evaluator_id' => $this->evaluatorId
            ]);

            throw $e;
        }
    }
}
