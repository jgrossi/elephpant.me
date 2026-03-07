<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $nbUsersWithElephpant = DB::table(DB::raw('(SELECT 1 FROM elephpant_user GROUP BY user_id) as distinct_users'))
            ->count();

        $nbUsers = DB::table('users')->count();

        return view('statistics.index', [
            'nbUsers' => $nbUsers,
            'nbUsersWithElephpant' => $nbUsersWithElephpant,
        ]);
    }
}
