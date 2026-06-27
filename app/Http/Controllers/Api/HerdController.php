<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;

class HerdController extends Controller
{
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
            'username' => $user->username,
            'name'     => $user->name,
            'avatar'   => $user->avatar(),
            'country'  => $user->country_code,
            'twitter'  => $user->twitter,
            'mastodon' => $user->mastodon,
            'herd_url' => route('herds.show', $user->username),
            'stats'    => [
                'total'  => $total,
                'unique' => $unique,
                'spare'  => $total - $unique,
            ],
            'elephpants' => $elephpants,
        ]);
    }
}
