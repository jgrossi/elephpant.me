<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    public function creating(User $user): void
    {
        $user->username = User::generateUsername($user);
    }
}
