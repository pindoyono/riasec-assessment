<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\RiasecCategory;
use App\Models\SmkMajor;
use Livewire\Component;

class ForcedChoiceResult extends Component
{
    public Assessment $assessment;
    public array $scores = [];
    public string $riasecCode = '';
    public $categories;
    public $chartData;

    public function mount(string $assessmentCode)
    {
        $this->assessment = Assessment::where('assessment_code', $assessmentCode)
            ->with('student.school')
            ->firstOrFail();

        // Calculate forced-choice scores
        $this->scores = ForcedChoiceAssessmentAnswer::calculateScores($this->assessment->id);

        // Generate RIASEC code (top 3)
        $this->riasecCode = ForcedChoiceAssessmentAnswer::getRiasecCode($this->scores);

        $this->categories = RiasecCategory::orderBy('order')->get();

        // Prepare chart data in RIASEC order
        $this->chartData = [
            'labels' => $this->categories->pluck('name')->toArray(),
            'data'   => [
                $this->scores['R'] ?? 0,
                $this->scores['I'] ?? 0,
                $this->scores['A'] ?? 0,
                $this->scores['S'] ?? 0,
                $this->scores['E'] ?? 0,
                $this->scores['C'] ?? 0,
            ],
        ];
    }

    public function render()
    {
        return view('livewire.forced-choice-result')
            ->layout('layouts.assessment');
    }
}
