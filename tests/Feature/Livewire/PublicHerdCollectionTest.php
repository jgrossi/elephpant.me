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
