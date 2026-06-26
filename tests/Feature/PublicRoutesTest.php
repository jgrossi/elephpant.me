<?php

declare(strict_types=1);

test('species page returns 200', function (): void {
    $response = $this->get(route('elephpants.index'));

    $response->assertStatus(200);
});

test('species page with q query string loads search', function (): void {
    $response = $this->get(route('elephpants.index', ['q' => 'test']));

    $response->assertStatus(200);
});

test('ranking page returns 200', function (): void {
    $response = $this->get(route('rankings.index'));

    $response->assertStatus(200);
});

test('herd show returns 404 for missing username', function (): void {
    $response = $this->get(route('herds.show', ['username' => 'nonexistent-user-12345']));

    $response->assertStatus(404);
});

test('statistics page returns 200', function (): void {
    $response = $this->get(route('statistics.index'));

    $response->assertStatus(200);
});

test('statistics page contains key content', function (): void {
    $response = $this->get(route('statistics.index'));

    $response->assertStatus(200);
    $response->assertSee('Statistics', false);
});
