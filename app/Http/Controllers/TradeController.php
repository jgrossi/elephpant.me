<?php

namespace App\Http\Controllers;

use App\Handlers\Commands\TradingUsersCommand;
use App\Handlers\TradingUsersHandler;
use App\User;

class TradeController extends Controller
{
    public function index(TradingUsersHandler $handler)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();

        $users = $handler->handle(
            new TradingUsersCommand($loggedUser)
        );

        return view('trade.index', compact('users'));
    }
}
