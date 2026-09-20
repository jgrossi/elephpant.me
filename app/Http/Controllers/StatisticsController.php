<?php

namespace App\Http\Controllers;

use App\Queries\TotalCollectorsQuery;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $nbUsersWithElephpant = app(TotalCollectorsQuery::class)->count();

        $nbUsers = DB::table('users')->count();

        return view('statistics.index', [
            'nbUsers'              => $nbUsers,
            'nbUsersWithElephpant' => $nbUsersWithElephpant,
        ]);
    }
}
