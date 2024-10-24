<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEvaluation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'employee_id' => 'integer',
        'evaluator_id' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(Employee::class, 'evaluator_id');
    }

    /**
     * get list evaluation for employee don't evaluate this year
     */
    public function getAvaliableEvaluation(): array
    {
        $allEvaluations = [
            'quarter_1',
            'quarter_2',
            'quarter_3',
            'quarter_4',
            'end_of_probation',
            'end_of_year'
        ];

        $completedEvaluations = $this->reviewsAsEmployee()
            ->where('year', date('Y'))
            ->pluck('evaluation_type')
            ->toArray();

        return [
            'completed' => $completedEvaluations,
            'pending' => array_values(array_diff( $allEvaluations, $completedEvaluations))
        ];
    }
}
