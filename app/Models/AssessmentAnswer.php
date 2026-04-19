<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_id',
        'answer',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
        ];
    }

    /**
     * Get the assessment.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the question.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the answer label.
     */
    public function getAnswerLabelAttribute(): string
    {
        return match ($this->answer) {
            1 => 'Sangat Tidak Setuju',
            2 => 'Tidak Setuju',
            3 => 'Netral',
            4 => 'Setuju',
            5 => 'Sangat Setuju',
            default => '-',
        };
    }
}
