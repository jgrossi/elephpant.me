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
