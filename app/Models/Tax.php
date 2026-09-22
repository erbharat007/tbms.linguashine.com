<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name',
        'type',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Tax type options (value => label).
     *
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            'percentage' => 'Percentage',
            'absolute' => 'Absolute',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return static::types()[$this->type] ?? ucfirst($this->type);
    }
}
