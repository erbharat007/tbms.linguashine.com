<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObjectCode extends Model
{
    protected $fillable = [
        'object_key',
        'label',
        'prefix',
        'next_id',
        'sort_order',
    ];

    /**
     * Whether this object gets a standalone sequence number.
     * Nested objects (Quote job, Job) are numbered within their parent.
     */
    public function hasSequence(): bool
    {
        return ! in_array($this->object_key, ['quote_job', 'job'], true);
    }

    /**
     * Preview of the next code that would be generated.
     */
    public function getNextCodePreviewAttribute(): string
    {
        return match ($this->object_key) {
            // Job numbered within its project: {prefix}{project}-{n}
            'job' => "{$this->prefix}-1",
            // Quote job numbered within its quote: {prefix}{quote}-{n}
            'quote_job' => "{$this->prefix}1-1",
            default => "{$this->prefix}" . ($this->next_id ?? 1),
        };
    }
}
