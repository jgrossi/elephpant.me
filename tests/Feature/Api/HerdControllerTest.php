<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('api herd endpoint exposes x_handle and bluesky keys', function (): void {
    $user = User::factory()->create([
        'x_handle' => 'john',
        'mastodon' => '@john@phpc.social',
        'bluesky'  => '@john.bsky.social',
    ]);
    $user->update(['is_public' => true]);

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertOk();
    $response->assertJson([
        'x_handle' => 'john',
        'mastodon' => '@john@phpc.social',
        'bluesky'  => '@john.bsky.social',
    ]);
    $response->assertJsonMissing(['twitter' => 'john']);
});

test('api herd endpoint exposes updated_at based on the most recent elephpant update', function (): void {
    $user = User::factory()->create();
    $user->update(['is_public' => true]);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $lastUpdate = $user->elephpants()->max('elephpant_user.updated_at');

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertOk();
    $response->assertJsonPath('updated_at', \Illuminate\Support\Carbon::parse($lastUpdate)->toIso8601String());
});

test('api herd endpoint returns null updated_at when the herd is empty', function (): void {
    $user = User::factory()->create();
    $user->update(['is_public' => true]);

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertOk();
    $response->assertJsonPath('updated_at', null);
});

test('api herd endpoint is forbidden for a private herd', function (): void {
    $user = User::factory()->create();
    $user->is_public = false;
    $user->save();

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertForbidden();
});
