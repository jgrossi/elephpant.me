<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use App\User;
use Illuminate\Database\Eloquent\Collection;

final class TradingUsersQuery
{
    public function fetchAll(User $user): Collection
    {
        $available = $user->elephpantsToTrade()->get();

        return $this
            ->fetchUsers($available)
            ->each(function (User $user) use ($available) {
                $this->addInterestedElephpants($user, $available);
            });
    }

    private function fetchUsers(Collection $available): Collection
    {
        $elephpantsQuery = function ($query) use ($available) {
            $query->whereNotIn('id', $available->pluck('id')->toArray());
        };;

        return User::query()
            ->with([
                'elephpants',
                'elephpantsToTrade' => $elephpantsQuery,
            ])
            ->whereHas('elephpantsToTrade', $elephpantsQuery)
            ->has('elephpantsToTrade')
            ->get();
    }

    private function addInterestedElephpants(User $user, Collection $available): void
    {
        $userElephpants = $user->elephpants->pluck('id')->toArray();

        $interests = $available
            ->filter(function (Elephpant $elephpant) use ($userElephpants) {
                return !in_array($elephpant->id, $userElephpants);
            });

        $user->elephpantsInterested = $interests;
    }
}
