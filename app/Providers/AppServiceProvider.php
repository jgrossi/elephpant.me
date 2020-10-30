<?php

namespace App\Providers;

use App\ElephpantUser;
use App\Observers\ElephpantUserObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        User::observe(UserObserver::class);
        ElephpantUser::observe(ElephpantUserObserver::class);
    }
}
