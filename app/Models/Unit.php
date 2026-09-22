<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'is_word',
        'is_enabled',
    ];

    protected $casts = [
        'is_word' => 'boolean',
        'is_enabled' => 'boolean',
    ];
}
