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

    $lastUpdate = $top->elephpants()->max('elephpant_user.updated_at');

    $response = $this->getJson(route('api.ranking.index'));

    $response->assertOk();
    $response->assertJsonPath('data.0.username', $top->username);
    $response->assertJsonPath('data.0.rank', 1);
    $response->assertJsonPath('data.0.stats.unique', 2);
    $response->assertJsonPath('data.0.stats.total', 2);
    $response->assertJsonPath('data.0.stats.spare', 0);
    $response->assertJsonPath('data.0.updated_at', \Illuminate\Support\Carbon::parse($lastUpdate)->toIso8601String());
    $response->assertJsonPath('data.1.username', $bottom->username);
    $response->assertJsonPath('data.1.rank', 2);
});

test('api ranking endpoint filters by country', function (): void {
    $user = User::factory()->create(['is_public' => true, 'country_code' => 'DNK']);
    $other = User::factory()->create(['is_public' => true, 'country_code' => 'GBR']);
    $elephpant = Elephpant::factory()->create();

    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);
    $other->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index', ['country' => 'DNK']));

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.username', $user->username);
});

test('api ranking endpoint excludes private herds', function (): void {
    $user = User::factory()->create(['is_public' => false]);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index'));

    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});

test('api ranking endpoint response is paginated with data, links and meta', function (): void {
    $user = User::factory()->create(['is_public' => true]);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.ranking.index'))
        ->assertOk();

    $response->assertJsonStructure(['data', 'links', 'meta']);
    $response->assertJsonPath('meta.current_page', 1);

    $emptyPage = $this->getJson(route('api.ranking.index', ['page' => 2]))
        ->assertOk();

    $emptyPage->assertJsonCount(0, 'data');
    $emptyPage->assertJsonPath('meta.current_page', 2);
});
