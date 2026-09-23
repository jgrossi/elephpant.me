<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RankedUserResource;
use App\Queries\RankedUsersQuery;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseField;

#[Group('Ranking', 'Collector leaderboards, globally or scoped to a country.')]
class RankingController extends Controller
{
    #[Endpoint(
        title: 'List the top collectors',
        description: <<<'DESC'
            Returns public herds ranked by number of unique elePHPants, then by total elePHPants,
            then alphabetically by name. `rank` is the position in the full ordering (not
            page-relative), and `updated_at` is an exact timestamp, not a rendered relative
            string, so it stays accurate however long you hold onto the response.
            DESC,
    )]
    #[QueryParam('country', 'string', 'ISO 3166-1 alpha-3 country code to filter the ranking by.', required: false, example: 'GBR')]
    #[QueryParam('page', 'integer', 'Page number.', required: false, example: 1)]
    #[ResponseField('data[].rank', 'integer', 'Position in the full ordering, not relative to the current page.')]
    #[ResponseField('data[].stats.spare', 'integer', 'Extra copies beyond one of each species held (total - unique).')]
    #[ResponseField('data[].updated_at', 'string', 'When this collector last updated their herd. Null if the herd is empty.')]
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
