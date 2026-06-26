<?php

namespace App\Http\Controllers;

use App\Elephpant;
use App\ElephpantUser;
use App\User;

class HomeController extends Controller
{
    public const CATALOG_PREVIEW_LIMIT = 12;

    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('home', [
            'speciesCount'        => Elephpant::count(),
            'collectorCount'      => User::query()->whereHas('elephpants')->count(),
            'collectedTotal'      => (int) ElephpantUser::query()->sum('quantity'),
            'catalogPreviewLimit' => self::CATALOG_PREVIEW_LIMIT,
            'latestElephpants'    => Elephpant::query()
                ->orderByDesc('year')
                ->orderByDesc('id')
                ->limit(self::CATALOG_PREVIEW_LIMIT)
                ->get(),
            'featuredElephpants'  => Elephpant::query()
                ->whereNotNull('image')
                ->inRandomOrder()
                ->limit(4)
                ->get(['id', 'name', 'image', 'year']),
        ]);
    }
}
