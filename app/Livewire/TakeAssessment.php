<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\Question;
use Livewire\Component;
use Illuminate\Support\Collection;

class TakeAssessment extends Component
{
    public Assessment $assessment;
    public Collection $questions;
    public int $currentIndex = 0;
    public array $answers = [];
    public bool $isCompleted = false;

    public function mount(string $assessmentCode)
    {
        $this->assessment = Assessment::where('assessment_code', $assessmentCode)
            ->with('student')
            ->firstOrFail();

        // If already completed, redirect to forced choice (if not done) or result
        if ($this->assessment->status === 'completed') {
            $hasFc = ForcedChoiceAssessmentAnswer::where('assessment_id', $this->assessment->id)->exists();

            if (!$hasFc) {
                return redirect()->route('assessment.forced-choice.take', [
                    'assessmentCode' => $this->assessment->assessment_code,
                ]);
            }

            return redirect()->route('assessment.result', [
                'assessmentCode' => $this->assessment->assessment_code,
            ]);
        }

        // Start assessment if pending
        if ($this->assessment->status === 'pending') {
            $this->assessment->start(
                request()->ip(),
                request()->userAgent()
            );
        }

        // Load questions
        $this->questions = Question::getForAssessment();

        // Load existing answers
        $existingAnswers = $this->assessment->answers()->pluck('answer', 'question_id');
        foreach ($existingAnswers as $questionId => $answer) {
            $this->answers[$questionId] = $answer;
        }

        // Find current question (first unanswered)
        foreach ($this->questions as $index => $question) {
            if (!isset($this->answers[$question->id])) {
                $this->currentIndex = $index;
                break;
            }
        }
    }

    public function answerQuestion(int $questionId, int $answer)
    {
        // Validate answer (1-5)
        if ($answer < 1 || $answer > 5) {
            return;
        }

        // Save answer
        AssessmentAnswer::updateOrCreate(
            [
                'assessment_id' => $this->assessment->id,
                'question_id' => $questionId,
            ],
            [
                'answer' => $answer,
                'answered_at' => now(),
            ]
        );

        $this->answers[$questionId] = $answer;

        // Move to next question
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
            $this->dispatch('question-advanced');
        } else {
            // Check if all questions answered
            $answeredCount = count($this->answers);
            if ($answeredCount >= $this->questions->count()) {
                $this->completeAssessment();
            }
        }
    }

    public function goToQuestion(int $index)
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentIndex = $index;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
        }
    }

    public function completeAssessment()
    {
        // Verify all questions answered
        if (count($this->answers) < $this->questions->count()) {
            session()->flash('error', 'Harap jawab semua pertanyaan terlebih dahulu.');
            return;
        }

        // Complete the assessment (calculates Likert scores)
        $this->assessment->complete();
        $this->isCompleted = true;

        // Redirect to forced choice assessment next
        return redirect()->route('assessment.forced-choice.take', [
            'assessmentCode' => $this->assessment->assessment_code,
        ]);
    }

    public function getCurrentQuestionProperty()
    {
        return $this->questions[$this->currentIndex] ?? null;
    }

    public function getProgressPercentageProperty()
    {
        if ($this->questions->isEmpty()) {
            return 0;
        }

        return round((count($this->answers) / $this->questions->count()) * 100);
    }

    public function render()
    {
        return view('livewire.take-assessment')
            ->layout('layouts.assessment');
    }
}
