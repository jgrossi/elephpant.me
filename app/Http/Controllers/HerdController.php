<?php

namespace App\Http\Controllers;

use App\Queries\ElephpantsQuery;
use App\User;
use Illuminate\Support\Facades\Auth;

class HerdController extends Controller
{
    public function edit(ElephpantsQuery $elephpantsQuery)
    {
        $elephpants = $elephpantsQuery->fetchAllOrderedAndGrouped();
        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.edit', compact('elephpants', 'userElephpants', 'stats'));
    }

    public function show(string $username)
    {
        $user = User::whereUsername($username)->firstOrFail();
        $elephpants = $user->elephpants()->orderBy('year', 'desc')->get();
        $userElephpants = $user->elephpantsWithQuantity()->toArray();

        $fullName = explode(' ',$user->name);
        $initials = substr($fullName[0],0,1);
        if(isset($fullName[1]))
        {
            $initials.=substr($fullName[1],0,1);
        }

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.show', compact('user', 'elephpants', 'stats', 'initials'));
    }
}
