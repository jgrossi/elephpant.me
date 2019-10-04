<?php

namespace App\Http\Controllers;

use App\Queries\RankedUsersQuery;

class RankingController extends Controller
{
    public function index(RankedUsersQuery $usersQuery)
    {
        $country = request('country');
        $users = $usersQuery->fetchAll($country);

        return view('ranking.index', compact('users', 'country'));
    }
}
