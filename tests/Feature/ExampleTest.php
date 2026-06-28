<?php

use App\Elephpant;
use App\User;

test('homepage returns 200', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('homepage shows total elephpants collected across all herds', function () {
    $user = User::factory()->create();
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $user->elephpants()->attach($e1->id, ['quantity' => 2]);
    $user->elephpants()->attach($e2->id, ['quantity' => 1]);

    $response = $this->get('/');

    $response->assertSee('Total Collected');
    $response->assertSee('3');
});

test('herd show returns 200 for existing user', function () {
    $user = User::factory()->create();

    $response = $this->get(route('herds.show', $user->username));

    $response->assertStatus(200);
});

test('herd show with authenticated user includes possible trades', function () {
    $user = User::factory()->create();
    $viewer = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($viewer)->get(route('herds.show', $user->username));

    $response->assertStatus(200);
});
