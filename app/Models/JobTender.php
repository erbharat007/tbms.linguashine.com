<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTender extends Model
{
    use HasFactory;

    protected $table = 'job_tenders';

    protected $fillable = [
        'code',
        'project_job_id',
        'type',
        'status',
        'started_at',
        'deadline_at',
        'ended_at',
        'participating_vendors_count',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'deadline_at' => 'datetime',
        'ended_at' => 'datetime',
        'participating_vendors_count' => 'integer',
    ];

    /**
     * Generate the next sequential tender code.
     */
    public static function generateCode(): string
    {
        $objectCode = ObjectCode::where('object_key', 'tender')->first();

        if ($objectCode) {
            $prefix = $objectCode->prefix ?? 'T';
            $nextId = $objectCode->next_id ?? 1;

            $objectCode->increment('next_id');

            return $prefix . $nextId;
        }

        $nextNum = (static::max('id') ?? 0) + 1;
        return 'T' . $nextNum;
    }

    /**
     * Associated project job.
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(ProjectJob::class, 'project_job_id');
    }

    /**
     * Creator user.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Human readable type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'first_claimer' => 'First claimer',
            'manager_selection_fixed' => 'Manager selection (fixed price)',
            'manager_selection_negotiable' => 'Manager selection (negotiable price)',
            default => ucfirst(str_replace('_', ' ', $this->type ?? 'First claimer')),
        };
    }

    /**
     * Status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'badge-secondary',
            'active' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'active' => 'Active',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status ?? 'Active')),
        };
    }
}
