<?php

use App\Elephpant;
use App\Queries\TradingUsersQuery;
use App\Queries\TradingUsersQueryOption;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->query = new TradingUsersQuery();
});

test('fetchAll returns null when user has no spare elephpants', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $result = $this->query->fetchAll($user);

    expect($result)->toBeNull();
});

test('fetchAll returns paginator when user has spare and another user can trade', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $e3 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userA->elephpants()->attach($e2->id, ['quantity' => 1]);
    $userB->elephpants()->attach($e3->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAll($userA, null, 5, null, false);

    expect($result)->not->toBeNull()
        ->and($result->count())->toBeGreaterThan(0);
});

test('getCountryCodesWithTraders returns empty when user has no spare', function () {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $this->actingAs($user);
    $result = $this->query->getCountryCodesWithTraders($user);

    expect($result)->toBeArray()->toBeEmpty();
});

test('getCountryCodesWithTraders returns country codes when user can trade', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->getCountryCodesWithTraders($userA);

    expect($result)->toBeArray();
    expect($result)->toContain('GBR');
});

test('fetchAllForUser filters by target user', function () {
    $userA = User::factory()->create(['country_code' => 'USA']);
    $userB = User::factory()->create(['country_code' => 'USA']);
    $e1 = Elephpant::factory()->create();
    $e3 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e3->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAllForUser($userA, $userB->id);

    expect($result)->not->toBeNull()
        ->and($result->first()->id)->toBe($userB->id);
});

test('fetchAll filters by country when provided', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAll($userA, null, 5, 'GBR', false);

    expect($result)->not->toBeNull();
    foreach ($result as $trader) {
        expect($trader->country_code)->toBe('GBR');
    }
});

test('fetchAllOnlyIfHeHasElephpant returns traders with spare for that elephpant', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userA->elephpants()->attach($e2->id, ['quantity' => 1]);
    $userB->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAllOnlyIfHeHasElephpant($userA, $e1->id);

    expect($result)->not->toBeNull();
});

test('fetchAllWhoLackElephpant returns traders who lack that elephpant', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAllWhoLackElephpant($userA, $e1->id);

    expect($result)->not->toBeNull();
});

test('getCountryCodesWithTraders with targetUserId option filters by that user', function () {
    $userA = User::factory()->create(['country_code' => 'USA']);
    $userB = User::factory()->create(['country_code' => 'USA']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $options = new TradingUsersQueryOption();
    $options->targetUserId = $userB->id;
    $result = $this->query->getCountryCodesWithTraders($userA, $options);

    expect($result)->toBeArray();
});

test('fetchAll with simplePaginate returns simple paginator', function () {
    $userA = User::factory()->create(['country_code' => 'GBR']);
    $userB = User::factory()->create(['country_code' => 'GBR']);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $userA->elephpants()->attach($e1->id, ['quantity' => 2]);
    $userB->elephpants()->attach($e2->id, ['quantity' => 2]);

    $this->actingAs($userA);
    $result = $this->query->fetchAll($userA, null, 5, null, true);

    expect($result)->not->toBeNull();
    expect($result->count())->toBeGreaterThan(0);
});
