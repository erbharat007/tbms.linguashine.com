<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class VendorPortalSetting extends Model
{
    protected $fillable = [
        'default_payment_terms',
        'allow_vendors_create_invoices',
        'invoice_notification_recipient_id',
        'application_form_enabled',
        'application_token',
        'application_notification_recipient_id',
        'agreement_text',
        'agreement_updated_at',
        'agreement_updated_by',
    ];

    protected $casts = [
        'default_payment_terms' => 'integer',
        'allow_vendors_create_invoices' => 'boolean',
        'application_form_enabled' => 'boolean',
        'agreement_updated_at' => 'datetime',
    ];

    /**
     * The single settings row, created with a fresh token if it does not exist.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'application_token' => static::newToken(),
        ]);
    }

    /**
     * Generate a new random token for the public application URL.
     */
    public static function newToken(): string
    {
        return Str::random(40);
    }

    /**
     * Regenerate the application token (invalidates the old public URL).
     */
    public function regenerateToken(): void
    {
        $this->update(['application_token' => static::newToken()]);
    }

    /**
     * The public vendor application form URL.
     */
    public function getApplicationUrlAttribute(): string
    {
        return route('vendor-app.create', ['token' => $this->application_token]);
    }

    /**
     * Whether a vendor agreement has been set.
     */
    public function hasAgreement(): bool
    {
        return filled($this->agreement_text);
    }

    /**
     * The public vendor agreement view URL (reuses the application token).
     */
    public function getAgreementUrlAttribute(): string
    {
        return route('vendor-agreement.view', ['token' => $this->application_token]);
    }

    /**
     * Save the agreement text and stamp who/when.
     */
    public function saveAgreement(string $text, ?int $userId): void
    {
        $this->update([
            'agreement_text' => $text,
            'agreement_updated_at' => now(),
            'agreement_updated_by' => $userId,
        ]);
    }

    /**
     * Remove the agreement.
     */
    public function clearAgreement(): void
    {
        $this->update([
            'agreement_text' => null,
            'agreement_updated_at' => null,
            'agreement_updated_by' => null,
        ]);
    }

    public function agreementUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreement_updated_by');
    }

    public function invoiceNotificationRecipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invoice_notification_recipient_id');
    }

    public function applicationNotificationRecipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'application_notification_recipient_id');
    }
}
