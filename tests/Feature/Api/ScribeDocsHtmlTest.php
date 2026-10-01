<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\ElephpantController;
use App\Http\Controllers\Api\HerdController;
use App\Http\Controllers\Api\RankingController;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\ResponseField;

/**
 * @return list<string>
 */
function scribeAttributeTexts(string $controller): array
{
    $texts = [];
    $class = new ReflectionClass($controller);

    foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if ($method->class !== $controller) {
            continue;
        }

        foreach ([Endpoint::class, ResponseField::class] as $attributeClass) {
            foreach ($method->getAttributes($attributeClass) as $attribute) {
                /** @var ReflectionAttribute $attribute */
                $instance = $attribute->newInstance();

                if ($instance instanceof Endpoint) {
                    $texts[] = (string) ($instance->description ?? '');
                }

                if ($instance instanceof ResponseField) {
                    $texts[] = (string) ($instance->description ?? '');
                }
            }
        }
    }

    return $texts;
}

test('scribe endpoint copy does not embed raw HTML tags that break the docs layout', function (string $controller): void {
    foreach (scribeAttributeTexts($controller) as $text) {
        expect($text)
            ->not->toMatch('/<(?!\/?(code|br|a|em|strong)\b)[^>]+>/i')
            ->and($text)->not->toContain('<code>');
    }
})->with([
    CountryController::class,
    ElephpantController::class,
    HerdController::class,
    RankingController::class,
]);
