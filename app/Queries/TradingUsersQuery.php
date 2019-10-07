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
        $users = $this->fetchUsers($user, $limit);

        foreach ($users as $user) {
            $this->addInterestedElephpants($user);
        }

        return $users;
    }

    private function fetchUsers(User $user, int $limit)
    {
        $userElephpants = $user->elephpants->pluck('id')->toArray();

        $elephpantsQuery = function ($query) use ($userElephpants) {
            $query->whereNotIn('id', $userElephpants);
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

    private function addInterestedElephpants(User $user): void
    {
        $available = $user->elephpantsToTrade()->get();
        $userElephpants = $user->elephpants->pluck('id')->toArray();

        $interests = $available
            ->filter(function (Elephpant $elephpant) use ($userElephpants) {
                return !in_array($elephpant->id, $userElephpants);
            });

        $user->elephpantsInterested = $interests;
    }
}
