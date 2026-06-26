<?php

use App\Elephpant;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('adopt attaches elephpant when user has none', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();

    $user->adopt($elephpant, 2);

    expect($user->elephpants()->where('elephpants.id', $elephpant->id)->first()->pivot->quantity)->toBe(2);
});

test('adopt updates pivot when user already has elephpant', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $user->adopt($elephpant, 3);

    expect($user->elephpants()->where('elephpants.id', $elephpant->id)->first()->pivot->quantity)->toBe(3);
});

test('adopt detaches when quantity is zero', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $user->adopt($elephpant, 0);

    expect($user->elephpants()->where('elephpants.id', $elephpant->id)->exists())->toBeFalse();
});

test('elephpantsWithQuantity returns id to quantity map', function () {
    $user = User::factory()->create();
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $user->elephpants()->attach($e1->id, ['quantity' => 2]);
    $user->elephpants()->attach($e2->id, ['quantity' => 1]);

    $result = $user->elephpantsWithQuantity()->toArray();

    expect($result)->toHaveKeys([$e1->id, $e2->id])
        ->and($result[$e1->id])->toBe(2)
        ->and($result[$e2->id])->toBe(1);
});

test('scopePublic filters by is_public', function () {
    User::factory()->create(['is_public' => true]);
    User::factory()->create(['is_public' => false]);

    $public = User::public()->get();

    expect($public)->toHaveCount(1)
        ->and((bool) $public->first()->is_public)->toBeTrue();
});

test('generateUsername produces unique username', function () {
    $user = User::factory()->make(['name' => 'Test User', 'twitter' => null]);

    $username = User::generateUsername($user);

    expect($username)->toBeString()->not->toBeEmpty();
});

test('generateUsername appends number when username exists', function () {
    User::withoutEvents(function () {
        User::factory()->create(['username' => 'jane-doe', 'name' => 'Jane Doe']);
    });
    $user = User::factory()->make(['name' => 'Jane Doe', 'twitter' => null]);

    $username = User::generateUsername($user);

    expect($username)->not->toBe('jane-doe');
    expect($username)->toMatch('/^jane-doe\d+$/');
});

test('hasAvatarImage returns true when user has twitter', function () {
    $user = User::factory()->make(['twitter' => 'johndoe']);

    expect($user->hasAvatarImage())->toBeTrue();
});

test('avatar returns twitter microlink url when user has twitter', function () {
    $user = User::factory()->make(['twitter' => 'johndoe']);

    expect($user->avatar())->toContain('twitter.com/johndoe');
});

test('avatar returns ui-avatars fallback when no twitter and no gravatar', function () {
    \Creativeorange\Gravatar\Facades\Gravatar::shouldReceive('exists')->andReturn(false);
    $user = User::factory()->make(['twitter' => null, 'name' => 'Jane Doe']);

    expect($user->avatar())->toContain('ui-avatars.com');
});

test('avatar returns gravatar url when no twitter but gravatar exists', function () {
    \Creativeorange\Gravatar\Facades\Gravatar::shouldReceive('exists')->andReturn(true);
    \Creativeorange\Gravatar\Facades\Gravatar::shouldReceive('get')->andReturn('https://gravatar.com/avatar/xxx');
    $user = User::factory()->make(['twitter' => null]);

    expect($user->avatar())->toBe('https://gravatar.com/avatar/xxx');
});

test('scopeNotLoggedIn excludes current user', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();
    $this->actingAs($user);

    $result = User::notLoggedIn()->get();

    expect($result->pluck('id')->toArray())->not->toContain($user->id);
});
