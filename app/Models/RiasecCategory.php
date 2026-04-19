<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiasecCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'characteristics',
        'preferred_activities',
        'strengths',
        'color',
        'icon',
        'order',
    ];

    /**
     * Get the questions of this category.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get category by code.
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', strtoupper($code))->first();
    }

    /**
     * Get all categories ordered.
     */
    public static function getAllOrdered()
    {
        return static::orderBy('order')->get();
    }

    /**
     * Get the full name with code.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }
}
