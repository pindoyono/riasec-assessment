<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SmkMajor extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code',
        'name',
        'program_keahlian',
        'bidang_keahlian',
        'description',
        'career_prospects',
        'skills_learned',
        'riasec_profile',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'riasec_profile' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('smk_major')
            ->logFillable()
            ->logOnlyDirty();
    }

    /**
     * Get the recommendations for this major.
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(AssessmentRecommendation::class);
    }

    /**
     * Scope for active majors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get primary RIASEC code (first two letters).
     */
    public function getPrimaryCodeAttribute(): string
    {
        $profile = $this->riasec_profile ?? [];
        return implode('', array_slice($profile, 0, 2));
    }

    /**
     * Get full name with program keahlian.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} ({$this->program_keahlian})";
    }

    /**
     * Calculate match score with given RIASEC scores.
     */
    public function calculateMatchScore(array $scores): float
    {
        if (empty($this->riasec_profile)) {
            return 0;
        }

        $totalWeight = 0;
        $weightedScore = 0;
        $weights = [1 => 3, 2 => 2, 3 => 1]; // Primary, secondary, tertiary weights

        foreach ($this->riasec_profile as $index => $code) {
            $weight = $weights[$index + 1] ?? 0.5;
            $scoreKey = 'score_' . strtolower($code);

            if (isset($scores[$scoreKey])) {
                $weightedScore += $scores[$scoreKey] * $weight;
                $totalWeight += 100 * $weight;
            }
        }

        if ($totalWeight === 0) {
            return 0;
        }

        return round(($weightedScore / $totalWeight) * 100, 2);
    }

    /**
     * Find majors matching given RIASEC code.
     */
    public static function findByRiasecCode(string $code): \Illuminate\Support\Collection
    {
        $codes = str_split(strtoupper($code));

        return static::active()
            ->get()
            ->filter(function ($major) use ($codes) {
                if (empty($major->riasec_profile)) {
                    return false;
                }

                // Check if any of the major's RIASEC codes match the given codes
                return count(array_intersect($major->riasec_profile, $codes)) > 0;
            })
            ->sortByDesc(function ($major) use ($codes) {
                // Sort by number of matching codes
                return count(array_intersect($major->riasec_profile, $codes));
            });
    }
}
