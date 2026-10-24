<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobChecklist extends Model
{
    use HasFactory;

    protected $table = 'job_checklists';

    protected $fillable = [
        'name',
        'is_enabled',
        'note_for_vendor',
        'items',
        'updated_by',
        'created_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'items' => 'array',
    ];

    /**
     * Record creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Last updater user.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Jobs using this checklist.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(ProjectJob::class, 'checklist_id');
    }

    /**
     * Total number of checklist items.
     */
    public function getNumberOfItemsAttribute(): int
    {
        return is_array($this->items) ? count($this->items) : 0;
    }
}
