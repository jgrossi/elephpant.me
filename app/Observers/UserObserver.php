<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function creating(User $user): void
    {
        if (blank($user->username)) {
            $user->username = User::generateUsername($user);
        }
    }
}
