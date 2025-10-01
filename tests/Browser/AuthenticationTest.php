<?php

use App\User;

use function Pest\Laravel\browser;

test('user can register through browser', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/register'))
            ->waitForText('Register')
            ->type('name', 'Browser Test User')
            ->type('email', 'browsertest@example.com')
            ->type('password', 'password123')
            ->type('password_confirmation', 'password123')
            ->select('country_code', 'USA')
            ->press('Register')
            ->waitForLocation('/home', 10)
            ->screenshot('after-registration');
    });

    expect(User::where('email', 'browsertest@example.com')->exists())->toBeTrue();
})->group('browser');

test('user can login through browser', function () {
    $user = User::factory()->create([
        'email' => 'logintest@example.com',
        'password' => bcrypt('password123'),
    ]);

    browser(function ($browser, $url) {
        $browser
            ->visit($url('/login'))
            ->waitForText('Login')
            ->type('email', 'logintest@example.com')
            ->type('password', 'password123')
            ->press('Login')
            ->waitForLocation('/', 10)
            ->screenshot('after-login');
    });
})->group('browser');

test('navigation menu is visible and functional', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/'))
            ->assertSee('Home')
            ->assertSee('Species')
            ->assertSee('Ranking')
            ->click('a[href*="/species"]')
            ->waitForLocation('/species', 5)
            ->assertPathIs('/species')
            ->screenshot('navigation-test');
    });
})->group('browser');
