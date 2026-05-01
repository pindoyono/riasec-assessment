<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\ForcedChoiceQuestion;
use App\Models\ForcedChoiceAssessmentAnswer;
use Livewire\Component;
use Illuminate\Support\Collection;

class TakeForcedChoiceAssessment extends Component
{
    public Assessment $assessment;
    public Collection $questions;
    public int $currentIndex = 0;
    public array $answers = []; // [question_id => 'A'|'B']
    public bool $isCompleted = false;

    public function mount(string $assessmentCode)
    {
        $this->assessment = Assessment::where('assessment_code', $assessmentCode)
            ->with('student.school')
            ->firstOrFail();

        // Start assessment if still pending
        if ($this->assessment->status === 'pending') {
            $this->assessment->start(
                request()->ip(),
                request()->userAgent()
            );
        }

        // Load active forced-choice questions
        $this->questions = ForcedChoiceQuestion::getForAssessment();

        // Load previously saved answers
        $existing = ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)
            ->pluck('selected_option', 'forced_choice_question_id');

        foreach ($existing as $questionId => $option) {
            $this->answers[(int) $questionId] = $option;
        }

        // Resume at first unanswered question
        foreach ($this->questions as $index => $question) {
            if (!isset($this->answers[$question->id])) {
                $this->currentIndex = $index;
                break;
            }
        }

        // If all already answered, mark completed
        if (count($this->answers) >= $this->questions->count() && $this->questions->isNotEmpty()) {
            $this->isCompleted = true;
        }
    }

    public function answerQuestion(int $questionId, string $selectedOption): void
    {
        if (!in_array($selectedOption, ['A', 'B'], true)) {
            return;
        }

        $question = $this->questions->firstWhere('id', $questionId);
        if (!$question) {
            return;
        }

        $selectedType = $selectedOption === 'A'
            ? $question->option_a_type
            : $question->option_b_type;

        ForcedChoiceAssessmentAnswer::updateOrCreate(
            [
                'assessment_id'              => $this->assessment->id,
                'forced_choice_question_id'  => $questionId,
            ],
            [
                'selected_option' => $selectedOption,
                'selected_type'   => $selectedType,
                'answered_at'     => now(),
            ]
        );

        $this->answers[$questionId] = $selectedOption;

        // Auto-advance
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
            $this->dispatch('question-advanced');
        } elseif (count($this->answers) >= $this->questions->count()) {
            $this->isCompleted = true;
        }
    }

    public function goToQuestion(int $index): void
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentIndex = $index;
        }
    }

    public function previousQuestion(): void
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
        }
    }

    public function completeAssessment()
    {
        if (count($this->answers) < $this->questions->count()) {
            session()->flash('error', 'Harap jawab semua pertanyaan terlebih dahulu.');
            return;
        }

        // Normalize FC scores to 0-100 proportion
        $fcNormalized = ForcedChoiceAssessmentAnswer::calculateNormalizedScores($this->assessment->id);

        // Combine with Likert scores: 60% Likert + 40% Forced Choice
        $likert = [
            'R' => $this->assessment->score_r ?? 0,
            'I' => $this->assessment->score_i ?? 0,
            'A' => $this->assessment->score_a ?? 0,
            'S' => $this->assessment->score_s ?? 0,
            'E' => $this->assessment->score_e ?? 0,
            'C' => $this->assessment->score_c ?? 0,
        ];

        $combined = ForcedChoiceAssessmentAnswer::combineScores($likert, $fcNormalized);

        arsort($combined);
        $riasecCode = implode('', array_slice(array_keys($combined), 0, 3));

        // Persist combined scores back to the assessment
        $this->assessment->update([
            'score_r'     => $combined['R'],
            'score_i'     => $combined['I'],
            'score_a'     => $combined['A'],
            'score_s'     => $combined['S'],
            'score_e'     => $combined['E'],
            'score_c'     => $combined['C'],
            'riasec_code' => $riasecCode,
        ]);

        // Re-generate recommendations based on combined scores
        $this->assessment->refresh();
        $this->assessment->generateRecommendations();

        return redirect()->route('assessment.result', [
            'assessmentCode' => $this->assessment->assessment_code,
        ]);
    }

    public function getCurrentQuestionProperty(): ?ForcedChoiceQuestion
    {
        return $this->questions[$this->currentIndex] ?? null;
    }

    public function getProgressPercentageProperty(): int
    {
        if ($this->questions->isEmpty()) {
            return 0;
        }
        return (int) round((count($this->answers) / $this->questions->count()) * 100);
    }

    public function render()
    {
        return view('livewire.take-forced-choice-assessment')
            ->layout('layouts.assessment');
    }
}
