<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'riasec_category_id',
        'question_text',
        'question_text_en',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the RIASEC category.
     */
    public function riasecCategory(): BelongsTo
    {
        return $this->belongsTo(RiasecCategory::class);
    }

    /**
     * Get the answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    /**
     * Scope for active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all active questions for assessment, randomized within each category.
     */
    public static function getForAssessment()
    {
        return static::with('riasecCategory')
            ->active()
            ->orderBy('riasec_category_id')
            ->orderBy('order')
            ->get()
            ->groupBy('riasec_category_id')
            ->flatMap(fn($questions) => $questions->shuffle())
            ->values();
    }
}
