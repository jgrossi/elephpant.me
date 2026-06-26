<?php

namespace App\Http\Controllers\Api;

use App\Elephpant;
use App\Http\Controllers\Controller;
use App\Http\Resources\ElephpantResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ElephpantController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $elephpants = Elephpant::withCount('users')
            ->orderBy('year')
            ->orderBy('name')
            ->paginate(20);

        return ElephpantResource::collection($elephpants);
    }

    public function show(Elephpant $elephpant): ElephpantResource
    {
        $elephpant->loadCount('users');

        return new ElephpantResource($elephpant);
    }
}
