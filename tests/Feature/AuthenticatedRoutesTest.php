<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('authenticated user can access my-herd', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('herds.edit'));

    $response->assertStatus(200);
});

test('authenticated user can access trade', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('trades.index'));

    $response->assertStatus(200);
});

test('authenticated user can access trade with country filter', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('trades.index', ['country' => 'GBR']));

    $response->assertStatus(200);
});

test('authenticated user can access profile', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertStatus(200);
});

test('authenticated user can access trade senders', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();

    $response = $this->actingAs($user)->get(route('trades.senders', ['elephpantId' => $elephpant->id]));

    $response->assertStatus(200);
});

test('trade senders with traders calls getCountryCodesWithTraders', function (): void {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $elephpant = Elephpant::factory()->create();
    $userA->elephpants()->attach($elephpant->id, ['quantity' => 2]);
    $userB->elephpants()->attach($elephpant->id, ['quantity' => 2]);

    $response = $this->actingAs($userA)->get(route('trades.senders', ['elephpantId' => $elephpant->id]));

    $response->assertStatus(200);
});

test('authenticated user can access trade receivers', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();

    $response = $this->actingAs($user)->get(route('trades.receivers', ['elephpantId' => $elephpant->id]));

    $response->assertStatus(200);
});

test('trade receivers with traders calls getCountryCodesWithTraders', function (): void {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userA->elephpants()->attach($e2->id, ['quantity' => 1]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $response = $this->actingAs($userA)->get(route('trades.receivers', ['elephpantId' => $e1->id]));

    $response->assertStatus(200);
});

test('trade index adds country to dropdown when not in trader list', function (): void {
    $user = User::factory()->create(['country_code' => 'USA']);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($user)->get(route('trades.index', ['country' => 'CAN']));

    $response->assertStatus(200);
});

test('trade senders adds current country to dropdown when not in list', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($user)->get(route('trades.senders', ['elephpantId' => $elephpant->id, 'country' => 'XYZ']));

    $response->assertStatus(200);
});

test('trade receivers adds current country to dropdown when not in list', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($user)->get(route('trades.receivers', ['elephpantId' => $elephpant->id, 'country' => 'XYZ']));

    $response->assertStatus(200);
});
