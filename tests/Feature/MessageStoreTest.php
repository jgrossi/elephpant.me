<?php

declare(strict_types=1);

use App\User;
use Illuminate\Support\Facades\Mail;

test('message store creates message and returns 204', function (): void {
    Mail::fake();
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $response = $this->actingAs($sender)->postJson(route('messages.store'), [
        'receiver_id' => $receiver->id,
        'message' => 'Hello from test',
    ]);

    $response->assertStatus(204);

    $this->assertDatabaseHas('messages', [
        'sender_id' => $sender->id,
        'receiver_id' => $receiver->id,
        'message' => 'Hello from test',
    ]);
});
