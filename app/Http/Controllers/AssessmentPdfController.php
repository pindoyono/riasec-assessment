<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\RiasecCategory;
use App\Models\SmkMajor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AssessmentPdfController extends Controller
{
    public function download(string $assessment)
    {
        $assessment = Assessment::where('assessment_code', $assessment)
            ->with(['student.school', 'recommendations.smkMajor'])
            ->firstOrFail();

        if ($assessment->status !== 'completed') {
            abort(404, 'Assessment belum selesai');
        }

        $categories = RiasecCategory::orderBy('order')->get();

        $data = [
            'assessment' => $assessment,
            'student' => $assessment->student,
            'categories' => $categories,
            'topCategories' => $categories->filter(
                fn($cat) => in_array($cat->code, str_split($assessment->riasec_code ?? ''))
            ),
            'recommendations' => $assessment->recommendations,
            'allMajors' => SmkMajor::where('is_active', true)->get(),
        ];

        $pdf = Pdf::loadView('pdf.assessment-result', $data);
        $pdf->setPaper([0, 0, 612, 936], 'portrait'); // F4 (8.5 x 13 inches)
        $pdf->getDomPDF()->getOptions()->setIsRemoteEnabled(true);

        $filename = 'RIASEC_' . $assessment->student->name . '_' . $assessment->completed_at->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function preview(string $assessment)
    {
        $assessment = Assessment::where('assessment_code', $assessment)
            ->with(['student.school', 'recommendations.smkMajor'])
            ->firstOrFail();

        if ($assessment->status !== 'completed') {
            abort(404, 'Assessment belum selesai');
        }

        $categories = RiasecCategory::orderBy('order')->get();

        $data = [
            'assessment' => $assessment,
            'student' => $assessment->student,
            'categories' => $categories,
            'topCategories' => $categories->filter(
                fn($cat) => in_array($cat->code, str_split($assessment->riasec_code ?? ''))
            ),
            'recommendations' => $assessment->recommendations,
            'allMajors' => SmkMajor::where('is_active', true)->get(),
        ];

        // Return as HTML for preview
        return view('pdf.assessment-result', $data);
    }
}
