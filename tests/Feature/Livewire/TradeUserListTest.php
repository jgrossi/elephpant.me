<?php

declare(strict_types=1);

use App\Livewire\TradeUserList;
use App\User;
use Livewire\Livewire;

test('trade user list renders with no users', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(TradeUserList::class, ['country' => null, 'countries' => []]);

    $component->assertStatus(200);
});

test('trade user list accepts country and countries', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(TradeUserList::class, [
        'country' => 'GBR',
        'countries' => ['GBR' => ['name' => 'United Kingdom', 'flag' => '🇬🇧']],
    ]);

    $component->assertSet('country', 'GBR');
    $component->assertSet('countries', ['GBR' => ['name' => 'United Kingdom', 'flag' => '🇬🇧']]);
});
