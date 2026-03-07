<?php

namespace App\Providers;

use App\ElephpantUser;
use App\Observers\ElephpantUserObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
        ElephpantUser::observe(ElephpantUserObserver::class);
    }
}
