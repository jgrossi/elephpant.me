<?php

declare(strict_types=1);

namespace App\Queries;

use App\User;
use Illuminate\Database\Eloquent\Collection;

final class UsersCountryQuery
{
    public function fetchAll(): Collection
    {
        $userQuery = User::query()->public();

        $visibleFields = ['users.country_code'];

        $users = $userQuery
            ->select($visibleFields)
            ->groupBy($visibleFields)
            ->get();

        return $users;
    }
}
