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
        $countries = $query->fetchAllPlucked()->toArray();
        $flags = $query->flags()->toArray();

        View::composer(['ranking.index', 'trade._user'], function ($view) use ($countries, $flags) {
            $view->with('countries', $countries);
            $view->with('flags', $flags);
        });
    }
}
