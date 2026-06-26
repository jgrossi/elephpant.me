<?php

namespace App\Http\Controllers;

use App\Elephpant;
use App\Queries\TradingUsersQuery;
use App\User;

class HerdController extends Controller
{
    public function edit(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $userElephpants = auth()->user()->elephpantsWithQuantity()->toArray();
        $unique = count($userElephpants);
        $total = array_sum($userElephpants);

        return view('herd.edit', [
            'userElephpants' => $userElephpants,
            'herdStats'      => [
                'unique' => $unique,
                'total'  => $total,
                'double' => $total - $unique,
            ],
            'totalSpecies' => Elephpant::count(),
        ]);
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
