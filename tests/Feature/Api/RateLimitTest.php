<?php

declare(strict_types=1);

/**
 * Every test here calls from an address of its own, so none of them depends on how many
 * requests another test made first. Counters live in the cache, and a cache that outlives
 * a test would otherwise leak one test's attempts into the next.
 */
function freshCaller(): string
{
    static $caller = 0;

    return '198.18.0.'.++$caller;
}

/**
 * Turns the real limiter down rather than replacing it, so these tests fail if the
 * limiter itself is wrong. Replacing it would only ever test the replacement.
 */
function limitApiTo(int $perMinute): void
{
    config(['api.rate_limit' => $perMinute]);
}

test('every response says where the caller stands, and counts down', function (): void {
    $caller = freshCaller();
    limitApiTo(10);

    $first = $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'))
        ->assertOk();

    $first->assertHeader('X-RateLimit-Limit', 10);
    $first->assertHeader('X-RateLimit-Remaining', 9);

    $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'))
        ->assertHeader('X-RateLimit-Remaining', 8);
});

test('going over the limit earns a 429 in JSON with a Retry-After', function (): void {
    $caller = freshCaller();
    limitApiTo(2);

    $this->withServerVariables(['REMOTE_ADDR' => $caller])->getJson(route('api.countries.index'))->assertOk();
    $this->withServerVariables(['REMOTE_ADDR' => $caller])->getJson(route('api.countries.index'))->assertOk();

    $response = $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'));

    $response->assertStatus(429);
    $response->assertHeader('Content-Type', 'application/json');
    $response->assertJsonStructure(['message']);
    expect((int) $response->headers->get('Retry-After'))->toBeGreaterThan(0);
    expect($response->headers->get('X-RateLimit-Reset'))->not->toBeNull();
});

test('the limit is counted per caller, not globally', function (): void {
    $caller = freshCaller();
    $other = freshCaller();
    limitApiTo(1);

    $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'))
        ->assertOk();

    $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'))
        ->assertStatus(429);

    $this->withServerVariables(['REMOTE_ADDR' => $other])
        ->getJson(route('api.countries.index'))
        ->assertOk();
});

test('the whole public API is limited, not just one endpoint', function (string $route): void {
    $response = $this->withServerVariables(['REMOTE_ADDR' => freshCaller()])
        ->getJson(route($route, ['username' => 'nobody', 'elephpant' => 1]));

    expect($response->headers->get('X-RateLimit-Limit'))->not->toBeNull();
})->with([
    'api.elephpants.index',
    'api.elephpants.show',
    'api.herds.show',
    'api.ranking.index',
    'api.countries.index',
]);
