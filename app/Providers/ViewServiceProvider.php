<?php

namespace App\Providers;

use App\Country;
use App\Queries\UsersCountryQuery;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['auth.register', 'profile.edit'], function ($view): void {
            $view->with('countries', Country::forDropdown());
        });

        View::composer(['ranking.index', 'trade._user', 'herd.show'], function ($view): void {
            $usersQuery = new UsersCountryQuery()->fetchAll();
            $countryCodes = $usersQuery->unique('country_code')->pluck('country_code')->toArray();
            $view->with('countries', Country::forDropdown($countryCodes));
        });
    }
}
