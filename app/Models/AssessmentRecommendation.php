<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'smk_major_id',
        'rank',
        'match_score',
        'match_reason',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'decimal:2',
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
     * Get the SMK major.
     */
    public function smkMajor(): BelongsTo
    {
        return $this->belongsTo(SmkMajor::class);
    }

    /**
     * Get match score as percentage string.
     */
    public function getMatchPercentageAttribute(): string
    {
        return number_format($this->match_score, 1) . '%';
    }
}
