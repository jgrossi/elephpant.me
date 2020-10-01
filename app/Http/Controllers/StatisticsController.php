<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $elephpants = DB::table('elephpants')
            ->select(DB::raw('COUNT(elephpant_user.elephpant_id) as nbElephpant, name, description, SUM(elephpant_user.quantity) as totalElephpant'))
            ->leftJoin('elephpant_user', 'elephpants.id', '=', 'elephpant_user.elephpant_id')
            ->orderBy('nbElephpant', 'desc')
            ->orderBy('elephpants.id', 'desc')
            ->orderBy('totalElephpant', 'desc')
            ->groupBy('elephpants.id')
            ->get();

        $nbUsersWithElephpant = DB::table('elephpant_user')
            ->distinct('user_id')
            ->count();

        $nbUsers = DB::table('users')->count();

        return view('statistics.index', compact('elephpants', 'nbUsers', 'nbUsersWithElephpant'));
    }
}
