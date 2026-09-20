<?php

namespace App\Http\Controllers\Api;

use App\Elephpant;
use App\Http\Controllers\Controller;
use App\Http\Resources\ElephpantResource;
use App\Queries\TotalCollectorsQuery;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ElephpantController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $elephpants = Elephpant::withCount('users')
            ->withSum('users as copies', 'elephpant_user.quantity')
            ->orderBy('year')
            ->orderBy('name')
            ->paginate(20);

        $this->setOwnershipPercentage($elephpants->getCollection());

        return ElephpantResource::collection($elephpants);
    }

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
