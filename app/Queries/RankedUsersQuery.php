<?php

declare(strict_types=1);

namespace App\Queries;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

final class RankedUsersQuery
{
    private const int LIMIT = 50;

    /**
     * @return Collection<int, User>
     */
    public function fetchAll(?string $country): Collection
    {
        $users = $this->baseQuery($country)
            ->limit(self::LIMIT)
            ->get();

        $this->parseLastUpdate($users);

        return $users;
    }

    public function paginate(?string $country, int $perPage = self::LIMIT): LengthAwarePaginator
    {
        $paginator = $this->baseQuery($country)->paginate($perPage);

        $this->parseLastUpdate($paginator->getCollection());

        return $paginator;
    }

    /**
     * @return Builder<User>
     */
    private function baseQuery(?string $country): Builder
    {
        $userQuery = User::query()->public();

        if ($country) {
            $userQuery->where('country_code', $country);
        }

        $visibleFields = ['users.id', 'users.name', 'users.username', 'users.x_handle', 'users.country_code'];

        return $userQuery
            ->join('elephpant_user', 'users.id', '=', 'elephpant_user.user_id')
            ->select($visibleFields)
            ->selectRaw('SUM(elephpant_user.quantity) AS elephpants_total')
            ->selectRaw('COUNT(DISTINCT elephpant_user.elephpant_id) AS elephpants_unique')
            ->selectRaw('MAX(elephpant_user.updated_at) AS last_update')
            ->groupBy($visibleFields)
            ->orderBy('elephpants_unique', 'desc')
            ->orderBy('elephpants_total', 'desc')
            ->orderBy('users.name', 'asc');
    }

    /**
     * @param  Collection<int, User>  $users
     */
    private function parseLastUpdate(Collection $users): void
    {
        $users->each(function (User $user): void {
            $user->last_update = $user->last_update
                ? Carbon::parse($user->last_update)
                : null;
        });
    }
}
