<?php

declare(strict_types=1);

test('species page returns 200', function (): void {
    $response = $this->get(route('elephpants.index'));

    $response->assertStatus(200);
});

test('species page hides the herd shortcut from guests', function (): void {
    $this->get(route('elephpants.index'))
        ->assertSuccessful()
        ->assertDontSeeText('Go to "My Herd" page');
});

test('species page shows the herd shortcut to authenticated users', function (): void {
    $user = \App\User::factory()->create();

    $this->actingAs($user)->get(route('elephpants.index'))
        ->assertSuccessful()
        ->assertSeeText('Go to "My Herd" page');
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

test('herd show hides private herd details from guests', function (): void {
    $user = \App\User::factory()->create(['is_public' => false, 'name' => 'Secret Collector']);
    $elephpant = \App\Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 3]);

    $this->get(route('herds.show', $user->username))
        ->assertForbidden()
        ->assertSeeText('This herd is private')
        ->assertDontSeeText('Secret Collector')
        ->assertDontSee('public-herd-collection');
});

test('herd show hides private herd details from other users', function (): void {
    $user = \App\User::factory()->create(['is_public' => false, 'name' => 'Secret Collector']);

    $this->actingAs(\App\User::factory()->create())
        ->get(route('herds.show', $user->username))
        ->assertForbidden()
        ->assertSeeText('This herd is private')
        ->assertDontSeeText('Secret Collector');
});

test('herd show lets the owner view their own private herd', function (): void {
    $user = \App\User::factory()->create(['is_public' => false, 'name' => 'Secret Collector']);

    $this->actingAs($user)
        ->get(route('herds.show', $user->username))
        ->assertSuccessful()
        ->assertSeeText('Secret Collector')
        ->assertDontSeeText('This herd is private');
});

test('herd show avatars fall back on image error and are not circles', function (): void {
    \Creativeorange\Gravatar\Facades\Gravatar::shouldReceive('exists')->andReturn(false);

    $user = \App\User::factory()->create([
        'x_handle'  => 'example_user',
        'name'      => 'Example User',
        'username'  => 'example-user',
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
