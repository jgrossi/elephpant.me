<?php

namespace App\Http\Controllers;

use App\Queries\TradingUsersQuery;
use App\Queries\ElephpantsQuery;
use App\User;
use Illuminate\Support\Facades\Auth;

class HerdController extends Controller
{
    public function edit(ElephpantsQuery $elephpantsQuery)
    {
        $elephpants = $elephpantsQuery->fetchAllOrderedAndGrouped();
        $userElephpants = Auth::user()->elephpantsWithQuantity()->toArray();

        $tradePossibilites = $this->prepareTradePossibilities($elephpants, $userElephpants);

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.edit', compact('elephpants', 'userElephpants', 'stats', 'tradePossibilites'));
    }

    private function prepareTradePossibilities($elephpants, $userElephpants)
    {
        $tradePossibilites = [];
        foreach ($elephpants as $year => $group) {
            foreach ($group as $elephpant) {
                if (($userElephpants[$elephpant->id] ?? 0) == 0 && strlen($elephpant->possible_senders) > 0) {
                    $tradePossibilites[$elephpant->id] = [
                        'type' => 'senders',
                        'count' => count(explode(',', $elephpant->possible_senders)),
                    ];
                } elseif (($userElephpants[$elephpant->id] ?? 0) && $userElephpants[$elephpant->id] > 1 && strlen($elephpant->possible_receivers) > 0) {
                    $tradePossibilites[$elephpant->id] = [
                        'type' => 'receivers',
                        'count' => count(explode(',', $elephpant->possible_receivers)),
                    ];
                }
            }
        }
        return $tradePossibilites;
    }


    public function show(string $username, TradingUsersQuery $query)
    {
        $user = User::whereUsername($username)->firstOrFail();
        $elephpants = $user->elephpants()->orderBy('year', 'desc')->orderBy('name', 'desc')->get();
        $userElephpants = $user->elephpantsWithQuantity()->toArray();

        $loggedUser = auth()->user();

        if ($loggedUser) {
            $possibleTrades = $query->fetchAllForUser($loggedUser, $user->id);
        } else {
            $possibleTrades = null;
        }

        $stats = [
            'unique' => $unique = count($userElephpants),
            'total' => $total = array_sum($userElephpants),
            'double' => $total - $unique,
        ];

        return view('herd.show', compact('user', 'elephpants', 'stats', 'possibleTrades'));
    }
}
