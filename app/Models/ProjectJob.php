<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project_jobs';

    protected $fillable = [
        'code',
        'name',
        'project_id',
        'service_id',
        'language_pair_id',
        'vendor_id',
        'vendor_contact_id',
        'status',
        'started_at',
        'deadline_at',
        'completed_at',
        'unit_id',
        'work_amount',
        'price_per_unit',
        'payables',
        'currency_id',
        'primary_manager_id',
        'checklist_id',
        'description',
        'instructions',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'deadline_at' => 'datetime',
        'completed_at' => 'datetime',
        'work_amount' => 'decimal:2',
        'price_per_unit' => 'decimal:4',
        'payables' => 'decimal:2',
    ];

    /**
     * Generate the next sequential job code based on system object codes.
     */
    public static function generateCode(): string
    {
        $objectCode = ObjectCode::where('object_key', 'job')->first();

        if ($objectCode) {
            $prefix = $objectCode->prefix ?? 'J';
            $nextId = $objectCode->next_id ?? 1;

            $objectCode->increment('next_id');

            return $prefix . $nextId;
        }

        $nextNum = (static::withTrashed()->max('id') ?? 0) + 1;
        return 'J' . $nextNum;
    }

    /**
     * Parent project.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Assigned service.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Assigned language pair.
     */
    public function languagePair(): BelongsTo
    {
        return $this->belongsTo(LanguagePair::class, 'language_pair_id');
    }

    /**
     * Assigned vendor.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    /**
     * Assigned vendor contact.
     */
    public function vendorContact(): BelongsTo
    {
        return $this->belongsTo(VendorContact::class, 'vendor_contact_id');
    }

    /**
     * Measurement unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * Financial currency.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Primary manager.
     */
    public function primaryManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'primary_manager_id');
    }

    /**
     * Assigned checklist.
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(JobChecklist::class, 'checklist_id');
    }

    /**
     * Record creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Tags assigned to this job.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(JobTag::class, 'project_job_job_tag', 'project_job_id', 'job_tag_id');
    }

    /**
     * Tender linked to this job if any.
     */
    public function tender(): HasOne
    {
        return $this->hasOne(JobTender::class, 'project_job_id');
    }

    /**
     * Returns the badge CSS class for this status.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'badge-secondary',
            'offered' => 'badge-warning',
            'in_progress' => 'badge-info',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Returns human readable status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'offered' => 'Offered',
            'in_progress' => 'In progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst(str_replace('_', ' ', $this->status ?? 'Draft')),
        };
    }
}
