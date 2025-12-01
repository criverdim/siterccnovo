<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewPastoreio(?User $user): bool
    {
        return $user && ($user->status === 'active') && in_array($user->role, ['servo','admin'], true);
    }
}

