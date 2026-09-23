<?php

declare(strict_types=1);

use App\User;

test('api countries endpoint returns codes, names and collector counts ordered by collectors desc', function (): void {
    $dnk1 = User::factory()->create(['is_public' => true, 'country_code' => 'DNK']);
    $dnk2 = User::factory()->create(['is_public' => true, 'country_code' => 'DNK']);
    $gbr = User::factory()->create(['is_public' => true, 'country_code' => 'GBR']);

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonCount(2, 'countries');
    $response->assertJsonPath('countries.0.code', 'DNK');
    $response->assertJsonPath('countries.0.name', 'Denmark');
    $response->assertJsonPath('countries.0.collectors', 2);
    $response->assertJsonPath('countries.1.code', 'GBR');
    $response->assertJsonPath('countries.1.collectors', 1);
});

test('api countries endpoint excludes private herds from the count', function (): void {
    User::factory()->create(['is_public' => true, 'country_code' => 'DNK']);
    User::factory()->create(['is_public' => false, 'country_code' => 'DNK']);

    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonPath('countries.0.collectors', 1);
});

test('api countries endpoint returns an empty list when there are no public collectors', function (): void {
    $response = $this->getJson(route('api.countries.index'));

    $response->assertOk();
    $response->assertJsonCount(0, 'countries');
});
