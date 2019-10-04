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
}
