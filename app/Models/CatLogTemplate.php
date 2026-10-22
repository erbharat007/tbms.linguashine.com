<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatLogTemplate extends Model
{
    protected $fillable = [
        'name',
        'is_default_client_account',
        'is_default_client_profile',
        'is_default_vendor_account',
        'is_default_vendor_profile',
        'rate_repetitions',
        'rate_100',
        'rate_95_99',
        'rate_85_94',
        'rate_75_84',
        'rate_50_74',
        'rate_no_match',
        'created_by',
    ];

    protected $casts = [
        'is_default_client_account' => 'boolean',
        'is_default_client_profile' => 'boolean',
        'is_default_vendor_account' => 'boolean',
        'is_default_vendor_profile' => 'boolean',
        'rate_repetitions' => 'decimal:2',
        'rate_100' => 'decimal:2',
        'rate_95_99' => 'decimal:2',
        'rate_85_94' => 'decimal:2',
        'rate_75_84' => 'decimal:2',
        'rate_50_74' => 'decimal:2',
        'rate_no_match' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
