<?php

declare(strict_types=1);

use App\Livewire\HomeSearch;
use Livewire\Livewire;

test('home search renders', function (): void {
    $component = Livewire::test(HomeSearch::class);

    $component->assertStatus(200);
});

test('home search clearSearch resets q', function (): void {
    $component = Livewire::test(HomeSearch::class)
        ->set('q', 'test')
        ->call('clearSearch');

    $component->assertSet('q', '');
});
