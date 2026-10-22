<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseSetting extends Model
{
    protected $fillable = [
        'status',
        'type',
        'managers_limit',
        'api_access',
        'expires_at',
        'license_key',
    ];

    protected $casts = [
        'api_access' => 'boolean',
        'expires_at' => 'date',
    ];

    public static function current(): self
    {
        $setting = static::first();
        if (!$setting) {
            $setting = static::create([
                'status' => 'Active',
                'type' => 'Enterprise Unlimited',
                'managers_limit' => 'Unlimited',
                'api_access' => true,
                'expires_at' => now()->addYears(2),
                'license_key' => 'TBMS-PROTEMOS-ENT-' . strtoupper(substr(md5(config('app.key', 'secret')), 0, 16)),
            ]);
        }
        return $setting;
    }
}
