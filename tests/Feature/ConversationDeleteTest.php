<?php

declare(strict_types=1);

use App\Conversation;
use App\Message;
use App\User;
use Creativeorange\Gravatar\Facades\Gravatar;

test('conversation soft delete can be restored', function (): void {
    $conversation = Conversation::factory()->create();

    $conversation->delete();

    $this->assertSoftDeleted($conversation);
    expect($conversation->deleted_at)->not->toBeNull();

    $conversation->restore();

    expect($conversation->fresh()->deleted_at)->toBeNull();
});

test('user can delete their conversation without removing it for the other participant', function (): void {
    Gravatar::shouldReceive('exists')->andReturn(false);

    $user = User::factory()->create(['x_handle' => null]);
    $otherUser = User::factory()->create(['x_handle' => null, 'name' => 'Ada Collector']);
    $message = Message::query()->create([
        'sender_id'   => $user->id,
        'receiver_id' => $otherUser->id,
        'message'     => 'Shall we trade?',
    ]);

    $this->actingAs($user)
        ->delete(route('messages.conversations.destroy', $otherUser->username))
        ->assertRedirect(route('messages.conversations'))
        ->assertSessionHas('status', 'Conversation deleted.');

    $conversation = Conversation::withTrashed()->where('user_id', $user->id)->firstOrFail();
    $this->assertSoftDeleted($conversation);
    expect($user->conversations()->count())->toBe(0);
    $this->assertDatabaseHas('messages', ['id' => $message->id]);

    $this->actingAs($user)
        ->get(route('messages.conversations'))
        ->assertDontSee('Ada Collector');

    $this->actingAs($otherUser)
        ->get(route('messages.conversations'))
        ->assertSee('Ada Collector');
});

test('user cannot delete a conversation they did not participate in', function (): void {
    $firstUser = User::factory()->create();
    $otherUser = User::factory()->create();
    $outsider = User::factory()->create();

    Message::query()->create([
        'sender_id'   => $firstUser->id,
        'receiver_id' => $otherUser->id,
        'message'     => 'Private message',
    ]);

    $this->actingAs($outsider)
        ->delete(route('messages.conversations.destroy', $otherUser->username))
        ->assertForbidden();

    expect(Conversation::withTrashed()->count())->toBe(0);
});