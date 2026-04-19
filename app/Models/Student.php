<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'asal_sekolah',
        'school_id',
        'class',
        'phone',
        'email',
        'address',
        'parent_name',
        'parent_phone',
        'registration_token',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->registration_token)) {
                $student->registration_token = static::generateUniqueToken();
            }
        });
    }

    /**
     * Generate a unique registration token.
     */
    public static function generateUniqueToken(): string
    {
        do {
            $token = strtoupper(Str::random(8));
        } while (static::where('registration_token', $token)->exists());

        return $token;
    }

    /**
     * Get the school.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all assessments.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    /**
     * Get the latest assessment.
     */
    public function latestAssessment(): HasOne
    {
        return $this->hasOne(Assessment::class)->latestOfMany();
    }

    /**
     * Get completed assessment.
     */
    public function completedAssessment(): HasOne
    {
        return $this->hasOne(Assessment::class)
            ->where('status', 'completed')
            ->latestOfMany();
    }

    /**
     * Check if student has completed assessment.
     */
    public function hasCompletedAssessment(): bool
    {
        return $this->assessments()->where('status', 'completed')->exists();
    }

    /**
     * Scope for active students.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get gender label.
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get age.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    /**
     * Find student by registration token.
     */
    public static function findByToken(string $token): ?self
    {
        return static::where('registration_token', strtoupper($token))
            ->active()
            ->first();
    }
}
