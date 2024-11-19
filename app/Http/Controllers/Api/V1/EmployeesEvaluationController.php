<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\EvaluationPdf;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\V1\StoreEmployeesEvaluationRequest;
use App\Http\Resources\V1\EvaluationResource;
use App\Jobs\V1\Evaluation\CreateEvaluation;
use App\Models\EmployeeEvaluation;
use App\Services\V1\EvaluationFilter;
use Illuminate\Http\Request;

class EmployeesEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $evaluatorId = auth()->id();

        $evaluations = EmployeeEvaluation::with('employee')->where('evaluator_id', $evaluatorId);

            // $filter = new EvaluationFilter();

            // $filterItems = $filter->transform($request);
        // return $request->query('employeeId');

        if ($request->query('employeeId')) {
            $evaluations = $evaluations->whereHas('employee', function($query) use (&$request) {
                                $query->where('id', '=', $request->query('employeeId'));
                            });
                            // ->where([
                            //     ['year', '=', $request->query('year')],
                            //     ['evaluation_type', '=', $request->query('evaluationType')]
                            // ]);

        }



        if ($request->query('year')) {
            $evaluations->where('year', $request->input('year'));
        }

        if ($request->query('evaluationType')) {
            $evaluations->where('evaluation_type', $request->input('evaluationType'));
        }

        $evaluations = $evaluations->paginate(10);

        $evaluations =  EvaluationResource::collection($evaluations);

        return response()->json([
                    'status' => true,
                    'status_code' => 201,
                    'message' => 'Get evaluations list successfully',
                    'data' => $evaluations
                ], 200);
    }

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

    public function generatePDF($evaluation_id)
    {

        try {
            $pdfGenerator = new EvaluationPdf($evaluation_id);
            return $pdfGenerator->download();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
