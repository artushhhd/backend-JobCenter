<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id'])]
class Like extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (Like $like) {
            Job::query()->whereKey($like->job_id)->increment('likes_count');
        });

        static::deleted(function (Like $like) {
            Job::query()
                ->whereKey($like->job_id)
                ->where('likes_count', '>', 0)
                ->decrement('likes_count');
        });
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForJob(Builder $query, Job $job): Builder
    {
        return $query->where('job_id', $job->id);
    }

    public function scopeByUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }
}
