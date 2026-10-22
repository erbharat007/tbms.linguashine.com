<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    protected $fillable = [
        'project_deadline',
        'job_deadline',
        'client_invoice_due',
        'vendor_invoice_due',
        'quote_expiration',
        'project_deadline_days',
        'job_deadline_days',
        'client_invoice_days',
        'vendor_invoice_days',
        'quote_expiration_days',
    ];

    protected $casts = [
        'project_deadline' => 'boolean',
        'job_deadline' => 'boolean',
        'client_invoice_due' => 'boolean',
        'vendor_invoice_due' => 'boolean',
        'quote_expiration' => 'boolean',
        'project_deadline_days' => 'integer',
        'job_deadline_days' => 'integer',
        'client_invoice_days' => 'integer',
        'vendor_invoice_days' => 'integer',
        'quote_expiration_days' => 'integer',
    ];

    public static function current(): self
    {
        $setting = static::first();
        if (!$setting) {
            $setting = static::create([
                'project_deadline' => true,
                'job_deadline' => true,
                'client_invoice_due' => true,
                'vendor_invoice_due' => true,
                'quote_expiration' => true,
                'project_deadline_days' => 1,
                'job_deadline_days' => 1,
                'client_invoice_days' => 3,
                'vendor_invoice_days' => 3,
                'quote_expiration_days' => 2,
            ]);
        }
        return $setting;
    }
}
