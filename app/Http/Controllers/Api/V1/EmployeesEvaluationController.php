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

            CreateEvaluation::dispatchSync(
                $validatedData,
                auth()->id()
            );

            // Return the response with the evaluation model
            return response()->json([
                'status' => true,
                'status_code' => 201,
                'message' => 'Evaluation created successfully',
                'data' => []
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create evaluation',
                'status' => false,
                'status_code' => 500,
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
