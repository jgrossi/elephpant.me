<?php

use App\User;

test('login page can be viewed', function () {
    $this->get('/login')
        ->assertStatus(200);
});

test('register page can be viewed', function () {
    $this->get('/register')
        ->assertStatus(200);
});

test('user can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
});

test('user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout');

    $this->assertGuest();
});
