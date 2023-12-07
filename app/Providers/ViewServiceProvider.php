<?php

namespace App\Providers;

use App\Queries\CountriesQuery;
use App\Queries\UsersCountryQuery;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(['auth.register', 'profile.edit'], function ($view) {
            $countries = (new CountriesQuery())->fetchAll()->mapWithKeys(function ($country) {
                return [
                    $country['cca3'] => [
                        'name' => $country['name']['common'],
                        'flag' => $country['flag']['flag-icon'],
                    ]
                ];
            });

            $view->with('countries', $countries);
        });

        View::composer(['ranking.index', 'trade._user', 'herd.show'], function ($view) {
            $usersQuery = (new UsersCountryQuery)->fetchAll();
            $countries = (new CountriesQuery())->fetchAll()->filter(function ($country) use ($usersQuery) {
                return in_array($country['cca3'], $usersQuery->unique('country_code')->pluck('country_code')->toArray());
            })->mapWithKeys(function ($country) {
                return [
                    $country['cca3'] => [
                        'name' => $country['name']['common'],
                        'flag' => $country['flag']['flag-icon'],
                    ]
                ];
            });
            $view->with('countries', $countries);
        });
    }
}
