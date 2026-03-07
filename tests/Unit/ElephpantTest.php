<?php

use App\Elephpant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('elephpant has users relation', function () {
    $elephpant = Elephpant::factory()->create();

    expect($elephpant->users())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
});

test('scopeFilter filters by name', function () {
    Elephpant::factory()->create(['name' => 'Blue Elephant']);
    Elephpant::factory()->create(['name' => 'Red Elephant']);
    $request = new \Illuminate\Http\Request(['q' => 'Blue']);

    $result = Elephpant::query()->filter($request)->get();

    expect($result)->toHaveCount(1)
        ->and($result->first()->name)->toBe('Blue Elephant');
});
