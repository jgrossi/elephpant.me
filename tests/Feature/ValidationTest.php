<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('adoption update validates quantity required', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();

    $response = $this->actingAs($user)->putJson(route('adoptions.update', ['elephpant' => $elephpant->id]), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['quantity']);
});

test('message store validates receiver_id and message', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('messages.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['receiver_id', 'message']);
});
