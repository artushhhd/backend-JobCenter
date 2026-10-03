<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'status', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const RANK_USER = 0;

    public const RANK_MODERATOR = 1;

    public const RANK_ADMIN = 2;

    public const RANK_SUPER_ADMIN = 3;

    public const ROLE_RANKS = [
        'user' => self::RANK_USER,
        'moderator' => self::RANK_MODERATOR,
        'admin' => self::RANK_ADMIN,
        'super_admin' => self::RANK_SUPER_ADMIN,
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    public function cv(): HasOne
    {
        return $this->hasOne(Cv::class, 'user_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            $user->cv?->delete();
        });
    }

    public function isRecruiter(): bool
    {
        return $this->status === 'job_poster';
    }

    public function roleRank(): int
    {
        return self::ROLE_RANKS[$this->role] ?? 0;
    }

    public function isStaff(): bool
    {
        return $this->roleRank() >= self::RANK_MODERATOR;
    }

    public function canModerateJobs(): bool
    {
        return $this->roleRank() >= self::RANK_MODERATOR;
    }

    public function canEditJobs(): bool
    {
        return $this->roleRank() >= self::RANK_ADMIN;
    }
}
