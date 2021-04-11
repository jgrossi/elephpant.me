<?php

namespace App\Providers;

use App\Queries\CountriesQuery;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $query = $this->app->make(CountriesQuery::class);

        $countries = $query->fetchAll()
            ->mapWithKeys(function ($country) {
                return [
                    $country['cca3'] => [
                        'name' => $country['name']['common'],
                        'flag' => $country['flag']['flag-icon'],
                    ]
                ];
            });

        $usedCountries = $query->filterOnlyUsedCountries($countries);

        View::composer([
            'ranking.index', 'trade._user', 'auth.register', 'herd.show', 'profile.edit'
        ], function ($view) use ($countries, $usedCountries) {
            $view->with('countries', $countries);
            $view->with('usedCountries', $usedCountries);
        });
    }
}
