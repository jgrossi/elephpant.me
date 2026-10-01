<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ElephpantController;
use App\Http\Controllers\Api\HerdController;
use App\Http\Controllers\Api\RankingController;
use Knuckles\Scribe\Attributes\Response;

/**
 * @return list<int>
 */
function documentedErrorStatuses(string $controller, string $method): array
{
    $reflection = new ReflectionMethod($controller, $method);

    return collect($reflection->getAttributes(Response::class))
        ->map(fn (ReflectionAttribute $attribute): int => $attribute->newInstance()->status)
        ->filter(fn (int $status): bool => $status >= 400)
        ->values()
        ->all();
}

test('every public API operation documents at least one error response for Scribe', function (string $controller, string $method, array $expected): void {
    expect(documentedErrorStatuses($controller, $method))
        ->toEqualCanonicalizing($expected);
})->with([
    'countries index' => [CountryController::class, 'index', [500]],
    'elephpants index' => [ElephpantController::class, 'index', [500]],
    'elephpants show' => [ElephpantController::class, 'show', [404, 500]],
    'herd show' => [HerdController::class, 'show', [403, 404, 500]],
    'ranking index' => [RankingController::class, 'index', [500]],
]);

test('api herd endpoint returns not found for an unknown username', function (): void {
    $this->getJson(route('api.herds.show', 'nobody-here'))
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

test('api elephpants show returns not found for an unknown id', function (): void {
    $this->getJson(route('api.elephpants.show', 999_999))
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});
