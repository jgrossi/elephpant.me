<?php

declare(strict_types=1);

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

test('api herd endpoint is forbidden for a private herd', function (): void {
    $user = User::factory()->create();
    $user->is_public = false;
    $user->save();

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertForbidden();
});
