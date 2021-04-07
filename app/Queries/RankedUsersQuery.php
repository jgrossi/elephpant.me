<?php

declare(strict_types=1);

namespace App\Queries;

use App\User;
use Illuminate\Database\Eloquent\Collection;

final class RankedUsersQuery
{
    private const LIMIT = 50;

    public function fetchAll(?string $country): Collection
    {
        $userQuery = User::query()->public();

        if ($country) {
            $userQuery->where('country_code', $country);
        }

        $visibleFields = ['users.id', 'users.name', 'users.username', 'users.twitter', 'users.country_code'];

        $users = $userQuery
            ->join('elephpant_user', 'users.id', '=', 'elephpant_user.user_id')
            ->select($visibleFields)
            ->selectRaw('SUM(elephpant_user.quantity) AS elephpants_total')
            ->selectRaw('COUNT(DISTINCT elephpant_user.elephpant_id) AS elephpants_unique')
            ->groupBy($visibleFields)
            ->orderBy('elephpants_unique', 'desc')
            ->orderBy('elephpants_total', 'desc')
            ->orderBy('users.name', 'asc')
            ->limit(static::LIMIT)
            ->get();

        foreach ($users as $user) {
            $user->last_update = $user->elephpants
                ->sortByDesc(function ($elephpant) {
                    return $elephpant->pivot->updated_at->getTimestamp();
                })
                ->first()
                ->pivot
                ->updated_at;
        }

        return $users;
    }
}
