<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEvaluation extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $guarded = ['id'];

    public $timestamps = [ "created_at" ];

    // protected $fillable = [
    //     'employee_id'
    // ];

    // protected $casts = [
    //     'employee_id' => 'integer',
    //     'evaluator_id' => 'integer',
    // ];
    public function printPdf($crud = false)
    {
        return '<a class="btn btn-sm btn-link" target="_blank" href="'. route('evaluations.download', ['id' => $this->id]).'" data-toggle="tooltip" title="download evaluation file."><i class="la la-download"></i>PDF</a>';
    }

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
