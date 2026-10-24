<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'client_id',
        'client_contact_id',
        'legal_entity_id',
        'specialization_id',
        'primary_manager_id',
        'secondary_manager_id',
        'start_at',
        'deadline_at',
        'status',
        'description',
        'internal_note',
        'total_to_receive',
        'total_to_pay',
        'profit',
        'profit_margin',
        'currency_id',
        'created_by',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'deadline_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_to_receive' => 'decimal:2',
        'total_to_pay' => 'decimal:2',
        'profit' => 'decimal:2',
        'profit_margin' => 'decimal:2',
    ];

    /**
     * Generate the next sequential project code based on system object codes.
     */
    public static function generateCode(): string
    {
        $objectCode = ObjectCode::where('object_key', 'project')->first();

        if ($objectCode) {
            $prefix = $objectCode->prefix ?? 'P';
            $nextId = $objectCode->next_id ?? 1;

            // Increment next_id for future records
            $objectCode->increment('next_id');

            return $prefix . $nextId;
        }

        $nextNum = (static::withTrashed()->max('id') ?? 0) + 1;
        return 'P' . $nextNum;
    }

    /**
     * Recalculate profit and profit margin percentage.
     */
    public function recalculateFinances(): void
    {
        $this->profit = (float) $this->total_to_receive - (float) $this->total_to_pay;
        if ((float) $this->total_to_receive > 0) {
            $this->profit_margin = round(($this->profit / (float) $this->total_to_receive) * 100, 1);
        } else {
            $this->profit_margin = 0.00;
        }
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientContact(): BelongsTo
    {
        return $this->belongsTo(ClientContact::class);
    }

    public function legalEntity(): BelongsTo
    {
        return $this->belongsTo(LegalEntity::class);
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class);
    }

    public function primaryManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'primary_manager_id');
    }

    public function secondaryManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secondary_manager_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function languagePairs(): BelongsToMany
    {
        return $this->belongsToMany(LanguagePair::class, 'project_language_pair');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ProjectTag::class, 'project_project_tag');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(ProjectJob::class, 'project_id');
    }

    /**
     * Formatted human-readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'draft' => 'Draft',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * AdminLTE / Bootstrap badge class for status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'badge-info',
            'completed' => 'badge-success',
            'draft' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            default => 'badge-light border',
        };
    }
}
