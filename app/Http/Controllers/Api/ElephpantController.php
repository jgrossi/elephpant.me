<?php

namespace App\Http\Controllers\Api;

use App\Elephpant;
use App\Http\Controllers\Controller;
use App\Http\Resources\ElephpantResource;
use App\Queries\TotalCollectorsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Elephpants', 'The elePHPant species catalogue and per-species ownership stats.')]
class ElephpantController extends Controller
{
    private const int DEFAULT_PER_PAGE = 20;

    private const int MAX_PER_PAGE = 100;

    #[Endpoint(
        title: 'List all elephpants',
        description: 'Returns a paginated list of all elephpant species, ordered by year and name.',
    )]
    #[QueryParam('page', 'integer', 'Page number.', required: false, example: 1)]
    #[QueryParam('per_page', 'integer', 'Species per page, 1 to 100. Defaults to 20, so the whole catalogue fits in one request at 100.', required: false, example: 'No-example')]
    #[ResponseField('data[].owners', 'integer', 'Number of collectors that have at least one of this elePHPant in their herd.')]
    #[ResponseField('data[].copies', 'integer', 'Every copy held across all herds, including spares (SUM of quantity, not distinct owners).')]
    #[ResponseField('data[].ownership_percentage', 'number', 'Percentage of all collectors who own at least one of this elePHPant, rounded to 2 decimals.')]
    #[ResponseField('data[].updated_at', 'string', "When this species' catalogue entry (name, description, image) last changed.")]
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = min(max($request->integer('per_page', self::DEFAULT_PER_PAGE), 1), self::MAX_PER_PAGE);

        $elephpants = Elephpant::withCount('users')
            ->withSum('users as copies', 'elephpant_user.quantity')
            ->orderBy('year')
            ->orderBy('name')
            ->paginate($perPage);

        $this->setOwnershipPercentage($elephpants->getCollection());

        return ElephpantResource::collection($elephpants);
    }

    #[Endpoint(title: 'Get a single elePHPant')]
    #[UrlParam('id', 'integer', 'elePHPant ID.', example: 1)]
    public function show(Elephpant $elephpant): ElephpantResource
    {
        $elephpant->loadCount('users');
        $elephpant->loadSum('users as copies', 'elephpant_user.quantity');

        $this->setOwnershipPercentage(collect([$elephpant]));

        return new ElephpantResource($elephpant);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Elephpant>  $elephpants
     */
    private function setOwnershipPercentage($elephpants): void
    {
        $totalCollectors = app(TotalCollectorsQuery::class)->count();

        $elephpants->each(function (Elephpant $elephpant) use ($totalCollectors): void {
            $elephpant->ownership_percentage = $totalCollectors > 0
                ? round(($elephpant->users_count / $totalCollectors) * 100, 2)
                : 0.0;
        });
    }
}
