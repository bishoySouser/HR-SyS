<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEmployeesEvaluationRequest;
use App\Jobs\V1\Evaluation\CreateEvaluation;

class EmployeesEvaluationController extends Controller
{
    public function store(StoreEmployeesEvaluationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            CreateEvaluation::dispatchAfterResponse(
                $validatedData,
                auth()->id()
            );

            // Return the response with the evaluation model
            return response()->json([
                'message' => 'Evaluation created successfully',
                'data' => []
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create evaluation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
