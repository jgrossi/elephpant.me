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
        $available = $user->elephpantsToTrade()->get();
        $users = $this->fetchUsers($available, $limit);

        foreach ($users as $user) {
            $this->addInterestedElephpants($user, $available);
        }

        return $users;
    }

    private function fetchUsers(Collection $available, int $limit)
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
            ->paginate($limit);
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
