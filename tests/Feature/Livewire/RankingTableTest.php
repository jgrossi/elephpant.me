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


test('ranking country filter only includes countries with ranked collectors', function (): void {
    \App\User::factory()->create(['country_code' => 'USA']);
    $collector = \App\User::factory()->create(['country_code' => 'GBR']);
    $elephpant = \App\Elephpant::factory()->create();
    $collector->elephpants()->attach($elephpant->id, ['quantity' => 1]);
    $component = Livewire::test(RankingTable::class);
    expect($component->get('countries'))->toHaveKey('GBR')->not->toHaveKey('USA');
});
