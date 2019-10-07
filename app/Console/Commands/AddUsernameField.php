<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class AddUsernameField extends Command
{
    protected $signature = 'users:fix-username';
    protected $description = 'Fix users with missing username field';

    public function handle()
    {
        User::whereNull('username')->get()
            ->each(function (User $user) {
                $user->username = User::generateUsername($user);
                $user->save();
            });
    }
}
