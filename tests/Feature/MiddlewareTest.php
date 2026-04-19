<?php

declare(strict_types=1);

use App\User;

test('authenticate middleware redirects to login when guest requests protected route without json', function (): void {
    $response = $this->get(route('herds.edit'));

    $response->assertRedirect(route('login'));
});

test('authenticate middleware returns 401 when guest requests protected route with accept json', function (): void {
    $response = $this->getJson(route('herds.edit'));

    $response->assertStatus(401);
});

test('redirect if authenticated redirects to home when authenticated user visits login', function (): void {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('login'));

    $response->assertRedirect('/home');
});

test('redirect if authenticated allows guest to access login', function (): void {
    $response = $this->get(route('login'));

    $response->assertStatus(200);
});

test('redirect if authenticated redirects to home when authenticated user visits register', function (): void {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('register'));

    $response->assertRedirect('/home');
});
