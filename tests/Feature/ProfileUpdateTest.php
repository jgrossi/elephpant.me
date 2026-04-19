<?php

declare(strict_types=1);

use App\User;

test('profile update with valid data redirects and flashes success', function (): void {
    $user = User::factory()->create([
        'name'         => 'Old Name',
        'email'        => 'old@example.com',
        'username'     => 'olduser',
        'country_code' => 'USA',
    ]);

    $response = $this->actingAs($user)->put(route('profile.update'), [
        'section'      => 'account',
        'name'         => 'New Name',
        'email'        => 'new@example.com',
        'username'     => 'newuser',
        'country_code' => 'GBR',
    ]);

    $response->assertRedirect(route('profile.edit'));
    $response->assertSessionHas('status-success');

    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@example.com');
    expect($user->username)->toBe('newuser');
    expect($user->country_code)->toBe('GBR');
});

test('profile update public_profile section saves twitter and mastodon', function (): void {
    $user = User::factory()->create(['twitter' => null, 'mastodon' => null]);

    $this->actingAs($user)->put(route('profile.update'), [
        'section'  => 'public_profile',
        'twitter'  => '@me',
        'mastodon' => '@me@mastodon.social',
    ]);

    $user->refresh();
    expect($user->twitter)->toBe('@me');
    expect($user->mastodon)->toBe('@me@mastodon.social');
});

test('profile update with password updates hashed password', function (): void {
    $user = User::factory()->create();
    $oldHash = $user->password;

    $this->actingAs($user)->put(route('profile.update'), [
        'section'               => 'password',
        'password'              => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $user->refresh();
    expect($user->password)->not->toBe($oldHash);
});
