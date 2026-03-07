<?php

declare(strict_types=1);

use App\Elephpant;
use App\Livewire\HerdCounter;
use App\User;
use Livewire\Livewire;

test('herd counter increment updates quantity and dispatches refreshStats', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(HerdCounter::class, ['elephpantId' => $elephpant->id, 'quantity' => 0]);
    $component->call('increment');
    $component->assertDispatched('refreshStats');

    $user->refresh();
    $pivot = $user->elephpants()->where('elephpant_id', $elephpant->id)->first();
    expect($pivot)->not->toBeNull();
    expect($pivot->pivot->quantity)->toBe(1);
});

test('herd counter decrement updates quantity when above zero', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 2]);
    $this->actingAs($user);

    Livewire::test(HerdCounter::class, ['elephpantId' => $elephpant->id, 'quantity' => 2])
        ->call('decrement')
        ->assertDispatched('refreshStats');

    $user->refresh();
    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->first()->pivot->quantity)->toBe(1);
});

test('herd counter decrement does nothing when quantity is zero', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    Livewire::test(HerdCounter::class, ['elephpantId' => $elephpant->id, 'quantity' => 0])
        ->call('decrement')
        ->assertNotDispatched('refreshStats');
});

test('herd counter placeholder returns view', function (): void {
    $component = Livewire::test(HerdCounter::class, ['elephpantId' => 1, 'quantity' => 0]);
    $placeholder = $component->instance()->placeholder();

    expect($placeholder)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
});

test('herd counter updatedQuantity saves and dispatches when quantity is set', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(HerdCounter::class, ['elephpantId' => $elephpant->id, 'quantity' => 0]);
    $component->set('quantity', 3);
    $component->assertDispatched('refreshStats');

    $user->refresh();
    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->first()->pivot->quantity)->toBe(3);
});
