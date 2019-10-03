<?php

namespace App\Http\Controllers;

use App\Elephpant;
use App\User;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $available = $request->user()->elephpantsToTrade;

        $users = User::notLoggedIn()
            ->whereHas('elephpantsToTrade', $elephpantsQuery = function ($query) use ($available) {
                $query->whereNotIn('id', $available->pluck('id')->toArray());
            })
            ->with([
                'elephpants',
                'elephpantsToTrade' => $elephpantsQuery,
            ])
            ->has('elephpantsToTrade')
            ->get();

        $users->each(function (User $user) use ($available) {
            $userElephpants = $user->elephpants->pluck('id')->toArray();
            $user->elephpants_interested = $available->filter(function (Elephpant $elephpant) use ($userElephpants) {
                return !in_array($elephpant->id, $userElephpants);
            })->toArray();
        });

        return view('match.index', compact('users'));
    }
}
