<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\EvaluationPdf;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\V1\StoreEmployeesEvaluationRequest;
use App\Http\Resources\V1\EvaluationResource;
use App\Jobs\V1\Evaluation\CreateEvaluation;
use App\Models\EmployeeEvaluation;
use Illuminate\Http\Request;

class EmployeesEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $evaluatorId = auth()->id();
        $query = EmployeeEvaluation::where('evaluator_id', $evaluatorId);

        if ($request->has('employee_name')) {
            $query->join('employees', 'employee_evaluations.employee_id', '=', 'employees.id')
                  ->where('employees.name', 'like', '%' . $request->input('employee_name') . '%');
        }

        if ($request->has('evaluation_year')) {
            $query->whereYear('evaluation_date', $request->input('evaluation_year'));
        }

        if ($request->has('evaluation_type')) {
            $query->where('evaluation_type', $request->input('evaluation_type'));
        }

        $evaluations = $query->paginate(10);

        $evaluations =  EvaluationResource::collection($evaluations)->additional([
                            'links' => [
                                'print_pdf' => $evaluations->map(function($evaluation) {
                                    return [
                                        'id' => $evaluation->id,
                                        'url' => route('evaluations.pdf', ['id' => $evaluation->id])
                                    ];
                                })
                            ]
                        ]);

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
        $pdfGenerator = new EvaluationPdf($evaluation_id);
        return $pdfGenerator->download();
    }
}
