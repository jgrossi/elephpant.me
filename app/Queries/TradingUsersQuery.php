<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use App\User;
use Illuminate\Database\Eloquent\Collection;

final class TradingUsersQuery
{
    public function fetchAll(User $user, int $limit = 5)
    {
        $userElephpants = $user->elephpants;

        $userAvailable = $userElephpants->filter(function (Elephpant $elephpant) {
            return $elephpant->pivot->quantity > 1;
        });

        if ($userAvailable->count() === 0) {
            return null;
        }

        $traders = $this->fetchTraders($userElephpants, $limit);

        foreach ($traders as $trader) {
            $this->addInterestedElephpants($trader, $userAvailable);
        }

        return $traders;
    }

    private function fetchTraders(Collection $userElephpants, int $limit)
    {
        $userElephpants = $userElephpants->pluck('id');

        $elephpantsQuery = function ($query) use ($userElephpants) {
            $query->whereNotIn('id', $userElephpants);
        };

        return User::query()
            ->with([
                'elephpants',
                'elephpantsToTrade' => $elephpantsQuery,
            ])
            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->paginate($limit);
    }

    private function addInterestedElephpants(User $trader, Collection $userAvailable): void
    {
        $traderElephpants = $trader->elephpants->pluck('id')->toArray();

        $interests = $userAvailable->filter(function (Elephpant $elephpant) use ($traderElephpants) {
            return !in_array($elephpant->id, $traderElephpants);
        });

        $trader->elephpantsInterested = $interests;
    }
}
