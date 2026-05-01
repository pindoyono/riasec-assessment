<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\RiasecCategory;
use App\Models\SmkMajor;
use Livewire\Component;

class AssessmentResult extends Component
{
    public Assessment $assessment;
    public $categories;
    public $chartData;
    public $allMajors;

    public function mount(string $assessmentCode)
    {
        $this->assessment = Assessment::where('assessment_code', $assessmentCode)
            ->with(['student.school', 'recommendations.smkMajor'])
            ->firstOrFail();

        // If not completed, redirect back to Likert
        if ($this->assessment->status !== 'completed') {
            return redirect()->route('assessment.take', [
                'assessmentCode' => $this->assessment->assessment_code,
            ]);
        }

        // If completed but forced choice not done yet, redirect to forced choice
        $hasForcedChoice = ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)->exists();
        if (!$hasForcedChoice) {
            return redirect()->route('assessment.forced-choice.take', [
                'assessmentCode' => $this->assessment->assessment_code,
            ]);
        }

        $this->categories = RiasecCategory::orderBy('order')->get();
        $this->allMajors = SmkMajor::where('is_active', true)->get();

        // Prepare chart data for radar chart
        $this->chartData = [
            'labels' => $this->categories->pluck('name')->toArray(),
            'data' => [
                $this->assessment->score_r ?? 0,
                $this->assessment->score_i ?? 0,
                $this->assessment->score_a ?? 0,
                $this->assessment->score_s ?? 0,
                $this->assessment->score_e ?? 0,
                $this->assessment->score_c ?? 0,
            ],
        ];
    }

    public function downloadPdf()
    {
        return redirect()->route('assessment.pdf', [
            'assessment' => $this->assessment->assessment_code,
        ]);
    }

    public function render()
    {
        return view('livewire.assessment-result')
            ->layout('layouts.assessment');
    }
}
