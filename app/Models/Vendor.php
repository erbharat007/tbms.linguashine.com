<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_code',
        'name',
        'type',
        'status',
        'legal_entity_id',
        'country_id',
        'region',
        'postal_code',
        'city',
        'address',
        'tax_number',
        'vat_number',
        'website',
        'currency_id',
        'payment_terms',
        'manager_id',
        'note',
        'is_assignable',
        'can_access_finances',
    ];

    protected $casts = [
        'is_assignable' => 'boolean',
        'can_access_finances' => 'boolean',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'freelancer', 'individual' => 'Freelancer',
            'company' => 'Company',
            'in-house' => 'In-house',
            default => ucfirst($this->type ?? ''),
        };
    }

    public function legalEntity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(VendorContact::class);
    }

    public function primaryContact(): HasOne
    {
        return $this->hasOne(VendorContact::class)->where('is_primary', true);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(VendorPrice::class);
    }

    public function payables(): HasMany
    {
        return $this->hasMany(VendorPayable::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(VendorInvoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(VendorPayment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(VendorTag::class, 'vendor_tag_vendor');
    }
}
