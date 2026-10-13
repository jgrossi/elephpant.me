<?php

declare(strict_types=1);

use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Writing\OpenAPISpecWriter;

/**
 * Builds the spec the way scribe:generate does, from the config the app ships, so these tests fail if the generator is
 * ever dropped from config/scribe.php.
 *
 * @return array<string, string|array>
 */
function specFor(string $uri): array
{
    $endpoint = new OutputEndpointData([
        'httpMethods' => ['GET'],
        'uri' => $uri,
        'metadata' => ['groupName' => 'Endpoints', 'title' => $uri],
        'responses' => [
            ['status' => 200, 'content' => '{}'],
            ['status' => 500, 'content' => '{"message":"Server Error"}'],
        ],
    ]);

    return (new OpenAPISpecWriter())->generateSpecContent([
        ['name' => 'Endpoints', 'description' => '', 'endpoints' => [$endpoint]],
    ]);
}

/**
 * @return array<int|string, mixed>
 */
function documentedResponses(array $spec, string $uri): array
{
    return $spec['paths']['/'.$uri]['get']['responses'];
}

/**
 * @return list<string>
 */
function documentedHeaders(array $spec, string $uri, int $status): array
{
    return array_keys(documentedResponses($spec, $uri)[$status]['headers'] ?? []);
}

/**
 * URIs as Scribe writes them, which is not always as the route declares them: Scribe
 * renames a bound model parameter such as {elephpant} to {id}.
 */
test('every rate limited endpoint documents a 429', function (string $uri): void {
    expect(documentedResponses(specFor($uri), $uri))->toHaveKey(429);
})->with([
    'api/elephpants',
    'api/elephpants/{id}',
    'api/herd/{username}',
    'api/ranking',
    'api/countries',
]);

test('the documented 429 body is the one the limiter actually sends', function (): void {
    config(['api.rate_limit' => 1]);
    $caller = '198.18.2.1';

    $this->withServerVariables(['REMOTE_ADDR' => $caller])->getJson(route('api.countries.index'));
    $actual = $this->withServerVariables(['REMOTE_ADDR' => $caller])
        ->getJson(route('api.countries.index'))
        ->assertStatus(429)
        ->json();

    expect(documentedResponses(specFor('api/countries'), 'api/countries')[429]['content']['application/json']['schema']['example'])
        ->toBe($actual);
});

test('every response from a rate limited endpoint documents where the caller stands', function (int $status): void {
    expect(documentedHeaders(specFor('api/countries'), 'api/countries', $status))
        ->toContain('X-RateLimit-Limit', 'X-RateLimit-Remaining');
})->with([200, 429, 500]);

test('only the 429 documents when to try again', function (): void {
    $spec = specFor('api/countries');

    expect(documentedHeaders($spec, 'api/countries', 429))
        ->toEqualCanonicalizing(['X-RateLimit-Limit', 'X-RateLimit-Remaining', 'Retry-After', 'X-RateLimit-Reset'])
        ->and(documentedHeaders($spec, 'api/countries', 200))->not->toContain('Retry-After', 'X-RateLimit-Reset');
});

test('the headers are defined once and referenced from each response', function (): void {
    $spec = specFor('api/countries');

    expect(array_keys($spec['components']['headers']))
        ->toEqualCanonicalizing(['X-RateLimit-Limit', 'X-RateLimit-Remaining', 'Retry-After', 'X-RateLimit-Reset'])
        ->and(documentedResponses($spec, 'api/countries')[200]['headers']['X-RateLimit-Limit'])
        ->toBe(['$ref' => '#/components/headers/X-RateLimit-Limit']);
});

test('the documented limit follows the configured one', function (): void {
    config(['api.rate_limit' => 42]);

    expect(specFor('api/countries')['components']['headers']['X-RateLimit-Limit']['schema']['example'])->toBe(42);
});

test('endpoints outside the rate limit document neither the headers nor a 429', function (): void {
    $spec = specFor('api/user');

    expect(documentedResponses($spec, 'api/user'))->not->toHaveKey(429)
        ->and(documentedHeaders($spec, 'api/user', 200))->toBeEmpty();
});
