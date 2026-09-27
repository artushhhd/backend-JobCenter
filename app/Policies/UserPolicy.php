<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function delete(User $user, User $target): bool
    {
        if ($target->id === $user->id) {
            return false;
        }

        if ($user->roleRank() === User::RANK_SUPER_ADMIN) {
            return true;
        }

        return $user->roleRank() > $target->roleRank();
    }
}
