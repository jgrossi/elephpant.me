<?php

declare(strict_types=1);

use App\Livewire\TradeMessage;
use App\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('trade message send validates and creates message', function (): void {
    Mail::fake();
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($sender);

    Livewire::test(TradeMessage::class, ['receiverId' => $receiver->id])
        ->set('message', 'Hello, trade?')
        ->call('send')
        ->assertSet('sent', true)
        ->assertSet('message', '');

    $this->assertDatabaseHas('messages', [
        'sender_id'   => $sender->id,
        'receiver_id' => $receiver->id,
        'message'     => 'Hello, trade?',
    ]);
});

test('trade message send requires message', function (): void {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    $this->actingAs($sender);

    Livewire::test(TradeMessage::class, ['receiverId' => $receiver->id])
        ->set('message', '')
        ->call('send')
        ->assertHasErrors(['message']);
});
