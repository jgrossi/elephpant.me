<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();
        } else {
            $userElephpants = [];
        }

        $elephpants = DB::table('elephpants')
            ->select(DB::raw('COUNT(elephpant_user.elephpant_id) as nbElephpant, name, description, image, id, SUM(elephpant_user.quantity) as totalElephpant'))
            ->leftJoin('elephpant_user', 'elephpants.id', '=', 'elephpant_user.elephpant_id')
            ->orderBy('nbElephpant', 'desc')
            ->orderBy('elephpants.id', 'desc')
            ->orderBy('totalElephpant', 'desc')
            ->groupBy(['elephpants.id', 'elephpants.name', 'elephpants.description', 'elephpants.image'])
            ->get();

        $nbUsersWithElephpant = DB::table('elephpant_user')
            ->distinct('user_id')
            ->count();

        $nbUsers = DB::table('users')->count();

        return view('statistics.index', compact('elephpants', 'nbUsers', 'nbUsersWithElephpant', 'userElephpants'));
    }
}
