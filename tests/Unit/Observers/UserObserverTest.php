<?php

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('creating user sets username via observer', function () {
    $user = User::factory()->create([
        'name'     => 'Jane Doe',
        'twitter'  => null,
        'username' => null,
    ]);

    expect($user->username)->not->toBeNull()
        ->and($user->username)->toBeString();
});
