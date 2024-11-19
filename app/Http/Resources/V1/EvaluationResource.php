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

            // approach_to_work
            'followsInstructions' => $this->follows_instructions,
            'acceptsConstructiveCriticism' => $this->accepts_constructive_criticism,
            'flexibleAndAdaptable' => $this->flexible_and_adaptable,

            // technical_skills
            'jobKnowledge' => $this->job_knowledge,
            'followsProcedures' => $this->follows_procedures,
            'worksFullPotential' => $this->works_full_potential,
            'learningNewSkills' => $this->learning_new_skills,

            // quality_of_work
            'accuracy' => $this->accuracy,
            'consistency' => $this->consistency,
            'followUp' => $this->follow_up,

            // handling_targets
            'completionOfWorkOnTime' => $this->completion_of_work_on_time,

            // communication_skills
            'shareInformationKnowledge' => $this->getAttribute('share_information/knowledge'),
            'willingly' => $this->willingly,
            'reporting' => $this->reporting,

            // interpersonal_skills
            'relationshipWithColleagues' => $this->relationship_with_colleagues,
            'cooperation' => $this->cooperation,
            'coordination' => $this->coordination,
            'teamWork' => $this->team_work,
            'punctualityAttendance' => $this->punctuality_attendance,
            'problemSolving' => $this->problem_solving,

            // willingness_to_learn
            'openToIdeas' => $this->open_to_ideas,
            'seeksTraining' => $this->seeks_training,


            'employeesAchievements' => $this->employees_achievements,
            'performanceAndProgress' => $this->performance_and_progress,
            'newGoalsToAchieve' => $this->new_goals_to_achieve,
            'createdAt' => $this->created_at,
            // Add other evaluation fields as needed

            'printPdf' => [
                "url" => route('evaluations.pdf', ['id' => $this->id]),
                'sub-url' => "api/v1/evaluations/pdf/$this->id"
            ]
        ];
    }
}
