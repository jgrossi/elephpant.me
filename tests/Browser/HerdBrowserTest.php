<?php

use App\User;

use function Pest\Laravel\browser;

test('user profile page displays correctly', function () {
    $user = User::factory()->create([
        'username' => 'browsertestuser',
        'name' => 'Browser Test User',
        'x_handle' => 'testhandle',
        'mastodon' => '@test@mastodon.social',
        'bluesky' => '@test.bsky.social',
        'country_code' => 'USA',
    ]);

    browser(function ($browser, $url) use ($user) {
        $browser
            ->visit($url("/herd/{$user->username}"))
            ->waitForText($user->name)
            ->assertSee($user->name)
            ->assertSee('@testhandle')
            ->screenshot('user-profile');
    });
})->group('browser');

test('authenticated user can view their herd', function () {
    $user = User::factory()->create([
        'email' => 'herdt est@example.com',
        'password' => bcrypt('password123'),
    ]);

    browser(function ($browser, $url) use ($user) {
        $browser
            ->visit($url('/login'))
            ->type('email', $user->email)
            ->type('password', 'password123')
            ->press('Login')
            ->waitForLocation('/', 10)
            ->visit($url('/my-herd'))
            ->waitForText('My Herd')
            ->assertSee('My Herd')
            ->screenshot('my-herd-page');
    });
})->group('browser');

test('profile page shows social media links', function () {
    $user = User::factory()->create([
        'username' => 'socialuser',
        'x_handle' => 'xhandle',
        'mastodon' => '@user@mastodon.social',
        'bluesky' => '@user.bsky.social',
    ]);

    browser(function ($browser, $url) use ($user) {
        $browser
            ->visit($url("/herd/{$user->username}"))
            ->waitForText($user->name)
            ->assertSee('X/Twitter')
            ->assertSee('Mastodon')
            ->assertSee('Bluesky')
            ->screenshot('social-links');
    });
})->group('browser');
