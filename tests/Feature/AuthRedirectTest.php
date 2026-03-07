<?php

declare(strict_types=1);

use App\Elephpant;

test('guest redirected from my-herd to login', function (): void {
    $response = $this->get(route('herds.edit'));

    $response->assertRedirect(route('login'));
});

test('guest redirected from trade to login', function (): void {
    $response = $this->get(route('trades.index'));

    $response->assertRedirect(route('login'));
});

test('guest redirected from profile to login', function (): void {
    $response = $this->get(route('profile.edit'));

    $response->assertRedirect(route('login'));
});

test('guest redirected from trade senders to login', function (): void {
    $elephpant = Elephpant::factory()->create();

    $response = $this->get(route('trades.senders', ['elephpantId' => $elephpant->id]));

    $response->assertRedirect(route('login'));
});

test('guest redirected from trade receivers to login', function (): void {
    $elephpant = Elephpant::factory()->create();

    $response = $this->get(route('trades.receivers', ['elephpantId' => $elephpant->id]));

    $response->assertRedirect(route('login'));
});
