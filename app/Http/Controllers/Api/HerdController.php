<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseField;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Herds', "A single collector's herd of elePHPants.")]
class HerdController extends Controller
{
    #[Endpoint(
        title: "Get a collector's herd",
        description: 'Returns the full herd of a registered collector, including statistics and collected elePHPants. 403s if the herd is private.',
    )]
    #[UrlParam('username', 'string', 'Collector username.', example: 'john')]
    #[ResponseField('country', 'string', 'ISO 3166-1 alpha-3 country code.')]
    #[ResponseField('updated_at', 'string', 'When the collector last updated their herd (add, remove or change a quantity). Null if the herd is empty.')]
    #[ResponseField('stats.total', 'integer', 'Total elePHPants held, including spares.')]
    #[ResponseField('stats.unique', 'integer', 'Distinct elePHPant species held.')]
    #[ResponseField('stats.spare', 'integer', 'Extra copies beyond one of each species held (total - unique).')]
    #[ResponseField('elephpants[].quantity', 'integer', 'How many of this elePHPant the collector owns.')]
    public function show(string $username): JsonResponse
    {
        $user = User::with('elephpants')
            ->whereUsername($username)
            ->firstOrFail();

        if (! $user->is_public) {
            abort(403, 'This herd is private.');
        }

        $elephpantsWithQuantity = $user->elephpantsWithQuantity()->toArray();
        $unique = count($elephpantsWithQuantity);
        $total = array_sum($elephpantsWithQuantity);
        $lastUpdate = $user->elephpants()->max('elephpant_user.updated_at');
        $updatedAt = $lastUpdate ? Carbon::parse($lastUpdate)->toIso8601String() : null;

        $elephpants = $user->elephpants
            ->sortBy('year')
            ->map(fn ($elephpant): array => [
                'id'          => $elephpant->id,
                'name'        => $elephpant->name,
                'description' => $elephpant->description,
                'sponsor'     => $elephpant->sponsor,
                'year'        => $elephpant->year,
                'image_url'   => $elephpant->image ? asset('storage/elephpants/'.$elephpant->image) : null,
                'quantity'    => $elephpant->pivot->quantity,
            ])
            ->values();

        return response()->json([
            'username'   => $user->username,
            'name'       => $user->name,
            'avatar'     => $user->avatar(),
            'country'    => $user->country_code,
            'x_handle'   => $user->x_handle,
            'mastodon'   => $user->mastodon,
            'bluesky'    => $user->bluesky,
            'herd_url'   => route('herds.show', $user->username),
            'updated_at' => $updatedAt,
            'stats'      => [
                'total'  => $total,
                'unique' => $unique,
                'spare'  => $total - $unique,
            ],
            'elephpants' => $elephpants,
        ]);
    }
}
