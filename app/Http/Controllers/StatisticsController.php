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
            ->groupBy('elephpants.id')
            ->get();

        $nbUsers = User::query()->count();

        return view('statistics.index', compact('elephpants', 'nbUsers'));
    }
}
