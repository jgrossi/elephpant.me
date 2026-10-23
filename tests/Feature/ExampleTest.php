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

test('herd show displays the last herd update', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->get(route('herds.show', $user->username));

    $response->assertSuccessful();
    $response->assertSee('Herd updated');
});

test('herd show displays when the user joined alongside the last herd update', function () {
    $user = User::factory()->create(['created_at' => '2021-03-15 10:00:00']);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $this->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->assertSeeInOrder(['Joined March 2021', '•', 'Herd updated']);
});

test('herd show displays when the user joined even without any elephpants', function () {
    $user = User::factory()->create(['created_at' => '2019-11-02 08:30:00']);

    $this->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->assertSee('Joined November 2019')
        ->assertDontSee('Herd updated');
});

test('herd show with authenticated user includes contact section', function () {
    $user = User::factory()->create();
    $viewer = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $this->actingAs($viewer)
        ->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->assertSee('Collection')
        ->assertSee('Contact')
        ->assertSee('Send Message');
});

test('herd show does not show contact section on own profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->assertSee('Collection')
        ->assertDontSee('>Contact<', false);
});
