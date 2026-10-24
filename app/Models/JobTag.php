<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobTag extends Model
{
    use HasFactory;

    protected $table = 'job_tags';

    protected $fillable = [
        'name',
        'color',
        'description',
    ];

    /**
     * Jobs with this tag.
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(ProjectJob::class, 'project_job_job_tag', 'job_tag_id', 'project_job_id');
    }
}
