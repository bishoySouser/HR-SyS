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
        try {
            // Direct PDF generation
            $pdf = Pdf::loadView('pdfs.evaluation', $this->generate()->getData());

            // Save PDF to temporary file
            $tempPath = storage_path('app/temp_evaluation.pdf');
            $pdf->save($tempPath);

            // Return file path for debugging
            return response()->file($tempPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="evaluation.pdf"'
            ]);
        } catch (\Exception $e) {
            // Detailed error logging
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'PDF generation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
