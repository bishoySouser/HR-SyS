<?php

namespace App\Exports;

use App\Models\EmployeeEvaluation;
use App\Http\Resources\V1\EvaluationResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use App\Interfaces\InterfaceToPrint;

class EvaluationPdf implements InterfaceToPrint
{
    private $evaluation;

    public function __construct($evaluationId)
    {
        $this->evaluation = EmployeeEvaluation::findOrFail($evaluationId);
        $this->evaluation = (new EvaluationResource($this->evaluation));
    }

    public function generate(): View
    {

        return view('pdfs.evaluation', $this->evaluation->toArray(request()));
    }

    public function download()
    {
        try {
            // Direct PDF generation
            $pdf = Pdf::loadView('pdfs.evaluation', $this->generate()->getData());

            // Save PDF to temporary file
            $tempPath = storage_path('app/temp_evaluation.pdf');
            $pdf->save($tempPath);

            $fileName = "evaluation_". $this->evaluation->employee->full_name . "_" . $this->evaluation->evaluation_type . "_" . $this->evaluation->year;
            $fileName = $fileName . ".pdf";
            // Return file path for debugging
            return response()->file($tempPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
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
