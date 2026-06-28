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
            'featuredElephpantPool' => $this->featuredElephpantPool(),
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array{id: int, name: string, year: int<0, max>, imageUrl: string}>
     */
    private function featuredElephpantPool(): \Illuminate\Support\Collection
    {
        return Elephpant::query()
            ->whereNotNull('image')
            ->get(['id', 'name', 'image', 'year'])
            ->shuffle()
            ->map(fn (Elephpant $elephpant): array => [
                'id'       => $elephpant->id,
                'name'     => $elephpant->name,
                'year'     => (int) $elephpant->year,
                'imageUrl' => asset('storage/elephpants/'.$elephpant->image),
            ])
            ->values();
    }
}
