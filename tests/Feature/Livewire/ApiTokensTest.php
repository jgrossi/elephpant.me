<?php

declare(strict_types=1);

use App\Livewire\ApiTokens;
use App\User;
use Livewire\Livewire;

test('guests are redirected to login from the api tokens page', function (): void {
    $this->get(route('api-tokens.index'))->assertRedirect(route('login'));
});

test('api tokens page lists only your own tokens', function (): void {
    $user = User::factory()->create();
    $user->createToken('my-agent');
    User::factory()->create()->createToken('someone-elses-agent');

    $this->actingAs($user)
        ->get(route('api-tokens.index'))
        ->assertOk()
        ->assertSeeLivewire(ApiTokens::class)
        ->assertSee('my-agent')
        ->assertDontSee('someone-elses-agent');
});

test('a token can be created and is shown once', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $component = Livewire::test(ApiTokens::class)
        ->set('name', 'my-agent')
        ->call('create')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertSee('my-agent');

    $plainTextToken = $component->get('plainTextToken');

    expect($user->tokens()->pluck('name')->all())->toBe(['my-agent'])
        ->and($plainTextToken)->toBeString();

    $component->assertSee($plainTextToken);

    Livewire::test(ApiTokens::class)->assertDontSee($plainTextToken);

    $this->withToken($plainTextToken)
        ->getJson(route('api.me.herd.show'))
        ->assertOk()
        ->assertJsonPath('username', $user->username);
});

test('a token needs a name', function (): void {
    $this->actingAs(User::factory()->create());

    Livewire::test(ApiTokens::class)
        ->set('name', '')
        ->call('create')
        ->assertHasErrors(['name' => 'required']);
});

test('only your own token can be revoked', function (): void {
    $user = User::factory()->create();
    $own = $user->createToken('mine')->accessToken;
    $other = User::factory()->create()->createToken('theirs')->accessToken;
    $this->actingAs($user);

    Livewire::test(ApiTokens::class)
        ->call('revoke', $other->id)
        ->call('revoke', $own->id)
        ->assertDontSee('mine');

    $this->assertDatabaseMissing('personal_access_tokens', ['id' => $own->id]);
    $this->assertDatabaseHas('personal_access_tokens', ['id' => $other->id]);
});
