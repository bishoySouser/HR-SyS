<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'evaluationType' => $this->evaluation_type,
            'employee' => new EmployeeResource($this->employee),
            'reviewer' => new EmployeeResource($this->evaluator),
            'year' => $this->year,
            'status' => $this->status,
            // 'approach_to_work'
            'followsInstructions' => $this->follows_instructions,
            'acceptsConstructiveCriticism' => $this->accepts_constructive_criticism,
            'flexibleAndAdaptable' => $this->flexible_and_adaptable,


            'employeesAchievements' => $this->employees_achievements,
            'performanceAndProgress' => $this->performance_and_progress,
            'newGoalsToAchieve' => $this->new_goals_to_achieve,
            'createdAt' => $this->created_at,
            // Add other evaluation fields as needed
        ];
    }
}
