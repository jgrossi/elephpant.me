<?php

declare(strict_types=1);

use App\Livewire\ConversationThread;
use App\Message;
use App\User;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

test('conversation thread send creates a message and shows it in the thread', function (): void {
    Mail::fake();
    Gravatar::shouldReceive('exists')->andReturn(false);

    $sender = User::factory()->create(['x_handle' => null]);
    $receiver = User::factory()->create(['x_handle' => null, 'name' => 'Ada Collector']);
    $this->actingAs($sender);

    Livewire::test(ConversationThread::class, [
        'otherUser'  => $receiver,
        'dateFormat' => 'j F Y H:i',
    ])
        ->assertSeeHtml('disabled')
        ->set('body', 'Shall we trade an elePHPant?')
        ->call('send')
        ->assertSet('body', '')
        ->assertSee('Shall we trade an elePHPant?')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('messages', [
        'sender_id'   => $sender->id,
        'receiver_id' => $receiver->id,
        'message'     => 'Shall we trade an elePHPant?',
    ]);

    Mail::assertQueued(\App\Mail\UserMessage::class);
});

test('conversation thread send requires a message body', function (): void {
    $sender = User::factory()->create(['x_handle' => null]);
    $receiver = User::factory()->create(['x_handle' => null]);
    $this->actingAs($sender);

    Livewire::test(ConversationThread::class, [
        'otherUser'  => $receiver,
        'dateFormat' => 'j F Y H:i',
    ])
        ->set('body', '')
        ->call('send')
        ->assertHasErrors(['body']);

    expect(Message::query()->count())->toBe(0);
});
