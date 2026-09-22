<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'legal_entity_id',
        'client_status_id',
        'client_source_id',
        'country_id',
        'region',
        'postal_code',
        'city',
        'address',
        'tax_number',
        'vat_number',
        'website',
        'note',
        'currency_id',
    ];

    public function legalEntity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ClientStatus::class, 'client_status_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(ClientSource::class, 'client_source_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    /**
     * The client's primary contact person.
     */
    public function primaryContact(): HasOne
    {
        return $this->hasOne(ClientContact::class)->where('is_primary', true);
    }
}
