<?php

declare(strict_types=1);

namespace App\Queries;

use App\Elephpant;
use Illuminate\Database\Eloquent\Collection;

final class ElephpantsQuery
{
    public function fetchAllOrderedAndGrouped(): Collection
    {
        return Elephpant::query()
            ->orderBy('year')
            ->orderBy('name')
            ->get()
            ->groupBy('year');
    }
}
