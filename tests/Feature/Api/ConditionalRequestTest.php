<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

function publicHerd(): User
{
    $user = User::factory()->create(['is_public' => true]);
    $user->elephpants()->attach(Elephpant::factory()->create()->id, ['quantity' => 1]);

    return $user;
}

test('a repeated read is answered with 304 and no body', function (string $route): void {
    $user = publicHerd();

    $first = $this->getJson(route($route, ['username' => $user->username, 'elephpant' => 1]));
    $first->assertOk();

    $etag = $first->headers->get('ETag');
    expect($etag)->not->toBeNull();

    $second = $this->getJson(
        route($route, ['username' => $user->username, 'elephpant' => 1]),
        ['If-None-Match' => $etag]
    );

    $second->assertStatus(304);
    expect($second->getContent())->toBe('');
})->with([
    'api.elephpants.index',
    'api.elephpants.show',
    'api.herds.show',
    'api.ranking.index',
    'api.countries.index',
]);

test('a changed resource stops matching the old etag', function (): void {
    $user = publicHerd();

    $etag = $this->getJson(route('api.herds.show', $user->username))->headers->get('ETag');

    $user->elephpants()->attach(Elephpant::factory()->create()->id, ['quantity' => 2]);

    $this->getJson(route('api.herds.show', $user->username), ['If-None-Match' => $etag])
        ->assertOk()
        ->assertJsonPath('stats.unique', 2);
});

test('a herd carries Last-Modified and honours If-Modified-Since', function (): void {
    $user = publicHerd();

    $response = $this->getJson(route('api.herds.show', $user->username))->assertOk();
    $lastModified = $response->headers->get('Last-Modified');

    expect($lastModified)->not->toBeNull();

    $this->getJson(route('api.herds.show', $user->username), ['If-Modified-Since' => $lastModified])
        ->assertStatus(304);
});

test('a herd Last-Modified moves when the profile changes, not just the herd', function (): void {
    $user = publicHerd();
    $user->elephpants()->newPivotQuery()->update(['updated_at' => now()->subYear()]);

    $lastModified = $this->getJson(route('api.herds.show', $user->username))
        ->headers->get('Last-Modified');

    $this->travelTo(now()->addMinute());
    $user->touch();

    $this->getJson(route('api.herds.show', $user->username), ['If-Modified-Since' => $lastModified])
        ->assertOk();
});

test('a private herd is still forbidden and carries no etag', function (): void {
    $user = User::factory()->create(['is_public' => false]);

    $response = $this->getJson(route('api.herds.show', $user->username));

    $response->assertForbidden();
    expect($response->headers->get('ETag'))->toBeNull();
});
