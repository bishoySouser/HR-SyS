<?php

namespace App\Exports;

use App\Models\EmployeeEvaluation;
use App\Http\Resources\V1\EvaluationResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use App\Interfaces\InterfaceToPrint;

class EvaluationPdf implements InterfaceToPrint
{
    private $evaluationId;

    public function __construct($evaluationId)
    {
        $this->evaluationId = $evaluationId;
    }

    public function generate(): View
    {
        $data = EmployeeEvaluation::findOrFail($this->evaluationId);
        $data = (new EvaluationResource($data))->toArray(request());

        return view('pdfs.evaluation', $data);
    }

    public function download()
    {
        $pdf = Pdf::loadView('pdfs.evaluation', $this->generate()->getData());

        // For Axios handling, return the response with proper headers
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="evaluation_' . $this->evaluationId . '.pdf"',
            'Content-Length' => strlen($pdf->output())
        ]);
    }
}
