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


test('ranking table shows double elephpant count between unique and total', function (): void {
    $user = \App\User::factory()->create(['country_code' => 'GBR']);
    $first = \App\Elephpant::factory()->create();
    $second = \App\Elephpant::factory()->create();
    $user->elephpants()->attach($first->id, ['quantity' => 2]);
    $user->elephpants()->attach($second->id, ['quantity' => 1]);
    Livewire::test(RankingTable::class)->assertSeeInOrder(['Unique', 'Double', 'Total'])->assertSeeInOrder(['2', '1', '3']);
});
