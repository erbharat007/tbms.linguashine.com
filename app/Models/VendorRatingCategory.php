<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRatingCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'weight',
        'status',
    ];

    protected $casts = [
        'weight' => 'integer',
    ];
}
