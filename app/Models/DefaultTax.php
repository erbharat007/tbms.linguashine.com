<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefaultTax extends Model
{
    protected $fillable = [
        'object_type',
        'tax_id',
        'amount',
        'currency_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Object types a default tax can apply to (value => label).
     *
     * @return array<string, string>
     */
    public static function objectTypes(): array
    {
        return [
            'client' => 'Client',
            'vendor' => 'Vendor',
        ];
    }

    public function getObjectTypeLabelAttribute(): string
    {
        return static::objectTypes()[$this->object_type] ?? ucfirst($this->object_type);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
