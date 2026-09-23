<?php

namespace App\Http\Controllers\Api;

use App\Elephpant;
use App\Http\Controllers\Controller;
use App\Http\Resources\ElephpantResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Elephpants', 'The elePHPant species catalogue.')]
class ElephpantController extends Controller
{
    #[Endpoint(
        title: 'List all elephpants',
        description: 'Returns a paginated list of all elephpant species, ordered by year and name.',
    )]
    #[QueryParam('page', 'integer', 'Page number.', required: false, example: 1)]
    #[ResponseField('data[].owners', 'integer', 'Number of collectors that have at least one of this elePHPant in their herd.')]
    public function index(): AnonymousResourceCollection
    {
        $elephpants = Elephpant::withCount('users')
            ->orderBy('year')
            ->orderBy('name')
            ->paginate(20);

        return ElephpantResource::collection($elephpants);
    }

    #[Endpoint(title: 'Get a single elePHPant')]
    #[UrlParam('id', 'integer', 'elePHPant ID.', example: 1)]
    public function show(Elephpant $elephpant): ElephpantResource
    {
        $elephpant->loadCount('users');

        return new ElephpantResource($elephpant);
    }
}
