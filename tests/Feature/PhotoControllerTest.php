<?php

declare(strict_types=1);

use App\User;

test('guest cannot access photo create', function (): void {
    $response = $this->get(route('photos.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated user can access photo create', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('photos.create'));

    $response->assertStatus(200);
});

test('authenticated user can store photo', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('photos.store'), [
        'url' => 'https://example.com/photo.jpg',
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('status-success');
    $this->assertDatabaseHas('photos', [
        'user_id' => $user->id,
        'url' => 'https://example.com/photo.jpg',
    ]);
});
