<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'type',
        'note',
        'details',
        'is_enabled',
        'is_default',
    ];

    protected $casts = [
        'details' => 'array',
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
    ];

    /**
     * Available payment method types (key => label).
     *
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            'bank_transfer' => 'Bank transfer',
            'paypal' => 'PayPal',
            'skrill' => 'Skrill',
            'custom' => 'Custom',
        ];
    }

    /**
     * Detail field definitions per type (field key => label).
     * The controller/form/validation use this as the source of truth.
     *
     * @return array<string, array<string, string>>
     */
    public static function detailFields(): array
    {
        return [
            'bank_transfer' => [
                'bank_name' => 'Bank name',
                'bank_address' => 'Bank address',
                'swift' => 'SWIFT',
                'iban' => 'IBAN',
                'sort_code' => 'Sort code',
                'account_number' => 'Account number',
                'account_currency' => 'Account currency',
                'beneficiary_name' => 'Account beneficiary name',
                'beneficiary_address' => 'Account beneficiary address',
            ],
            'paypal' => [
                'email' => 'Email',
            ],
            'skrill' => [
                'email' => 'Email',
            ],
            'custom' => [
                'custom_type' => 'Custom type',
                'details' => 'Details',
            ],
        ];
    }

    /**
     * Human-readable type label, e.g. "Custom (Cash)".
     */
    public function getTypeLabelAttribute(): string
    {
        $label = self::types()[$this->type] ?? ucfirst($this->type);

        if ($this->type === 'custom' && ! empty($this->details['custom_type'])) {
            return "{$label} ({$this->details['custom_type']})";
        }

        return $label;
    }

    /**
     * Short summary of the type-specific details for the list "Details" column.
     */
    public function getDetailsSummaryAttribute(): string
    {
        return match ($this->type) {
            'bank_transfer' => $this->details['bank_name'] ?? '',
            'paypal', 'skrill' => $this->details['email'] ?? '',
            'custom' => $this->details['details'] ?? '',
            default => '',
        };
    }

    /**
     * Make this method the single default (used in client portal settings).
     */
    public function makeDefault(): void
    {
        DB::transaction(function () {
            static::where('is_default', true)->update(['is_default' => false]);
            $this->update(['is_default' => true, 'is_enabled' => true]);
        });
    }
}
