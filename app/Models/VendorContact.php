<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorContact extends Model
{
    protected $fillable = [
        'vendor_id',
        'first_name',
        'last_name',
        'designation',
        'email',
        'timezone_id',
        'gender',
        'phone',
        'skype',
        'is_primary',
        'can_login',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'can_login' => 'boolean',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
