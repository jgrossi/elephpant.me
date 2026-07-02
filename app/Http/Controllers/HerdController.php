<?php

namespace App\Http\Controllers;

use App\Country;
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
            'user'            => $user,
            'stats'           => $stats,
            'possibleTrades'  => $possibleTrades,
            'herdLastUpdated' => $user->elephpants()->max('elephpant_user.updated_at'),
        ]);
    }

    public function compare(string $username0, string $username1, TradingUsersQuery $query): \Illuminate\Http\Response|\Illuminate\Contracts\View\View
    {
        if ($username0 === $username1) {
            return response('Cannot compare the same herd to itself', 400);
        }

        $elephpants = Elephpant::query()->orderBy('year')->get();

        $user0 = User::whereUsername($username0)->first();

        if (! $user0) {
            return response(sprintf('User %s not found', $username0), 404);
        }

        $user1 = User::whereUsername($username1)->first();

        if (! $user1) {
            return response(sprintf('User %s not found', $username1), 404);
        }

        if (! $user0->is_public && $user0->isNot(auth()->user())) {
            abort(403, $username0 . "'s herd is private.");
        }

        if (! $user1->is_public && $user1->isNot(auth()->user())) {
            abort(403, $username1 . "'s herd is private.");
        }

        return view('herd.compare', [
            'countries'  => Country::forDropdown([$user0->country_code, $user1->country_code]),
            'elephpants' => $elephpants,
            'user0'      => $user0,
            'user1'      => $user1,
        ]);
    }
}
