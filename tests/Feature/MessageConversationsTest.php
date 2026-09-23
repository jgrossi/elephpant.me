<?php

declare(strict_types=1);

use App\Message;
use App\User;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('conversations page lists the latest message per partner without remote gravatar lookups', function (): void {
    // Layout may check Gravatar once for the authenticated user's profile avatar.
    Gravatar::shouldReceive('exists')->once()->andReturn(false);
    Gravatar::shouldReceive('get')->never();

    $user = User::factory()->create(['x_handle' => null]);
    $partners = User::factory()->count(5)->create(['x_handle' => null]);

    foreach ($partners as $index => $partner) {
        Message::query()->create([
            'sender_id'   => $user->id,
            'receiver_id' => $partner->id,
            'message'     => "Hello partner {$index}",
        ]);
    }

    $response = $this->actingAs($user)->get(route('messages.conversations'));

    $response->assertSuccessful()
        ->assertSee('Browse your conversations with other collectors.');

    foreach ($partners as $index => $partner) {
        $response->assertSee($partner->name);
        $response->assertSee("Hello partner {$index}");
    }
});

test('conversations page shows message count, started date, and last message date', function (): void {
    Gravatar::shouldReceive('exists')->once()->andReturn(false);

    $user = User::factory()->create([
        'x_handle'     => null,
        'country_code' => 'GBR',
    ]);
    $partner = User::factory()->create(['x_handle' => null, 'name' => 'Ada Collector']);

    Carbon::setTestNow('2024-01-01 10:00:00');
    Message::query()->create([
        'sender_id'   => $user->id,
        'receiver_id' => $partner->id,
        'message'     => 'First hello',
    ]);

    Carbon::setTestNow('2024-06-15 12:30:00');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'Middle hello',
    ]);

    Carbon::setTestNow('2024-09-01 08:15:00');
    Message::query()->create([
        'sender_id'   => $user->id,
        'receiver_id' => $partner->id,
        'message'     => 'Latest hello',
    ]);

    Carbon::setTestNow();

    $this->actingAs($user)
        ->get(route('messages.conversations'))
        ->assertSuccessful()
        ->assertSee('Ada Collector')
        ->assertSee('Latest hello')
        ->assertSee('3 messages')
        ->assertSee('Started:')
        ->assertSee('1 January 2024 10:00')
        ->assertSee('Last message:')
        ->assertSee('1 September 2024 08:15');
});

test('conversations page shows an empty state when the user has no messages', function (): void {
    Gravatar::shouldReceive('exists')->once()->andReturn(false);

    $user = User::factory()->create(['x_handle' => null]);

    $this->actingAs($user)
        ->get(route('messages.conversations'))
        ->assertSuccessful()
        ->assertSee("You don't have any messages yet.", false);
});

test('conversation page shows the other user with profile-style heading', function (): void {
    Gravatar::shouldReceive('exists')->andReturn(false);

    $user = User::factory()->create(['x_handle' => null]);
    $partner = User::factory()->create([
        'x_handle' => null,
        'name'     => 'Ada Collector',
        'username' => 'ada-collector',
    ]);

    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'Shall we trade?',
    ]);

    $this->actingAs($user)
        ->get(route('messages.conversation', $partner->username))
        ->assertSuccessful()
        ->assertSee('Ada Collector')
        ->assertSee('Shall we trade?')
        ->assertSee(route('herds.show', $partner->username), false);
});

test('conversation page shows name once for consecutive messages from the same sender', function (): void {
    Gravatar::shouldReceive('exists')->andReturn(false);

    $user = User::factory()->create(['x_handle' => null, 'name' => 'Test User']);
    $partner = User::factory()->create([
        'x_handle' => null,
        'name'     => 'Ada Collector',
        'username' => 'ada-collector',
    ]);

    Carbon::setTestNow('2024-09-01 10:00:00');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'First from Ada',
    ]);

    Carbon::setTestNow('2024-09-01 10:00:20');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'Second from Ada',
    ]);

    Carbon::setTestNow('2024-09-01 10:00:40');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'Third from Ada',
    ]);

    Carbon::setTestNow();

    $html = $this->actingAs($user)
        ->get(route('messages.conversation', $partner->username))
        ->assertSuccessful()
        ->assertSee('First from Ada')
        ->assertSee('Second from Ada')
        ->assertSee('Third from Ada')
        ->getContent();

    // Once in the profile header, once above the grouped messages.
    expect(substr_count($html, '>Ada Collector<'))->toBe(2);
});

test('conversation page starts a new group when the same sender messages in a different minute', function (): void {
    Gravatar::shouldReceive('exists')->andReturn(false);

    $user = User::factory()->create([
        'x_handle'     => null,
        'name'         => 'Test User',
        'country_code' => 'GBR',
    ]);
    $partner = User::factory()->create([
        'x_handle' => null,
        'name'     => 'Ada Collector',
        'username' => 'ada-collector',
    ]);

    Carbon::setTestNow('2024-09-01 10:00:00');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'First minute message',
    ]);

    Carbon::setTestNow('2024-09-01 10:01:00');
    Message::query()->create([
        'sender_id'   => $partner->id,
        'receiver_id' => $user->id,
        'message'     => 'Next minute message',
    ]);

    Carbon::setTestNow();

    $html = $this->actingAs($user)
        ->get(route('messages.conversation', $partner->username))
        ->assertSuccessful()
        ->assertSee('First minute message')
        ->assertSee('Next minute message')
        ->assertSee('1 September 2024 10:00')
        ->assertSee('1 September 2024 10:01')
        ->getContent();

    // Profile header once, plus once per minute-split group.
    expect(substr_count($html, '>Ada Collector<'))->toBe(3);
});
