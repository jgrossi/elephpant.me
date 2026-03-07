<?php

declare(strict_types=1);

use App\Elephpant;
use App\Queries\ElephpantsQuery;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    $this->query = new ElephpantsQuery();
});

test('fetchAllOrderedAndGrouped returns elephpants grouped by year', function (): void {
    $e2022 = Elephpant::factory()->create(['year' => 2022]);
    $e2023 = Elephpant::factory()->create(['year' => 2023]);
    $e2023b = Elephpant::factory()->create(['year' => 2023]);

    $user = User::factory()->create();
    $this->actingAs($user);

    $result = $this->query->fetchAllOrderedAndGrouped();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($result->has(2023))->toBeTrue();
    expect($result->has(2022))->toBeTrue();
    expect($result->get(2023))->toHaveCount(2);
    expect($result->get(2022))->toHaveCount(1);
});

test('fetchAllOrderedAndGrouped adds possible_senders and possible_receivers subquery fields', function (): void {
    $e = Elephpant::factory()->create(['year' => 2024]);
    $user = User::factory()->create();
    $this->actingAs($user);

    $result = $this->query->fetchAllOrderedAndGrouped();

    expect($result->get(2024))->not->toBeNull();
    $first = $result->get(2024)->first();
    expect($first)->toHaveKeys(['possible_senders', 'possible_receivers']);
});
