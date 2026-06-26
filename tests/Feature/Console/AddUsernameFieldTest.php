<?php

declare(strict_types=1);

use App\User;

test('users fix-username command sets username for users with null username', function (): void {
    $user = User::factory()->create();
    $user->update(['username' => null]);
    $user->refresh();

    expect($user->username)->toBeNull();

    $this->artisan('users:fix-username')->assertSuccessful();

    $user->refresh();
    expect($user->username)->not->toBeNull();
});
