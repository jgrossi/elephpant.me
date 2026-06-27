<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class AddUsernameField extends Command
{
    #[\Override]
    protected $signature = 'users:fix-username';

    #[\Override]
    protected $description = 'Fix users with missing username field';

    public function handle(): void
    {
        User::whereNull('username')->get()
            ->each(function (User $user): void {
                $user->username = User::generateUsername($user);
                $user->save();
            });
    }
}
