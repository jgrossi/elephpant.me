<?php

namespace App\Http\Controllers;

use App\Queries\TradingUsersQuery;
use App\User;

class HerdController extends Controller
{
    public function edit(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('herd.edit');
    }

    public function show(string $username, TradingUsersQuery $query): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = User::with('elephpants')->whereUsername($username)->firstOrFail();
        $userElephpants = $user->elephpantsWithQuantity()->toArray();

        $loggedUser = auth()->user();
        $possibleTrades = $loggedUser ? $query->fetchAllForUser($loggedUser, $user->id) : null;

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total'  => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.show', [
            'user'           => $user,
            'stats'          => $stats,
            'possibleTrades' => $possibleTrades,
        ]);
    }
}
