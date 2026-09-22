<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_locked',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
    ];

    /**
     * The permissions granted to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Users that belong to this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Determine whether this role grants the given permission key.
     * Locked roles (the primary Administrator) always have full access.
     */
    public function hasPermission(string $key): bool
    {
        if ($this->is_locked) {
            return true;
        }

        return $this->permissions->contains('key', $key);
    }
}
