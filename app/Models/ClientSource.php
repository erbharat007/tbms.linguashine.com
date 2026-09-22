<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientSource extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
    ];
}
