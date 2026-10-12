<?php

declare(strict_types=1);

use App\Elephpant;
use App\Format;
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

test('species search catalog mode with limit shows latest species only', function (): void {
    Elephpant::factory()->count(15)->create();

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'catalog', 'limit' => 12]);
    $view = $component->instance()->render();
    $data = $view->getData();

    expect($data['elephpants'])->toHaveCount(12)
        ->and($data['isCatalogPreview'])->toBeTrue()
        ->and($data['catalogTotal'])->toBe(15);
});

test('species search catalog mode searches full catalog when limited and q is set', function (): void {
    Elephpant::factory()->create(['name' => 'Ancient Alpha', 'year' => 2010]);
    Elephpant::factory()->count(14)->create(['year' => 2025]);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'catalog', 'limit' => 12])
        ->set('q', 'Ancient');

    $view = $component->instance()->render();

    expect($view->getData()['elephpants'])->toHaveCount(1)
        ->and($view->getData()['isCatalogPreview'])->toBeFalse();
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

test('species search herd mode increment updates quantity and dispatches refreshStats', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])
        ->call('incrementQuantity', $elephpant->id)
        ->assertDispatched('refreshStats');

    $user->refresh();
    $pivot = $user->elephpants()->where('elephpant_id', $elephpant->id)->first();
    expect($pivot)->not->toBeNull();
    expect($pivot->pivot->quantity)->toBe(1);
});

test('species search herd mode decrement updates quantity when above zero', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 2]);
    $this->actingAs($user);

    Livewire::test(SpeciesSearch::class, ['mode' => 'herd', 'userElephpants' => [$elephpant->id => 2]])
        ->call('decrementQuantity', $elephpant->id)
        ->assertDispatched('refreshStats');

    $user->refresh();
    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->first()->pivot->quantity)->toBe(1);
});

test('species search herd mode decrement does nothing when quantity is zero', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])
        ->call('decrementQuantity', $elephpant->id)
        ->assertNotDispatched('refreshStats');
});

test('species search herd mode accepts userElephpants and totalSpecies from mount', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, [
        'mode'           => 'herd',
        'userElephpants' => [$elephpant->id => 2],
        'totalSpecies'   => 42,
    ]);

    $component->assertSet('userElephpants', [$elephpant->id => 2])
        ->assertSet('totalSpecies', 42);
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

    $component->assertSet('collectedSpecies', 1);
    expect($component->instance()->render()->getData()['totalSpecies'])->toBeGreaterThan(0);
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

test('species search herd mode filters by year, color, size and ownership', function (string $property, array $values, array $expected): void {
    $user = User::factory()->create();
    $owned = Elephpant::factory()->create(['name' => 'Owned Blue Small', 'year' => 2024, 'color' => 'Blue', 'format' => Format::Small]);
    Elephpant::factory()->create(['name' => 'Red Large', 'year' => 2023, 'color' => 'Red', 'format' => Format::Large]);
    Elephpant::factory()->create(['name' => 'Green Small', 'year' => 2022, 'color' => 'Green', 'format' => Format::Small]);
    $user->elephpants()->attach($owned->id, ['quantity' => 1]);
    $this->actingAs($user);

    $grouped = Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])
        ->set($property, $values)
        ->instance()->render()->getData()['elephpantsGrouped'];

    expect($grouped->flatten()->pluck('name')->sort()->values()->all())->toBe($expected);
})->with([
    'single year'      => ['years', ['2024'], ['Owned Blue Small']],
    'multiple years'   => ['years', ['2024', '2022'], ['Green Small', 'Owned Blue Small']],
    'colors'           => ['colors', ['Red', 'Green'], ['Green Small', 'Red Large']],
    'size'             => ['sizes', ['large'], ['Red Large']],
    'owned'            => ['ownership', ['owned'], ['Owned Blue Small']],
    'not owned'        => ['ownership', ['not-owned'], ['Green Small', 'Red Large']],
    'owned and not'    => ['ownership', ['owned', 'not-owned'], ['Green Small', 'Owned Blue Small', 'Red Large']],
    'no filter values' => ['years', [], ['Green Small', 'Owned Blue Small', 'Red Large']],
]);

test('species search herd mode combines filters across types', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['name' => 'Match', 'year' => 2024, 'color' => 'Blue', 'format' => Format::Small]);
    Elephpant::factory()->create(['name' => 'Wrong Color', 'year' => 2024, 'color' => 'Red', 'format' => Format::Small]);
    Elephpant::factory()->create(['name' => 'Wrong Size', 'year' => 2024, 'color' => 'Blue', 'format' => Format::Large]);
    $this->actingAs($user);

    $grouped = Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])
        ->set('years', ['2024'])
        ->set('colors', ['Blue'])
        ->set('sizes', ['small'])
        ->set('ownership', ['not-owned'])
        ->instance()->render()->getData()['elephpantsGrouped'];

    expect($grouped->flatten()->pluck('name')->all())->toBe(['Match']);
});

