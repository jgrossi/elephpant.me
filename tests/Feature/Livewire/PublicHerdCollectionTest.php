<?php

declare(strict_types=1);

use App\Elephpant;
use App\Livewire\PublicHerdCollection;
use App\User;
use Livewire\Livewire;

test('public herd collection renders for existing user', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $component = Livewire::test(PublicHerdCollection::class, ['username' => $user->username]);

    $component->assertStatus(200)->assertSet('username', $user->username);
});

test('public herd collection placeholder returns view', function (): void {
    $component = Livewire::test(PublicHerdCollection::class, ['username' => 'any']);
    $placeholder = $component->instance()->placeholder([]);

    expect($placeholder)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
});

test('public herd collection render returns view with elephpants', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $component = Livewire::test(PublicHerdCollection::class, ['username' => $user->username]);
    $view = $component->instance()->render();

    expect($view->getData())->toHaveKey('elephpants');
});

test('public herd collection render throws when username does not exist', function (): void {
    $component = Livewire::test(PublicHerdCollection::class, ['username' => 'nonexistent-user-'.time()]);

    $component->instance()->render();
})->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

test('public herd collection mount sets username', function (): void {
    $component = app(PublicHerdCollection::class);
    $component->mount('johndoe');

    expect($component->username)->toBe('johndoe');
});

test('public herd collection is forbidden for a private herd', function (): void {
    $user = User::factory()->create(['is_public' => false]);

    Livewire::test(PublicHerdCollection::class, ['username' => $user->username])
        ->instance()
        ->render();
})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class, 'This herd is private');

test('public herd collection renders a private herd for its owner', function (): void {
    $user = User::factory()->create(['is_public' => false]);

    $view = Livewire::actingAs($user)
        ->test(PublicHerdCollection::class, ['username' => $user->username])
        ->instance()
        ->render();

    expect($view->getData())->toHaveKey('elephpants');
});

test('public herd collection shows a private notice to the owner', function (): void {
    $user = User::factory()->create(['is_public' => false]);

    expect(Livewire::actingAs($user)
        ->test(PublicHerdCollection::class, ['username' => $user->username])
        ->instance()
        ->render()
        ->render())
        ->toContain('Your herd is private')
        ->toContain('Only you can see its contents');
});

test('public herd collection does not show a private notice for a public herd', function (): void {
    $user = User::factory()->create(['is_public' => true]);

    expect(Livewire::actingAs($user)
        ->test(PublicHerdCollection::class, ['username' => $user->username])
        ->instance()
        ->render()
        ->render())
        ->not->toContain('Your herd is private');
});

test('guests do not see a compare to mine button on a herd page', function (): void {
    $user = User::factory()->create(['is_public' => true]);

    $this->get(route('herds.show', $user->username))
        ->assertOk()
        ->assertDontSee('Compare to my herd');
});

test('authenticated users see a compare to mine button on someone else\'s herd page', function (): void {
    $user = User::factory()->create(['is_public' => true]);
    $viewer = User::factory()->create();

    $html = $this->actingAs($viewer)
        ->get(route('herds.show', $user->username))
        ->assertOk()
        ->getContent();

    expect($html)->toContain('Compare to my herd')
        ->toContain(route('herds.compare', [$user->username, $viewer->username]));
});

test('authenticated users do not see a compare to mine button on their own herd page', function (): void {
    $user = User::factory()->create(['is_public' => true]);

    $this->actingAs($user)
        ->get(route('herds.show', $user->username))
        ->assertOk()
        ->assertDontSee('Compare to my herd');
});

test('clicking the compare to mine button leads to a working comparison page', function (): void {
    $user = User::factory()->create(['is_public' => true]);
    $viewer = User::factory()->create();

    $this->actingAs($viewer)
        ->get(route('herds.compare', [$user->username, $viewer->username]))
        ->assertOk();
});
