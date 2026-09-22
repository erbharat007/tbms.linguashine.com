<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LanguagePair extends Model
{
    protected $fillable = [
        'source_language_id',
        'target_language_id',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function sourceLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'source_language_id');
    }

    public function targetLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'target_language_id');
    }

    /**
     * Display label, e.g. "English - French".
     */
    public function getLabelAttribute(): string
    {
        return "{$this->sourceLanguage?->name} - {$this->targetLanguage?->name}";
    }

    /**
     * Short tag, e.g. "en - fr".
     */
    public function getTagAttribute(): string
    {
        return "{$this->sourceLanguage?->code} - {$this->targetLanguage?->code}";
    }
}
