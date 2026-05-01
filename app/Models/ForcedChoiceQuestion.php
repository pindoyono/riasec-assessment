<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForcedChoiceQuestion extends Model
{
    protected $fillable = [
        'prompt',
        'option_a_text',
        'option_a_type',
        'option_b_text',
        'option_b_type',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ForcedChoiceAssessmentAnswer::class);
    }

    public static function getForAssessment()
    {
        return static::where('is_active', true)->orderBy('order')->get();
    }
}
