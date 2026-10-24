<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'client_contact_id',
        'specialization_id',
        'primary_manager_id',
        'secondary_manager_id',
        'description',
        'internal_note',
        'created_by',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientContact(): BelongsTo
    {
        return $this->belongsTo(ClientContact::class);
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

    public function languagePairs(): BelongsToMany
    {
        return $this->belongsToMany(LanguagePair::class, 'project_template_language_pair');
    }
}
