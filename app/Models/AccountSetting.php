<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AccountSetting extends Model
{
    protected $fillable = [
        'name',
        'type',
        'api_key',
        'timezone',
        'legal_entity_id',
        'currency_id',
        'client_cat_log_template_id',
        'vendor_cat_log_template_id',
        'default_quote_pdf_template',
        'default_client_invoice_pdf_template',
        'default_vendor_invoice_pdf_template',
        'default_job_po_pdf_template',
        'created_by',
    ];

    public static function current(): self
    {
        $setting = static::first();
        if (!$setting) {
            $setting = static::create([
                'name' => 'Linguashine TBMS',
                'type' => 'Enterprise',
                'api_key' => Str::random(40),
                'timezone' => 'Asia/Kolkata',
                'legal_entity_id' => LegalEntity::first()?->id,
                'currency_id' => Currency::where('is_system', true)->first()?->id ?? Currency::first()?->id,
                'created_by' => User::first()?->id,
            ]);
        }
        return $setting;
    }

    public function legalEntity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function clientCatLogTemplate(): BelongsTo
    {
        return $this->belongsTo(CatLogTemplate::class, 'client_cat_log_template_id');
    }

    public function vendorCatLogTemplate(): BelongsTo
    {
        return $this->belongsTo(CatLogTemplate::class, 'vendor_cat_log_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
