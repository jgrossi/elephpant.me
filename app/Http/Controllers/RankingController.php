<?php

namespace App\Http\Controllers;

use App\Queries\CountriesQuery;
use App\Queries\RankedUsersQuery;

class RankingController extends Controller
{
    public function index(RankedUsersQuery $usersQuery, CountriesQuery $countriesQuery)
    {
        $country = request('country');
        $users = $usersQuery->fetchAll($country);
        $countries = $countriesQuery->fetchAllPlucked();

        return view('ranking.index', compact('users', 'countries', 'country'));
    }
}
