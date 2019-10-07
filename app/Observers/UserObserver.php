<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user)
    {
        $username = $user->twitter ?: Str::slug($user->name);
        $count = 1;

        while(User::whereUsername($username)->exists()) {
            $username = $username . $count;
            $count++;
        }

        $user->username = $username;
    }
}
