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

        $users = $userQuery
            ->with('elephpants')
            ->withCount('elephpants as elephpants_unique')
            ->join('elephpant_user', 'users.id', '=', 'elephpant_user.user_id')
            ->selectRaw('users.*, SUM(elephpant_user.quantity) as elephpants_total')
            ->groupBy('users.id')
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
