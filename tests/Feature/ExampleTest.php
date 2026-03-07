<?php

use App\Elephpant;
use App\User;

test('homepage returns 200', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
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
