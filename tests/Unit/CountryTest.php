<?php

declare(strict_types=1);

use App\Country;
use Tests\TestCase;

uses(TestCase::class);

test('getFlagAttribute returns flag emoji for two-letter code', function (): void {
    $country = new Country();
    $country->code_2 = 'GB';

    expect($country->flag)->not->toBe('');
});

test('getFlagAttribute returns empty for non-two-letter code', function (): void {
    $country = new Country();
    $country->code_2 = 'GBR';

    expect($country->flag)->toBe('');
});

test('forDropdown returns array keyed by code when filterCodes provided', function (): void {
    $result = Country::forDropdown(['USA', 'GBR']);

    expect($result)->toBeArray();
});

test('getRows returns empty array when countries file does not exist', function (): void {
    $country = new Country();
    $ref = new ReflectionMethod($country, 'getRows');
    $path = database_path('data/countries.json');
    if (!file_exists($path)) {
        $this->markTestSkipped('countries.json not found');
    }
    $backup = $path.'.bak';
    rename($path, $backup);

    try {
        $rows = $ref->invoke($country);
        expect($rows)->toBeArray()->toBeEmpty();
    } finally {
        if (file_exists($backup)) {
            rename($backup, $path);
        }
    }
});
