<?php

declare(strict_types=1);

use App\User;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();
});

test('gravatar existence is only checked once per user instance', function (): void {
    Gravatar::shouldReceive('exists')->once()->andReturn(true);
    Gravatar::shouldReceive('get')->twice()->andReturn('https://gravatar.com/avatar/xxx');

    $user = User::factory()->make(['x_handle' => null, 'email' => 'once@example.com']);

    expect($user->hasAvatarImage())->toBeTrue()
        ->and($user->avatar())->toBe('https://gravatar.com/avatar/xxx')
        ->and($user->avatar())->toBe('https://gravatar.com/avatar/xxx');
});

test('gravatar existence is cached across user instances', function (): void {
    Gravatar::shouldReceive('exists')->once()->andReturn(false);

    $first = User::factory()->make(['x_handle' => null, 'email' => 'cached@example.com', 'name' => 'Cached User']);
    $second = User::factory()->make(['x_handle' => null, 'email' => 'cached@example.com', 'name' => 'Cached User']);

    expect($first->hasAvatarImage())->toBeFalse()
        ->and($second->hasAvatarImage())->toBeFalse()
        ->and($second->avatar())->toContain('ui-avatars.com');
});

test('localAvatarUrl returns microlink url for x handle without gravatar lookups', function (): void {
    Gravatar::shouldReceive('exists')->never();

    $user = User::factory()->make(['x_handle' => 'johndoe']);

    expect($user->localAvatarUrl())->toContain('twitter.com/johndoe');
});

test('localAvatarUrl returns null without x handle and without gravatar lookups', function (): void {
    Gravatar::shouldReceive('exists')->never();

    $user = User::factory()->make(['x_handle' => null]);

    expect($user->localAvatarUrl())->toBeNull();
});
