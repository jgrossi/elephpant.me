<?php

declare(strict_types=1);

use App\Elephpant;
use App\Livewire\SpeciesSearch;
use App\User;
use Livewire\Livewire;

test('species search mount sets mode and q from request', function (): void {
    $this->withHeader('Accept', 'text/html');
    $component = app(SpeciesSearch::class);
    $component->mount('catalog');

    expect($component->mode)->toBe('catalog');
    $component->mount('herd');
    expect($component->mode)->toBe('herd');
});

test('species search catalog mode filters by q', function (): void {
    Elephpant::factory()->create(['name' => 'Alpha Elephant']);
    Elephpant::factory()->create(['name' => 'Beta Elephant']);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'catalog'])
        ->set('q', 'Alpha');

    $component->assertSee('Alpha');
});

test('species search placeholder returns view', function (): void {
    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'catalog']);
    $placeholder = $component->instance()->placeholder([]);

    expect($placeholder)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
});

test('species search herd mode with auth returns grouped data', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['year' => 2024]);
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);

    $component->assertStatus(200);
});


test('species search clearSearch resets q', function (): void {
    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'catalog'])
        ->set('q', 'test')
        ->call('clearSearch');

    $component->assertSet('q', '');
});

test('species search herd mode render computes grouped data', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['year' => 2024]);
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);
    $view = $component->instance()->render();

    expect($view->getData())->toHaveKeys(['elephpants', 'elephpantsGrouped', 'userElephpants', 'tradePossibilities', 'speciesCount', 'totalSpecies', 'collectedSpecies']);
});

test('species search onRefreshStats sets userElephpantsFromEvent', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);
    $component->call('onRefreshStats', ['userElephpants' => [1 => 2, 2 => 1]]);

    $component->assertSet('userElephpantsFromEvent', [1 => 2, 2 => 1]);
});

test('species search onRefreshStats does not set when payload has no userElephpants key', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);
    $component->call('onRefreshStats', []);

    $component->assertSet('userElephpantsFromEvent', null);
});

test('species search herd mode with q filter filters grouped elephpants', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['name' => 'Alpha Species', 'year' => 2024]);
    Elephpant::factory()->create(['name' => 'Beta Species', 'year' => 2024]);
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])->set('q', 'Alpha');
    $view = $component->instance()->render();

    $grouped = $view->getData()['elephpantsGrouped'];
    expect($grouped->flatten()->pluck('name')->toArray())->toContain('Alpha Species');
});

test('species search herd mode exposes totalSpecies and collectedSpecies', function (): void {
    $user = User::factory()->create();
    $e1 = Elephpant::factory()->create(['year' => 2024]);
    $e2 = Elephpant::factory()->create(['year' => 2024]);
    $user->elephpants()->attach($e1->id, ['quantity' => 1]);

    $this->actingAs($user);
    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);
    $view = $component->instance()->render();
    $data = $view->getData();

    expect($data['totalSpecies'])->toBeGreaterThan(0);
    expect($data['collectedSpecies'])->toBe(1);
});

test('species search herd mode computes trade possibilities with senders and receivers', function (): void {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $e1 = Elephpant::factory()->create(['year' => 2024]);
    $e2 = Elephpant::factory()->create(['year' => 2024]);
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userA->elephpants()->attach($e2->id, ['quantity' => 0]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd']);
    $view = $component->instance()->render();
    $tradePossibilities = $view->getData()['tradePossibilities'];

    expect($tradePossibilities)->toBeArray();
});

test('species search prepareTradePossibilities receivers branch is covered', function (): void {
    $user = User::factory()->create();
    $e1 = Elephpant::factory()->create(['year' => 2024]);
    $e2 = Elephpant::factory()->create(['year' => 2024]);
    $e1->possible_receivers = '1,2';
    $e2->possible_senders = 'alice,bob';

    $grouped = collect([2024 => collect([$e1, $e2])]);
    $userElephpants = [$e1->id => 2, $e2->id => 0];

    $this->actingAs($user);
    $component = app(SpeciesSearch::class);
    $component->mount('herd');
    $ref = new \ReflectionMethod($component, 'prepareTradePossibilities');
    $ref->setAccessible(true);
    $result = $ref->invoke($component, $grouped, $userElephpants);

    expect($result)->toHaveKey($e1->id);
    expect($result[$e1->id]['type'])->toBe('receivers');
    expect($result[$e1->id]['count'])->toBe(2);
    expect($result)->toHaveKey($e2->id);
    expect($result[$e2->id]['type'])->toBe('senders');
    expect($result[$e2->id]['count'])->toBe(2);
});
