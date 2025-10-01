<?php

use App\User;

test('mastodon URL generates correctly with full handle', function () {
    $user = User::factory()->make([
        'mastodon' => '@user@example.com',
    ]);

    expect($user->mastodonUrl())->toBe('https://example.com/@user');
});

test('mastodon URL generates correctly with simple handle', function () {
    $user = User::factory()->make([
        'mastodon' => 'user',
    ]);

    expect($user->mastodonUrl())->toBe('https://mastodon.social/@user');
});

test('mastodon URL handles @ symbol correctly', function () {
    $user = User::factory()->make([
        'mastodon' => '@user',
    ]);

    expect($user->mastodonUrl())->toBe('https://mastodon.social/@user');
});

test('mastodon URL returns null when empty', function () {
    $user = User::factory()->make([
        'mastodon' => null,
    ]);

    expect($user->mastodonUrl())->toBeNull();
});

test('bluesky URL strips @ symbol correctly', function () {
    $user = User::factory()->make([
        'bluesky' => '@user.bsky.social',
    ]);

    expect($user->blueskyUrl())->toBe('https://bsky.app/profile/user.bsky.social');
});

test('bluesky URL works without @ symbol', function () {
    $user = User::factory()->make([
        'bluesky' => 'user.bsky.social',
    ]);

    expect($user->blueskyUrl())->toBe('https://bsky.app/profile/user.bsky.social');
});

test('bluesky URL returns null when empty', function () {
    $user = User::factory()->make([
        'bluesky' => null,
    ]);

    expect($user->blueskyUrl())->toBeNull();
});

test('avatar returns twitter URL when x_handle exists', function () {
    $user = User::factory()->make([
        'x_handle' => 'testuser',
        'email' => 'test@example.com',
    ]);

    expect($user->avatar())->toContain('twitter.com/testuser');
});

test('avatar falls back to ui-avatars when no x_handle', function () {
    $user = User::factory()->make([
        'x_handle' => null,
        'email' => 'nonexistent@example.com',
        'name' => 'Test User',
    ]);

    expect($user->avatar())->toContain('ui-avatars.com');
});

test('generates username from x_handle', function () {
    $user = User::factory()->make([
        'x_handle' => 'uniquehandle123',
    ]);

    $username = User::generateUsername($user);

    expect($username)->toBe('uniquehandle123');
});

test('generates username from name when no x_handle', function () {
    $user = User::factory()->make([
        'name' => 'Unique Test Person',
        'x_handle' => null,
    ]);

    $username = User::generateUsername($user);

    expect($username)->toBe('unique-test-person');
});

test('appends number to username when already exists', function () {
    User::factory()->create([
        'username' => 'existinguser',
    ]);

    $user = User::factory()->make([
        'name' => 'Existing User',
        'x_handle' => null,
    ]);

    $username = User::generateUsername($user);

    expect($username)->toBe('existinguser1');
});

test('user has elephpants with quantity', function () {
    $user = User::factory()->create();

    $collection = $user->elephpantsWithQuantity();

    expect($collection)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('scope not logged in excludes current user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $this->actingAs($user1);

    $users = User::notLoggedIn()->get();

    expect($users->pluck('id'))->not->toContain($user1->id)
        ->and($users->pluck('id'))->toContain($user2->id);
});

test('scope public filters public users only', function () {
    $publicUser = User::factory()->create(['is_public' => true]);
    $privateUser = User::factory()->create(['is_public' => false]);

    $users = User::public()->get();

    expect($users->pluck('id'))->toContain($publicUser->id)
        ->and($users->pluck('id'))->not->toContain($privateUser->id);
});
