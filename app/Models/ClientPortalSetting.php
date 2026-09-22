<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPortalSetting extends Model
{
    protected $fillable = [
        'default_payment_method_id',
        'default_payment_terms',
        'allow_clients_create_projects',
        'default_client_manager_id',
        'notification_recipient_id',
    ];

    protected $casts = [
        'default_payment_terms' => 'integer',
        'allow_clients_create_projects' => 'boolean',
    ];

    /**
     * The single settings row, created with defaults if it does not exist yet.
     */
    public static function current(): self
    {
        return static::firstOrCreate([]);
    }

    public function defaultPaymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'default_payment_method_id');
    }

    public function defaultClientManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_client_manager_id');
    }

    public function notificationRecipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'notification_recipient_id');
    }
}
