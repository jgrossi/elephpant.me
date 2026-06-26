<?php

declare(strict_types=1);

use App\Livewire\RankingTable;
use Livewire\Livewire;

test('ranking table renders', function (): void {
    $component = Livewire::test(RankingTable::class);

    $component->assertStatus(200);
});

test('ranking table selectCountry sets country', function (): void {
    $component = Livewire::test(RankingTable::class)
        ->call('selectCountry', 'GBR');

    $component->assertSet('country', 'GBR');
});
