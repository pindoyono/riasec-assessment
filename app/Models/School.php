<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'npsn',
        'address',
        'district',
        'city',
        'province',
        'type',
        'is_active',
        'registration_token',
        'token_expires_at',
        'token_valid_minutes',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'token_expires_at' => 'datetime',
            'token_valid_minutes' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::retrieved(function (School $school) {
            if ($school->registration_token && $school->isTokenExpired()) {
                $school->generateToken();
            }
        });
    }

    /**
     * Get the students of the school.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the users of the school.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get active students count.
     */
    public function getActiveStudentsCountAttribute(): int
    {
        return $this->students()->where('is_active', true)->count();
    }

    /**
     * Scope for active schools.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Generate a new unique registration token.
     */
    public function generateToken(): string
    {
        do {
            $token = strtoupper(Str::random(8));
        } while (static::where('registration_token', $token)->exists());

        $this->registration_token = $token;
        $this->token_expires_at = now()->addMinutes($this->token_valid_minutes ?? 60);
        $this->save();

        return $token;
    }

    /**
     * Check if token is valid (exists and not expired).
     */
    public function isTokenValid(): bool
    {
        if (empty($this->registration_token)) {
            return false;
        }

        if ($this->token_expires_at && $this->token_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Check if token is expired.
     */
    public function isTokenExpired(): bool
    {
        if (empty($this->token_expires_at)) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }

    /**
     * Get remaining time for token validity.
     */
    public function getTokenRemainingTimeAttribute(): ?string
    {
        if (empty($this->token_expires_at)) {
            return null;
        }

        if ($this->token_expires_at->isPast()) {
            return 'Expired';
        }

        return $this->token_expires_at->diffForHumans();
    }

    /**
     * Find school by token. If expired, auto-generate new token and reject the old one.
     */
    public static function findByValidToken(string $token): ?self
    {
        $school = static::where('registration_token', $token)->first();

        if (!$school) {
            return null;
        }

        // If token is expired, generate a new one (old token becomes invalid)
        if ($school->isTokenExpired()) {
            $school->generateToken();
            return null;
        }

        return $school;
    }
}
