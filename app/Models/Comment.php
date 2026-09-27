<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['body'])]
class Comment extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (Comment $comment) {
            Job::query()->whereKey($comment->job_id)->increment('comments_count');
        });

        static::deleted(function (Comment $comment) {
            Job::query()
                ->whereKey($comment->job_id)
                ->where('comments_count', '>', 0)
                ->decrement('comments_count');
        });
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForJob(Builder $query, Job $job): Builder
    {
        return $query->where('job_id', $job->id);
    }
}
