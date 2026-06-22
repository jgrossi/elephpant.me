<?php

declare(strict_types=1);

namespace App\Queries;

use App\User;
use Illuminate\Database\Eloquent\Collection;

final class RankedUsersQuery
{
    private const int LIMIT = 50;

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
            ->selectRaw('MAX(elephpant_user.updated_at) AS last_update')
            ->groupBy($visibleFields)
            ->orderBy('elephpants_unique', 'desc')
            ->orderBy('elephpants_total', 'desc')
            ->orderBy('users.name', 'asc')
            ->limit(self::LIMIT)
            ->get();

        $users->each(function (User $user): void {
            $user->last_update = $user->last_update
                ? \Illuminate\Support\Carbon::parse($user->last_update)
                : null;
        });

        return $users;
    }
}
