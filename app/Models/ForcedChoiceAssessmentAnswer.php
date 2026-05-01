<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForcedChoiceAssessmentAnswer extends Model
{
    protected $fillable = [
        'assessment_id',
        'forced_choice_question_id',
        'selected_option',
        'selected_type',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ForcedChoiceQuestion::class, 'forced_choice_question_id');
    }

    /**
     * Calculate RIASEC scores from forced choice answers for a given assessment.
     *
     * @return array<string, int>  ['R' => x, 'I' => x, 'A' => x, 'S' => x, 'E' => x, 'C' => x]
     */
    public static function calculateScores(int $assessmentId): array
    {
        $scores = ['R' => 0, 'I' => 0, 'A' => 0, 'S' => 0, 'E' => 0, 'C' => 0];

        $answers = static::where('assessment_id', $assessmentId)->get();

        foreach ($answers as $answer) {
            if (array_key_exists($answer->selected_type, $scores)) {
                $scores[$answer->selected_type]++;
            }
        }

        return $scores;
    }

    /**
     * Get RIASEC code (top 3 types) from scores.
     */
    public static function getRiasecCode(array $scores): string
    {
        arsort($scores);
        return implode('', array_slice(array_keys($scores), 0, 3));
    }

    /**
     * Normalize raw FC counts to 0-100 proportion.
     * e.g. if 30 answers: R=10 → 33.33, total always sums to 100.
     *
     * @return array<string, float>
     */
    public static function calculateNormalizedScores(int $assessmentId): array
    {
        $raw   = static::calculateScores($assessmentId);
        $total = array_sum($raw);

        if ($total === 0) {
            return $raw; // no answers yet, return zeros
        }

        return array_map(fn($v) => round(($v / $total) * 100, 2), $raw);
    }

    /**
     * Combine Likert scores (0-100 %) and Forced Choice normalized scores (0-100 %)
     * using weighted average: 60 % Likert + 40 % Forced Choice.
     *
     * @param  array<string, float>  $likertScores  ['R'=>float, 'I'=>float, ...]
     * @param  array<string, float>  $fcNormalized  ['R'=>float, 'I'=>float, ...]
     * @return array<string, float>
     */
    public static function combineScores(array $likertScores, array $fcNormalized): array
    {
        $types = ['R', 'I', 'A', 'S', 'E', 'C'];
        $combined = [];

        foreach ($types as $type) {
            $combined[$type] = round(
                ($likertScores[$type] ?? 0) * 0.60 + ($fcNormalized[$type] ?? 0) * 0.40,
                2
            );
        }

        return $combined;
    }
}
