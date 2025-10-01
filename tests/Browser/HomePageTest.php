<?php

use function Pest\Laravel\browser;

test('homepage loads and displays content', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/'))
            ->waitForText('elePHPant')
            ->assertSee('elePHPant')
            ->screenshot('homepage');
    });
})->group('browser');

test('species page loads and shows elephpants', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/species'))
            ->waitForText('Species')
            ->assertSee('Species')
            ->screenshot('species-page');
    });
})->group('browser');

test('ranking page displays user rankings', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/ranking'))
            ->waitForText('Ranking')
            ->assertSee('Ranking')
            ->screenshot('ranking-page');
    });
})->group('browser');

test('statistics page shows statistics', function () {
    browser(function ($browser, $url) {
        $browser
            ->visit($url('/statistics'))
            ->waitForText('Statistics')
            ->assertSee('Statistics')
            ->screenshot('statistics-page');
    });
})->group('browser');
