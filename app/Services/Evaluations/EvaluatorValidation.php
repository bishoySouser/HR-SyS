<?php

namespace App\Services\Evaluations;

use App\Models\Employee;

class EvaluatorValidation
{
    /**
     * Check if the evaluator is a valid manager for the employee
     *
     * @param int $employeeId
     * @param int $evaluatorId
     * @return bool
     */
    public function isValidEvaluator(int $employeeId, int $evaluatorId): bool
    {
        // Get the employee and their manager
        $employee = Employee::find($employeeId);

        if (!$employee) {
            return false;
        }

        // First check: Is the evaluator the direct manager?
        if ($employee->manager_id === $evaluatorId) {
            return true;
        }

        // Second check: Is the evaluator actually a manager?
        $evaluator = Employee::find($evaluatorId);
        if (!$evaluator || !$evaluator->isManager()) {
            return false;
        }

    }
}
