<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'company',
    'department',
    'category',
    'employment_type',
    'experience_level',
    'description',
    'responsibilities',
    'requirements',
    'skills',
    'salary_min',
    'salary_max',
    'pay_period',
    'show_salary_range',
    'work_mode',
    'location',
    'location_note',
    'application_method',
    'deadline',
    'status',
    'featured',
    'publish_to_marketplace',
    'notify_matching_candidates',
])]
class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected static function booted(): void
    {
        static::saving(function (Job $job) {
            if ($job->status === 'published' && ! $job->published_at) {
                $job->published_at = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'responsibilities' => 'array',
            'requirements' => 'array',
            'skills' => 'array',
            'salary_min' => 'integer',
            'salary_max' => 'integer',
            'show_salary_range' => 'boolean',
            'featured' => 'boolean',
            'publish_to_marketplace' => 'boolean',
            'notify_matching_candidates' => 'boolean',
            'deadline' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'job_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'job_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}
