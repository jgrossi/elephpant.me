<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Support\Facades\DB;

final class TotalCollectorsQuery
{
    /**
     * Number of distinct users who have at least one elephpant in their herd.
     */
    public function count(): int
    {
        return DB::table(DB::raw('(SELECT 1 FROM elephpant_user GROUP BY user_id) as distinct_users'))
            ->count();
    }
}
