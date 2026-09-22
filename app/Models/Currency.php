<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_enabled',
        'is_system',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_system' => 'boolean',
    ];
}
