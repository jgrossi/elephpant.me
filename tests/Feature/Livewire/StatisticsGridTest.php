<?php

declare(strict_types=1);

use App\Livewire\StatisticsGrid;
use Livewire\Livewire;

test('statistics grid renders with count', function (): void {
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 0]);

    $component->assertStatus(200);
    $component->assertSet('nbUsersWithElephpant', 0);
});

test('statistics grid placeholder returns view', function (): void {
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 0]);
    $placeholder = $component->instance()->placeholder([]);

    expect($placeholder)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
});

test('statistics grid render method returns view with elephpants', function (): void {
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 0]);
    $view = $component->instance()->render();

    expect($view)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
    expect($view->getData())->toHaveKeys(['elephpants', 'currentUserElephpants', 'nbUsersWithElephpant']);
});

test('statistics grid render when not authenticated uses empty currentUserElephpants', function (): void {
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 0]);
    $view = $component->instance()->render();
    $data = $view->getData();

    expect($data['currentUserElephpants'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($data['currentUserElephpants']->isEmpty())->toBeTrue();
});

test('statistics grid mount sets nbUsersWithElephpant', function (): void {
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 42]);

    $component->assertSet('nbUsersWithElephpant', 42);
});

test('statistics grid render when authenticated includes current user elephpants', function (): void {
    $user = \App\User::factory()->create();
    $elephpant = \App\Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $this->actingAs($user);
    $component = Livewire::test(StatisticsGrid::class, ['nbUsersWithElephpant' => 0]);
    $view = $component->instance()->render();
    $data = $view->getData();

    expect($data['currentUserElephpants']->contains($elephpant->id))->toBeTrue();
});

test('statistics grid mount called directly sets nbUsersWithElephpant', function (): void {
    $component = app(StatisticsGrid::class);
    $component->mount(99);

    expect($component->nbUsersWithElephpant)->toBe(99);
});