test('species search herd mode renders filter dropdowns with options', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['year' => 2019, 'color' => 'Purple']);
    Elephpant::factory()->create(['year' => 2021, 'color' => '']);
    $this->actingAs($user);

    $component = Livewire::withoutLazyLoading()->test(SpeciesSearch::class, ['mode' => 'herd']);

    $component->assertSee(['Year', 'Color', 'Size', 'Owned', 'Not owned', '2019', '2021', 'Purple', 'Large', 'Small'])
        ->assertDontSee('Clear filters');
    expect($component->instance()->availableColors)->toBe(['', 'Purple']);
});

test('species search clearFilters resets all filters', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])
        ->set('years', ['2024'])
        ->set('colors', ['Blue'])
        ->set('sizes', ['large'])
        ->set('ownership', ['owned'])
        ->assertSee('Clear filters')
        ->call('clearFilters')
        ->assertSet('years', [])
        ->assertSet('colors', [])
        ->assertSet('sizes', [])
        ->assertSet('ownership', []);
});

test('species search herd mode shows a loading state while filters update', function (): void {
    $this->actingAs(User::factory()->create());

    Livewire::withoutLazyLoading()->test(SpeciesSearch::class, ['mode' => 'herd'])
        ->assertSeeHtml('wire:loading.flex wire:target="q, years, colors, sizes, ownership, clearFilters"')
        ->assertSeeHtml('wire:loading.class="opacity-50 pointer-events-none"')
        ->assertSee('Updating results…');
});

test('species search facet counts reflect search and other filters but not their own', function (): void {
    $user = User::factory()->create();
    $owned = Elephpant::factory()->create(['name' => 'Alpha', 'year' => 2024, 'color' => 'Black', 'format' => Format::Large]);
    Elephpant::factory()->create(['name' => 'Beta', 'year' => 2024, 'color' => 'Black', 'format' => Format::Large]);
    Elephpant::factory()->create(['name' => 'Gamma', 'year' => 2023, 'color' => 'Blue', 'format' => Format::Large]);
    Elephpant::factory()->create(['name' => 'Delta', 'year' => 2023, 'color' => 'Black', 'format' => Format::Small]);
    $user->elephpants()->attach($owned->id, ['quantity' => 1]);
    $this->actingAs($user);

    $component = Livewire::test(SpeciesSearch::class, ['mode' => 'herd'])->set('sizes', ['large']);
    $counts = $component->instance()->facetCounts;

    expect($counts['colors'])->toEqual(['Black' => 2, 'Blue' => 1])
        ->and($counts['years'])->toEqual(['2024' => 2, '2023' => 1])
        ->and($counts['sizes'])->toEqual(['large' => 3, 'small' => 1])
        ->and($counts['ownership'])->toEqual(['owned' => 1, 'not-owned' => 2]);

    $counts = $component->set('q', 'Alpha')->instance()->facetCounts;

    expect($counts['colors'])->toEqual(['Black' => 1])
        ->and($counts['sizes'])->toEqual(['large' => 1]);
});

test('species search disables filter options with no matches unless selected', function (): void {
    $user = User::factory()->create();
    Elephpant::factory()->create(['year' => 2024, 'color' => 'Black', 'format' => Format::Large]);
    Elephpant::factory()->create(['year' => 2023, 'color' => 'Brown', 'format' => Format::Small]);
    $this->actingAs($user);

    $html = Livewire::withoutLazyLoading()->test(SpeciesSearch::class, ['mode' => 'herd'])
        ->set('sizes', ['large'])
        ->set('colors', ['Brown'])
        ->html();

    $checkbox = function (string $value) use ($html): string {
        preg_match('/<ui-menu-checkbox[^>]*value="'.$value.'"[^>]*>.*?<\/ui-menu-checkbox>/s', $html, $matches);

        return $matches[0] ?? '';
    };

    expect($checkbox('Black'))->toContain('Black (1)')->not->toContain('disabled="disabled"')
        ->and($checkbox('Brown'))->toContain('Brown (0)')->not->toContain('disabled="disabled"')
        ->and($checkbox('2023'))->toContain('2023 (0)')->toContain('disabled="disabled"')
        ->and($checkbox('2024'))->toContain('2024 (0)')->toContain('disabled="disabled"');
});

test('herd species found counts follow the filters', function (): void {
    $user = User::factory()->create();
    $ownedIn2024 = Elephpant::factory()->create(['year' => 2024]);
    Elephpant::factory()->create(['year' => 2024]);
    $ownedIn2023 = Elephpant::factory()->create(['year' => 2023]);
    $this->actingAs($user);

    $component = Livewire::withoutLazyLoading()->test(SpeciesSearch::class, [
        'mode'           => 'herd',
        'userElephpants' => [$ownedIn2024->id => 1, $ownedIn2023->id => 1],
        'totalSpecies'   => 3,
    ]);
    $html = fn (): string => $component->html();

    expect($html())->toContain('Species Found: 2 of 3');

    $component->set('years', ['2024']);

    expect($html())->toContain('Species Found: 1 of 2')
        ->and($html())->not->toContain('Species Found: 2 of 3');
});
