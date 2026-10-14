<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPayable extends Model
{
    protected $fillable = [
        'payable_number',
        'vendor_id',
        'job_code',
        'project_name',
        'description',
        'quantity',
        'unit_id',
        'rate',
        'amount',
        'currency_id',
        'due_date',
        'status',
        'vendor_invoice_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'rate' => 'decimal:4',
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(VendorInvoice::class, 'vendor_invoice_id');
    }
}
