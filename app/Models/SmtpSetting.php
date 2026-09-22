<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpSetting extends Model
{
    protected $fillable = [
        'host',
        'port',
        'encryption',
        'authentication',
        'username',
        'password',
        'from_email',
    ];

    protected $casts = [
        'password' => 'encrypted', // never stored in plaintext
    ];

    /**
     * The single SMTP configuration row, or null if none is configured.
     */
    public static function current(): ?self
    {
        return static::query()->first();
    }

    /**
     * Human-readable encryption label.
     */
    public function getEncryptionLabelAttribute(): string
    {
        return match ($this->encryption) {
            'starttls' => 'STARTTLS',
            'tls' => 'TLS/SSL',
            default => 'None',
        };
    }
}
