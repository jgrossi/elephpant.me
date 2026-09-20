<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('api ranking endpoint returns users ordered by unique then total elephpants', function (): void {
    $top = User::factory()->create(['name' => 'Top', 'is_public' => true, 'country_code' => 'DNK']);
    $bottom = User::factory()->create(['name' => 'Bottom', 'is_public' => true, 'country_code' => 'DNK']);

    $elephpantA = Elephpant::factory()->create();
    $elephpantB = Elephpant::factory()->create();

    $top->elephpants()->attach($elephpantA->id, ['quantity' => 1]);
    $top->elephpants()->attach($elephpantB->id, ['quantity' => 1]);
    $bottom->elephpants()->attach($elephpantA->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index'));

    $response->assertOk();
    $response->assertJsonPath('ranking.0.username', $top->username);
    $response->assertJsonPath('ranking.0.rank', 1);
    $response->assertJsonPath('ranking.1.username', $bottom->username);
    $response->assertJsonPath('ranking.1.rank', 2);
});

test('api ranking endpoint filters by country', function (): void {
    $user = User::factory()->create(['is_public' => true, 'country_code' => 'DNK']);
    $other = User::factory()->create(['is_public' => true, 'country_code' => 'GBR']);
    $elephpant = Elephpant::factory()->create();

    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);
    $other->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index', ['country' => 'DNK']));

    $response->assertOk();
    $response->assertJsonCount(1, 'ranking');
    $response->assertJsonPath('ranking.0.username', $user->username);
    $response->assertJsonPath('country', 'DNK');
});

test('api ranking endpoint excludes private herds', function (): void {
    $user = User::factory()->create(['is_public' => false]);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index'));

    $response->assertOk();
    $response->assertJsonCount(0, 'ranking');
});
