<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Assessment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'student_id',
        'assessment_code',
        'status',
        'started_at',
        'completed_at',
        'duration_seconds',
        'ip_address',
        'user_agent',
        'score_r',
        'score_i',
        'score_a',
        'score_s',
        'score_e',
        'score_c',
        'riasec_code',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('assessment')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($assessment) {
            if (empty($assessment->assessment_code)) {
                $assessment->assessment_code = static::generateUniqueCode();
            }
        });
    }

    /**
     * Generate a unique assessment code.
     */
    public static function generateUniqueCode(): string
    {
        $prefix = 'ASM';
        $date = now()->format('Ymd');

        do {
            $code = $prefix . $date . strtoupper(Str::random(4));
        } while (static::where('assessment_code', $code)->exists());

        return $code;
    }

    /**
     * Get the student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the answers.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AssessmentAnswer::class);
    }

    /**
     * Get the forced choice answers.
     */
    public function forcedChoiceAnswers(): HasMany
    {
        return $this->hasMany(ForcedChoiceAssessmentAnswer::class);
    }

    /**
     * Get the recommendations.
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(AssessmentRecommendation::class)->orderBy('rank');
    }

    /**
     * Start the assessment.
     */
    public function start(?string $ipAddress = null, ?string $userAgent = null): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Complete the assessment and calculate scores.
     */
    public function complete(): void
    {
        $this->calculateScores();
        $this->refresh(); // Refresh to get updated scores
        $this->generateRiasecCode();
        $this->refresh(); // Refresh to get updated RIASEC code
        $this->generateRecommendations();

        $duration = $this->started_at
            ? now()->diffInSeconds($this->started_at)
            : null;

        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'duration_seconds' => $duration,
        ]);
    }

    /**
     * Calculate RIASEC scores from answers.
     */
    public function calculateScores(): void
    {
        $categories = RiasecCategory::all()->pluck('id', 'code');
        $scores = [];

        foreach ($categories as $code => $categoryId) {
            $categoryAnswers = $this->answers()
                ->whereHas('question', function ($query) use ($categoryId) {
                    $query->where('riasec_category_id', $categoryId);
                })
                ->get();

            if ($categoryAnswers->isEmpty()) {
                $scores['score_' . strtolower($code)] = 0;
                continue;
            }

            // Calculate score: (sum of answers / max possible) * 100
            $totalAnswers = $categoryAnswers->sum('answer');
            $maxPossible = $categoryAnswers->count() * 5; // Max score is 5 per question
            $percentage = round(($totalAnswers / $maxPossible) * 100);

            $scores['score_' . strtolower($code)] = $percentage;
        }

        $this->update($scores);
    }

    /**
     * Generate RIASEC code from top 3 scores.
     */
    public function generateRiasecCode(): void
    {
        $scores = [
            'R' => $this->attributes['score_r'] ?? 0,
            'I' => $this->attributes['score_i'] ?? 0,
            'A' => $this->attributes['score_a'] ?? 0,
            'S' => $this->attributes['score_s'] ?? 0,
            'E' => $this->attributes['score_e'] ?? 0,
            'C' => $this->attributes['score_c'] ?? 0,
        ];

        arsort($scores);
        $topThree = array_slice(array_keys($scores), 0, 3);
        $code = implode('', $topThree);

        $this->update(['riasec_code' => $code]);
    }

    /**
     * Generate major recommendations based on RIASEC scores.
     */
    public function generateRecommendations(): void
    {
        // Delete existing recommendations
        $this->recommendations()->delete();

        $scores = [
            'score_r' => $this->attributes['score_r'] ?? 0,
            'score_i' => $this->attributes['score_i'] ?? 0,
            'score_a' => $this->attributes['score_a'] ?? 0,
            'score_s' => $this->attributes['score_s'] ?? 0,
            'score_e' => $this->attributes['score_e'] ?? 0,
            'score_c' => $this->attributes['score_c'] ?? 0,
        ];

        $majors = SmkMajor::active()->get();
        $recommendations = [];

        foreach ($majors as $major) {
            $matchScore = $major->calculateMatchScore($scores);

            if ($matchScore > 0) {
                $recommendations[] = [
                    'major' => $major,
                    'score' => $matchScore,
                ];
            }
        }

        // Sort by match score descending
        usort($recommendations, fn($a, $b) => $b['score'] <=> $a['score']);

        // Save top 10 recommendations
        foreach (array_slice($recommendations, 0, 10) as $index => $rec) {
            AssessmentRecommendation::create([
                'assessment_id' => $this->id,
                'smk_major_id' => $rec['major']->id,
                'rank' => $index + 1,
                'match_score' => $rec['score'],
                'match_reason' => $this->generateMatchReason($rec['major'], $scores),
            ]);
        }
    }

    /**
     * Generate match reason text.
     */
    protected function generateMatchReason(SmkMajor $major, array $scores): string
    {
        $profile = $major->riasec_profile ?? [];
        $reasons = [];

        $categories = RiasecCategory::all()->pluck('name', 'code');

        foreach ($profile as $code) {
            $scoreKey = 'score_' . strtolower($code);
            $score = $scores[$scoreKey] ?? 0;
            $categoryName = $categories[$code] ?? $code;

            if ($score >= 70) {
                $reasons[] = "Skor {$categoryName} sangat tinggi ({$score}%)";
            } elseif ($score >= 50) {
                $reasons[] = "Skor {$categoryName} baik ({$score}%)";
            }
        }

        return implode(', ', $reasons) ?: 'Cocok berdasarkan profil RIASEC';
    }

    /**
     * Get scores as array.
     * Note: Named 'riasec_scores' to avoid conflict with score_* column names
     */
    public function getRiasecScoresAttribute(): array
    {
        return [
            'R' => $this->attributes['score_r'] ?? 0,
            'I' => $this->attributes['score_i'] ?? 0,
            'A' => $this->attributes['score_a'] ?? 0,
            'S' => $this->attributes['score_s'] ?? 0,
            'E' => $this->attributes['score_e'] ?? 0,
            'C' => $this->attributes['score_c'] ?? 0,
        ];
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return '-';
        }

        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;

        return sprintf('%d menit %d detik', $minutes, $seconds);
    }

    /**
     * Scope for completed assessments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending assessments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
