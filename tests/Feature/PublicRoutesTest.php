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

test('herd show avatars fall back on image error and are not circles', function (): void {
    \Creativeorange\Gravatar\Facades\Gravatar::shouldReceive('exists')->andReturn(false);

    $user = \App\User::factory()->create([
        'x_handle' => 'webaaz',
        'name'     => 'Webaaz',
        'username' => 'webaaz',
        'is_public' => true,
    ]);

    $html = $this->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->getContent();

    expect($html)
        ->toContain('onerror="this.remove()"')
        ->not->toContain('data-circle="true"');
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

test('legacy openapi.yaml redirects to scribe docs.openapi', function (): void {
    $this->get('/openapi.yaml')
        ->assertRedirect('/docs.openapi');
});
