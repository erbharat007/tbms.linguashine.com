<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LegalEntity extends Model
{
    protected $fillable = [
        'name',
        'website',
        'tax_number',
        'vat_number',
        'country_id',
        'region',
        'postal_code',
        'city',
        'address',
        'phone',
        'logo_path',
        'first_client_invoice_number',
        'first_vendor_invoice_number',
        'client_invoice_prefix',
        'vendor_invoice_prefix',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Public URL to the logo, or null when none is set.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }
}
