<?php

declare(strict_types=1);

use App\Elephpant;
use App\Queries\RankedUsersQuery;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function (): void {
    $this->query = new RankedUsersQuery();
});

test('fetchAll returns users ordered by unique then total', function (): void {
    $user1 = User::factory()->create(['country_code' => 'GBR']);
    $user1->update(['is_public' => true]);
    $user2 = User::factory()->create(['country_code' => 'GBR']);
    $user2->update(['is_public' => true]);
    $e1 = Elephpant::factory()->create();
    $e2 = Elephpant::factory()->create();
    $e3 = Elephpant::factory()->create();
    $user1->elephpants()->attach([$e1->id => ['quantity' => 1], $e2->id => ['quantity' => 2]]);
    $user2->elephpants()->attach([$e1->id => ['quantity' => 3]]);

    $result = $this->query->fetchAll(null);

    expect($result)->toHaveCount(2);
    expect($result->first()->elephpants_unique)->toBe(2);
    expect($result->first()->elephpants_total)->toBe(3);
    expect($result->last()->elephpants_unique)->toBe(1);
    expect($result->last()->elephpants_total)->toBe(3);
});

test('fetchAll filters by country when provided', function (): void {
    $userGbr = User::factory()->create(['country_code' => 'GBR']);
    $userGbr->update(['is_public' => true]);
    $userUsa = User::factory()->create(['country_code' => 'USA']);
    $userUsa->update(['is_public' => true]);
    $e = Elephpant::factory()->create();
    $userGbr->elephpants()->attach($e->id, ['quantity' => 1]);
    $userUsa->elephpants()->attach($e->id, ['quantity' => 1]);

    $result = $this->query->fetchAll('GBR');

    expect($result)->toHaveCount(1);
    expect($result->first()->country_code)->toBe('GBR');
});

test('fetchAll excludes non public users', function (): void {
    $publicUser = User::factory()->create();
    $publicUser->update(['is_public' => true]);
    $privateUser = User::factory()->create();
    $privateUser->is_public = false;
    $privateUser->save();
    $e = Elephpant::factory()->create();
    $publicUser->elephpants()->attach($e->id, ['quantity' => 1]);
    $privateUser->elephpants()->attach($e->id, ['quantity' => 1]);

    $result = $this->query->fetchAll(null);

    expect($result)->toHaveCount(1);
    expect($result->first()->id)->toBe($publicUser->id);
});
