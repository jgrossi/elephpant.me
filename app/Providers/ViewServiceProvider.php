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

        View::composer([
            'ranking.index', 'trade._user', 'auth.register', 'herd.show', 'profile.edit'
        ], function ($view) use ($countries) {
            $view->with('countries', $countries);
        });
    }
}
