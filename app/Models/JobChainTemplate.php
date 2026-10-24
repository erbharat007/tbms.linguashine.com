<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobChainTemplate extends Model
{
    use HasFactory;

    protected $table = 'job_chain_templates';

    protected $fillable = [
        'name',
        'description',
        'steps',
        'created_by',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    /**
     * Record creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the list of service IDs in the chain.
     */
    public function getServiceIdsAttribute(): array
    {
        if (empty($this->steps) || !is_array($this->steps)) {
            return [];
        }

        return array_map(function ($step) {
            return is_array($step) ? ($step['service_id'] ?? null) : $step;
        }, $this->steps);
    }
}
