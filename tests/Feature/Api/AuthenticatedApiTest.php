<?php

declare(strict_types=1);

use App\Elephpant;
use App\Mail\UserMessage;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

// The test environment caches to files, so keep rate limits from leaking between runs.
beforeEach(function (): void {
    config(['cache.limiter' => 'array']);
});

test('token is created with valid credentials', function (): void {
    $user = User::factory()->create(['password' => bcrypt('secret-password')]);

    $response = $this->postJson(route('api.tokens.store'), [
        'username'    => $user->username,
        'password'    => 'secret-password',
        'device_name' => 'test',
    ]);

    $response->assertCreated()->assertJsonStructure(['token']);

    $this->withToken($response->json('token'))
        ->getJson(route('api.me.herd.show'))
        ->assertOk()
        ->assertJsonPath('username', $user->username);
});

test('token is refused with wrong password', function (): void {
    $user = User::factory()->create(['password' => bcrypt('secret-password')]);

    $this->postJson(route('api.tokens.store'), [
        'username'    => $user->username,
        'password'    => 'wrong',
        'device_name' => 'test',
    ])->assertUnprocessable()->assertJsonValidationErrors('username');

    expect($user->tokens()->count())->toBe(0);
});

test('token endpoint is throttled', function (): void {
    $user = User::factory()->create();
    $payload = ['username' => $user->username, 'password' => 'wrong', 'device_name' => 'test'];

    foreach (range(1, 5) as $ignored) {
        $this->postJson(route('api.tokens.store'), $payload)->assertUnprocessable();
    }

    $this->postJson(route('api.tokens.store'), $payload)->assertTooManyRequests();
});

test('authenticated endpoints return 401 without a token', function (string $method, string $route, array $parameters): void {
    $this->json($method, route($route, $parameters))->assertUnauthorized();
})->with([
    'own herd'   => ['GET', 'api.me.herd.show', []],
    'set amount' => ['PUT', 'api.me.herd.update', ['elephpant' => 1]],
    'messages'     => ['GET', 'api.messages.index', []],
    'conversation' => ['GET', 'api.messages.show', ['username' => 'someone']],
    'message'      => ['POST', 'api.messages.store', ['username' => 'someone']],
]);

test('own herd is returned also when private', function (): void {
    $user = User::factory()->create(['is_public' => false]);
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 3]);
    Sanctum::actingAs($user);

    $this->getJson(route('api.me.herd.show'))
        ->assertOk()
        ->assertJsonPath('stats.total', 3)
        ->assertJsonPath('elephpants.0.quantity', 3);
});

test('quantity in own herd can be set', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    Sanctum::actingAs($user);

    $this->putJson(route('api.me.herd.update', ['elephpant' => $elephpant->id]), ['quantity' => 2])
        ->assertNoContent();

    expect($user->elephpants()->where('elephpant_id', $elephpant->id)->first()->pivot->quantity)->toBe(2);
});

test('message can be sent', function (): void {
    Mail::fake();
    $sender = User::factory()->create();
    $receiver = User::factory()->create();
    Sanctum::actingAs($sender);

    $this->postJson(route('api.messages.store', ['username' => $receiver->username]), [
        'message' => 'Want to trade?',
    ])->assertNoContent();

    Mail::assertQueued(UserMessage::class);

    $this->assertDatabaseHas('messages', [
        'sender_id'   => $sender->id,
        'receiver_id' => $receiver->id,
        'message'     => 'Want to trade?',
    ]);
});

test('conversations and a conversation can be read', function (): void {
    $me = User::factory()->create();
    $other = User::factory()->create();
    $stranger = User::factory()->create();
    Message::query()->create(['sender_id' => $other->id, 'receiver_id' => $me->id, 'message' => 'Trade?']);
    Message::query()->create(['sender_id' => $me->id, 'receiver_id' => $other->id, 'message' => 'Sure!']);
    Message::query()->create(['sender_id' => $stranger->id, 'receiver_id' => $other->id, 'message' => 'Not for me']);
    Sanctum::actingAs($me);

    $this->getJson(route('api.messages.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.user.username', $other->username)
        ->assertJsonPath('data.0.message_count', 2)
        ->assertJsonPath('data.0.latest_message.message', 'Sure!');

    $this->getJson(route('api.messages.show', ['username' => $other->username]))
        ->assertOk()
        ->assertJsonPath('user.username', $other->username)
        ->assertJsonCount(2, 'messages')
        ->assertJsonPath('messages.0.from', $other->username)
        ->assertJsonPath('messages.1.message', 'Sure!');
});

test('message to an unknown username returns 404', function (): void {
    Sanctum::actingAs(User::factory()->create());

    $this->postJson(route('api.messages.store', ['username' => 'nobody']), ['message' => 'Hi'])
        ->assertNotFound();
});
