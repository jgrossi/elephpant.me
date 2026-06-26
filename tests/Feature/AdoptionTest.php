<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('adoption update with valid quantity returns 204 and updates db', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();

    $response = $this->actingAs($user)->putJson(route('adoptions.update', ['elephpant' => $elephpant->id]), [
        'quantity' => 2,
    ]);

    $response->assertStatus(204);

    $user->refresh();
    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->first()->pivot->quantity)->toBe(2);
});

test('adoption update with zero quantity detaches elephpant', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->actingAs($user)->putJson(route('adoptions.update', ['elephpant' => $elephpant->id]), [
        'quantity' => 0,
    ]);

    $response->assertStatus(204);

    $user->refresh();
    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->exists())->toBeFalse();
});
