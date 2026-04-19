<?php

declare(strict_types=1);

use App\Elephpant;
use App\Livewire\HerdStats;
use App\User;
use Livewire\Livewire;

test('herd stats refresh loads from db when no payload', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 2]);
    $this->actingAs($user);

    $component = Livewire::test(HerdStats::class);

    $component->assertSet('unique', 1);
    $component->assertSet('total', 2);
    $component->assertSet('double', 1);
});

test('herd stats refresh sets from payload when provided', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(HerdStats::class)
        ->call('refresh', [
            'unique' => 5,
            'total'  => 10,
            'double' => 5,
        ])
        ->assertSet('unique', 5)
        ->assertSet('total', 10)
        ->assertSet('double', 5);
});
