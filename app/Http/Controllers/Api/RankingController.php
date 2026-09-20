<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Queries\RankedUsersQuery;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $country = $request->string('country')->trim()->value() ?: null;

        $users = app(RankedUsersQuery::class)->fetchAll($country);

        $ranking = $users->values()->map(fn (User $user, int $index): array => [
            'rank'              => $index + 1,
            'username'          => $user->username,
            'name'              => $user->name,
            'country'           => $user->country_code,
            'elephpants_total'  => (int) $user->elephpants_total,
            'elephpants_unique' => (int) $user->elephpants_unique,
            'last_update'       => $user->last_update?->toIso8601String(),
            'herd_url'          => route('herds.show', $user->username),
        ])->values();

        return response()->json([
            'country' => $country,
            'ranking' => $ranking,
        ]);
    }
}
