<?php

declare(strict_types=1);

namespace App\Docs;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Routing\Route as LaravelRoute;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Knuckles\Camel\Output\OutputEndpointData;
use Knuckles\Scribe\Writing\OpenApiSpecGenerators\OpenApiGenerator;

/**
 * The \Knuckles\Scribe\Attributes\Response object does not support response headers so this class generates
 * documentation for the API rate limit on every route it applies to, including both the response headers and the 429
 * HTTP status code.  Values are read from the limiter and the routes themselves, so a new throttled endpoint, a new
 * limit or a reworded 429 is documented without modifying that class or this one.
 * @see \Knuckles\Scribe\Attributes\Response
 */
final class RateLimitDocumentation extends OpenApiGenerator
{
    private const string LIMITER = 'api';

    private const array ON_EVERY_RESPONSE = ['X-RateLimit-Limit', 'X-RateLimit-Remaining'];

    private const array ON_TOO_MANY_REQUESTS = ['Retry-After', 'X-RateLimit-Reset'];

    /**
     * @param  array<string, mixed>  $root
     * @param  array<int, array{description: string, name: string, endpoints: OutputEndpointData[]}>  $groupedEndpoints
     * @return array<string, mixed>
     */
    #[\Override]
    public function root(array $root, array $groupedEndpoints): array
    {
        $root['components']['headers'] = [
            ...$root['components']['headers'] ?? [],
            'X-RateLimit-Limit' => [
                'description' => 'Requests allowed per minute from your address.',
                'schema' => ['type' => 'integer', 'example' => RateLimitDocumentation::limit()->maxAttempts],
            ],
            'X-RateLimit-Remaining' => [
                'description' => 'Requests left in the current minute.',
                'schema' => ['type' => 'integer', 'example' => 17],
            ],
            'Retry-After' => [
                'description' => 'Seconds to wait before trying again.',
                'schema' => ['type' => 'integer', 'example' => 42],
            ],
            'X-RateLimit-Reset' => [
                'description' => 'Unix timestamp at which you may try again.',
                'schema' => ['type' => 'integer', 'example' => 1767225600],
            ],
        ];

        return $root;
    }

    /**
     * @param  array<string, mixed> $pathItem
     * @param  array<int, array{description: string, name: string, endpoints: OutputEndpointData[]}> $groupedEndpoints
     * @return array<string, mixed>
     */
    #[\Override]
    public function pathItem(array $pathItem, array $groupedEndpoints, OutputEndpointData $endpoint): array
    {
        if (! is_array($pathItem['responses'] ?? null) || !RateLimitDocumentation::isThrottled($endpoint)) {
            return $pathItem;
        }

        $pathItem['responses'][429] ??= RateLimitDocumentation::tooManyRequestsResponse();
        ksort($pathItem['responses']);

        foreach ($pathItem['responses'] as $status => $response) {
            $headers = (int) $status === 429
                ? [...self::ON_EVERY_RESPONSE, ...self::ON_TOO_MANY_REQUESTS]
                : self::ON_EVERY_RESPONSE;

            $pathItem['responses'][$status]['headers'] = [
                ...$response['headers'] ?? [],
                ...RateLimitDocumentation::references($headers),
            ];
        }

        return $pathItem;
    }

    /**
     * Matches on the URI's shape alone, because Scribe renames bound model parameters,
     * documenting /elephpants/{elephpant} as /elephpants/{id}.
     */
    private static function isThrottled(OutputEndpointData $endpoint): bool
    {
        $route = collect(Route::getRoutes()->get($endpoint->httpMethods[0]))
            ->first(fn (LaravelRoute $route): bool => RateLimitDocumentation::shape($route->uri()) === RateLimitDocumentation::shape($endpoint->uri));

        return $route !== null && in_array('throttle:'.self::LIMITER, $route->gatherMiddleware(), true);
    }

    private static function shape(string $uri): string
    {
        return (string) preg_replace('/\{[^}]+}/', '{}', $uri);
    }

    private static function limit(): Limit
    {
        return RateLimiter::limiter(self::LIMITER)(Request::create('/'));
    }

    /**
     * @return array<string, string|array>
     */
    private static function tooManyRequestsResponse(): array
    {
        $body = json_decode((string) (RateLimitDocumentation::limit()->responseCallback)(Request::create('/'), [])->getContent(), true);

        return [
            'description' => 'Rate limit exceeded. Wait the number of seconds in Retry-After before trying again.',
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'object',
                        'example' => $body,
                        'properties' => collect($body)->map(fn (mixed $value): array => ['type' => 'string', 'example' => $value])->all(),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param list<string> $headers
     * @return array<string, array{'$ref': string}>
    */
    private static function references(array $headers): array
    {
        return collect($headers)
            ->mapWithKeys(fn (string $header): array => [$header => ['$ref' => '#/components/headers/'.$header]])
            ->all();
    }
}
