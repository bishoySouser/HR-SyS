<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEmployeesEvaluationRequest;
use App\Models\EmployeeEvaluation;
use Illuminate\Http\Request;

class EmployeesEvaluationController extends Controller
{
    public function store(StoreEmployeesEvaluationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Add evaluator_id from authenticated user
            $validatedData['evaluator_id'] = auth()->id();

            $evaluation = EmployeeEvaluation::create($validatedData);

            return response()->json([
                'message' => 'Evaluation created successfully',
                'data' => $evaluation->load(['employee', 'evaluator'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create evaluation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
