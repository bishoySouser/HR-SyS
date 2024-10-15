<?php

namespace App\Services;

use App\Models\EmployeeEvaluation;
use App\Models\Employee;

class EmployeeEvaluationService
{
    public function create(array $data): EmployeeEvaluation
    {
        return EmployeeEvaluation::create($data);
    }

    public function update(EmployeeEvaluation $evaluation, array $data): bool
    {
        return $evaluation->update($data);
    }

    public function delete(EmployeeEvaluation $evaluation): bool
    {
        return $evaluation->delete();
    }

    public function getEmployeeEvaluations(Employee $employee)
    {
        return EmployeeEvaluation::where('employee_id', $employee->id)->get();
    }

    public function getEvaluatorEvaluations(Employee $evaluator)
    {
        return EmployeeEvaluation::where('evaluator_id', $evaluator->id)->get();
    }

    public function getEvaluationsByType(Employee $employee, string $type)
    {
        return EmployeeEvaluation::where('employee_id', $employee->id)
                                  ->where('evaluation_type', $type)
                                  ->get();
    }
}
