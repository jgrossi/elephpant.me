<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('creating user sets username via observer', function () {
    $user = User::factory()->create([
        'name'     => 'Jane Doe',
        'x_handle' => null,
        'username' => null,
    ]);

    expect($user->username)->not->toBeNull()
        ->and($user->username)->toBeString();
});

test('creating user with explicit username preserves provided username', function () {
    $user = User::factory()->create([
        'username' => 'custom_username',
    ]);

    expect($user->username)->toBe('custom_username');
});

test('creating user without username generates username from x_handle', function () {
    $user = User::create([
        'name'         => 'Jane Doe',
        'email'        => 'jane@example.com',
        'password'     => 'secret',
        'country_code' => 'USA',
        'x_handle'     => 'janedoe_x',
    ]);

    expect($user->username)->toBe('janedoe_x');
});

test('creating user without username or x_handle generates username from name slug', function () {
    $user = User::create([
        'name'         => 'Jane Doe',
        'email'        => 'jane2@example.com',
        'password'     => 'secret',
        'country_code' => 'USA',
        'x_handle'     => null,
    ]);

    expect($user->username)->toBe('jane-doe');
});
