<?php

namespace App\Http\Controllers;

use App\Queries\TradingUsersQuery;
use App\User;

class TradeController extends Controller
{
    public function index(TradingUsersQuery $query)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAll($loggedUser);

        return view('trade.index', compact('users'));
    }

    public function senders(int $elephpantId, TradingUsersQuery $query)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllOnlyIfHeHasElephpant($loggedUser, $elephpantId);

        return view('trade.index', compact('users'));
    }

    public function receivers(int $elephpantId, TradingUsersQuery $query)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllWhoLackElephpant($loggedUser, $elephpantId);

        return view('trade.index', compact('users'));
    }
}
