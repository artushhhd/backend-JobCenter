<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Job $job): bool
    {
        return $user->isStaff()
            || $job->status === 'published'
            || $job->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function manage(User $user): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Job $job): bool
    {
        return $user->canEditJobs()
            || ($user->isRecruiter() && $job->user_id === $user->id);
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->canModerateJobs() || $job->user_id === $user->id;
    }

    public function like(User $user, Job $job): bool
    {
        return $job->status === 'published' || $job->user_id === $user->id;
    }
}
