<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RankedUserResource;
use App\Queries\RankedUsersQuery;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RankingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $country = $request->string('country')->trim()->value() ?: null;

        $paginator = app(RankedUsersQuery::class)->paginate($country);

        $firstRank = $paginator->firstItem() ?? 0;

        collect($paginator->items())
            ->values()
            ->each(function (User $user, int $index) use ($firstRank): void {
                $user->rank = $firstRank + $index;
            });

        return RankedUserResource::collection($paginator);
    }
}
