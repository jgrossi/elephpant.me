<?php

declare(strict_types=1);

use App\Elephpant;
use App\User;

test('api elephpants index exposes owners, copies, ownership_percentage and updated_at', function (): void {
    $elephpant = Elephpant::factory()->create();
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $owner->elephpants()->attach($elephpant->id, ['quantity' => 3]);
    $other->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.elephpants.index'));

    $response->assertOk();
    $response->assertJsonPath('data.0.owners', 2);
    $response->assertJsonPath('data.0.copies', 4);
    expect((float) $response->json('data.0.ownership_percentage'))->toBe(100.0);
    $response->assertJsonPath('data.0.updated_at', $elephpant->fresh()->updated_at->toIso8601String());
});

test('api elephpants show exposes owners, copies, ownership_percentage and updated_at', function (): void {
    $elephpant = Elephpant::factory()->create();
    $otherElephpant = Elephpant::factory()->create();
    $owner = User::factory()->create();
    $otherCollector = User::factory()->create();

    $owner->elephpants()->attach($elephpant->id, ['quantity' => 2]);
    $otherCollector->elephpants()->attach($otherElephpant->id, ['quantity' => 1]);

    $response = $this->getJson(route('api.elephpants.show', $elephpant->id));

    $response->assertOk();
    $response->assertJsonPath('data.owners', 1);
    $response->assertJsonPath('data.copies', 2);
    expect((float) $response->json('data.ownership_percentage'))->toBe(50.0);
});

test('api elephpants endpoint reports zero copies and ownership for an unowned elephpant', function (): void {
    $elephpant = Elephpant::factory()->create();

    $response = $this->getJson(route('api.elephpants.show', $elephpant->id));

    $response->assertOk();
    $response->assertJsonPath('data.owners', 0);
    $response->assertJsonPath('data.copies', 0);
    expect((float) $response->json('data.ownership_percentage'))->toBe(0.0);
});
