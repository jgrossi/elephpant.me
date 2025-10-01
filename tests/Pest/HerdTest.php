<?php

use App\User;

test('user herd page loads successfully', function () {
    $user = User::factory()->create([
        'username' => 'testuser',
    ]);

    $response = $this->get('/herd/testuser');

    $response->assertStatus(200)
        ->assertSee($user->name);
});

test('returns 404 for nonexistent user herd', function () {
    $this->get('/herd/nonexistent')
        ->assertStatus(404);
});

test('authenticated user can view their own herd', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/my-herd')
        ->assertStatus(200);
});

test('guest cannot view my herd page', function () {
    $this->get('/my-herd')
        ->assertRedirect('/login');
});
