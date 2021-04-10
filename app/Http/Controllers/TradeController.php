<?php

namespace App\Http\Controllers;

use App\Queries\TradingUsersQuery;
use App\Queries\MessagesQuery;
use App\User;

class TradeController extends Controller
{
    public function index(TradingUsersQuery $query, MessagesQuery $mQuery)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAll($loggedUser);

        $this->feedMessages($users, $mQuery);

        return view('trade.index', compact('users'));
    }

    public function senders(int $elephpantId, TradingUsersQuery $query, MessagesQuery $mQuery)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllOnlyIfHeHasElephpant($loggedUser, $elephpantId);

        $this->feedMessages($users, $mQuery);
        return view('trade.index', compact('users'));
    }

    public function receivers(int $elephpantId, TradingUsersQuery $query, MessagesQuery $mQuery)
    {
        /** @var User $loggedUser */
        $loggedUser = auth()->user();
        $users = $query->fetchAllWhoLackElephpant($loggedUser, $elephpantId);
        $this->feedMessages($users, $mQuery);

        return view('trade.index', compact('users'));
    }

    private function feedMessages($users, MessagesQuery $mQuery)
    {
        foreach ($users as $user) {
            $user->messages = $mQuery->getMessagesWithLoggedInUserAndSomeoneElse($user->id);
        }
    }
}
