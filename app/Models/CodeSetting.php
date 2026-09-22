<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeSetting extends Model
{
    protected $fillable = [
        'include_year_in_invoice_codes',
    ];

    protected $casts = [
        'include_year_in_invoice_codes' => 'boolean',
    ];

    /**
     * The single code-settings row, creating it with defaults if missing.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
