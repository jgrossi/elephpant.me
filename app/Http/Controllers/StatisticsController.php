<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        $elephpants = DB::table('elephpants')
            ->select(DB::raw('COUNT(elephpant_user.elephpant_id) as nbElephpant, id, name, description, SUM(elephpant_user.quantity) as totalElephpant, image'))
            ->leftJoin('elephpant_user', 'elephpants.id', '=', 'elephpant_user.elephpant_id')
            ->orderBy('nbElephpant', 'desc')
            ->orderBy('elephpants.id', 'desc')
            ->orderBy('totalElephpant', 'desc')
            ->groupBy('elephpants.id')
            ->groupBy('elephpants.name')
            ->groupBy('elephpants.description')
            ->groupBy('elephpants.image')
            ->get();

        $nbUsersWithElephpant = DB::table('elephpant_user')
            ->distinct('user_id')
            ->count();

        $nbUsers = DB::table('users')->count();

        if (Auth::check()) {
            $currentUserElephpants = Auth::user()->elephpants->pluck('id');
        } else {
            $currentUserElephpants = collect();
        }

        return view('statistics.index', compact('elephpants', 'nbUsers', 'nbUsersWithElephpant', 'currentUserElephpants'));
    }
}
