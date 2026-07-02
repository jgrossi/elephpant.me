<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('guests are redirected to login before accessing a herd comparison', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();

    $this->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertRedirect(route('login'));
});

test('comparing a herd to itself returns a 400', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('herds.compare', [$user->username, $user->username]))
        ->assertStatus(400)
        ->assertSee('Cannot compare the same herd to itself');
});

test('comparing against an unknown first username shows a not-found message', function (): void {
    $user = User::factory()->create();
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', ['does-not-exist', $user->username]))
        ->assertNotFound()
        ->assertSee('User does-not-exist not found');
});

test('comparing against an unknown second username shows a not-found message', function (): void {
    $user = User::factory()->create();
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$user->username, 'does-not-exist']))
        ->assertNotFound()
        ->assertSee('User does-not-exist not found');
});

test('a private herd is forbidden to other users', function (): void {
    $privateUser = User::factory()->create(['is_public' => false]);
    $publicUser = User::factory()->create(['is_public' => true]);
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$privateUser->username, $publicUser->username]))
        ->assertForbidden();
});

test('a private second herd is also forbidden to other users', function (): void {
    $publicUser = User::factory()->create(['is_public' => true]);
    $privateUser = User::factory()->create(['is_public' => false]);
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$publicUser->username, $privateUser->username]))
        ->assertForbidden();
});

test('the owner of a private herd can still view their own comparison', function (): void {
    $privateUser = User::factory()->create(['is_public' => false]);
    $publicUser = User::factory()->create(['is_public' => true]);

    $this->actingAs($privateUser)
        ->get(route('herds.compare', [$privateUser->username, $publicUser->username]))
        ->assertOk();

    $this->actingAs($privateUser)
        ->get(route('herds.compare', [$publicUser->username, $privateUser->username]))
        ->assertOk();
});

test('two private herds are forbidden to a third party', function (): void {
    $privateUser0 = User::factory()->create(['is_public' => false]);
    $privateUser1 = User::factory()->create(['is_public' => false]);
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$privateUser0->username, $privateUser1->username]))
        ->assertForbidden();
});

test('being one side of the comparison does not grant access to the other users private herd', function (): void {
    $privateUser = User::factory()->create(['is_public' => false]);
    $viewer = User::factory()->create(['is_public' => true]);

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$privateUser->username, $viewer->username]))
        ->assertForbidden();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$viewer->username, $privateUser->username]))
        ->assertForbidden();
});

test('two public herds can be compared by any authenticated user', function (): void {
    $user0 = User::factory()->create(['is_public' => true]);
    $user1 = User::factory()->create(['is_public' => true]);
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertOk();
});


test('compare table renders sponsor, name, description and year for each elephpant', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();
    $elephpant = Elephpant::factory()->create([
        'sponsor'     => 'Acme Corp',
        'name'        => 'Testy McTestface',
        'description' => 'A very particular elePHPant',
        'year'        => 2019,
        'image'       => '1-original-blue.jpg',
    ]);

    $html = $this->actingAs($user0)
        ->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertOk()
        ->getContent();

    expect($html)->toContain('Acme Corp')
        ->toContain('Testy McTestface')
        ->toContain('A very particular elePHPant')
        ->toContain('2019');
});

test('compare table shows each users own quantity independently', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();

    $sharedElephpant = Elephpant::factory()->create(['image' => '1-original-blue.jpg']);
    $user0Only = Elephpant::factory()->create(['image' => '1-original-blue.jpg']);

    $user0->elephpants()->attach($sharedElephpant->id, ['quantity' => 2]);
    $user1->elephpants()->attach($sharedElephpant->id, ['quantity' => 5]);
    $user0->elephpants()->attach($user0Only->id, ['quantity' => 1]);

    $html = $this->actingAs($user0)
        ->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertOk()
        ->getContent();

    expect($html)->toMatch('/<td[^>]*>\s*2\s*<\/td>/')
        ->toMatch('/<td[^>]*>\s*5\s*<\/td>/')
        ->and($html)->toMatch('/<td[^>]*text-zinc-300![^>]*>\s*0\s*<\/td>/');
});

test('compare table marks sponsor, name, description, year, and both user columns as sortable but not the image column', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();
    Elephpant::factory()->create(['image' => '1-original-blue.jpg']);

    $html = $this->actingAs($user0)
        ->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertOk()
        ->getContent();

    preg_match_all('/<button[^>]*data-flux-table-sortable[^>]*>/', $html, $sortButtonMatches);
    expect($sortButtonMatches[0])->toHaveCount(6);

    preg_match('/<thead.*?<\/thead>/s', $html, $matches);
    $headerRow = $matches[0];
    preg_match('/<th[^>]*>.*?Image.*?<\/th>/s', $headerRow, $imageHeaderMatch);

    expect($imageHeaderMatch[0])->not->toContain('<button');
});

test('compare page dims a quantity cell only when it is exactly zero', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();

    $owned = Elephpant::factory()->create(['name' => 'Owned One', 'image' => '1-original-blue.jpg']);
    $unowned = Elephpant::factory()->create(['name' => 'Unowned One', 'image' => '1-original-blue.jpg']);
    $twenty = Elephpant::factory()->create(['name' => 'Twenty Count', 'image' => '1-original-blue.jpg']);

    $user0->elephpants()->attach($owned->id, ['quantity' => 3]);
    $user0->elephpants()->attach($twenty->id, ['quantity' => 20]);

    $html = $this->actingAs($user0)
        ->get(route('herds.compare', [$user0->username, $user1->username]))
        ->assertOk()
        ->getContent();

    expect($html)->toMatch('/<td[^>]*>\s*3\s*<\/td>/')
        ->not->toMatch('/<td[^>]*text-zinc-300![^>]*>\s*3\s*<\/td>/')
        ->and($html)->toMatch('/<td[^>]*text-zinc-300![^>]*>\s*0\s*<\/td>/')
        ->and($html)->toMatch('/<td[^>]*>\s*20\s*<\/td>/')
        ->not->toMatch('/<td[^>]*text-zinc-300![^>]*>\s*20\s*<\/td>/');
});

test('compare page renders small thumbnails with a lightbox to view the full image', function (): void {
    $user0 = User::factory()->create();
    $user1 = User::factory()->create();
    $elephpant = Elephpant::factory()->create(['image' => '1-original-blue.jpg']);

    $user0->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($user0)->get(route('herds.compare', [$user0->username, $user1->username]));

    $response->assertOk()
        ->assertSee('storage/elephpants/1-original-blue.jpg', false)
        ->assertSee('width="40"', false)
        ->assertSee('height="40"', false)
        ->assertSee('data-elephpant-lightbox', false)
        ->assertSee('data-modal="elephpant-image"', false)
        ->assertSee('id="elephpant-lightbox-image"', false);
});
