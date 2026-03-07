<?php

declare(strict_types=1);

use App\Elephpant;
use App\ElephpantUser;
use App\Observers\ElephpantUserObserver;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('ElephpantUser observer invokes delete when quantity is zero', function (): void {
    $user = User::factory()->create();
    $elephpant = Elephpant::factory()->create();
    $user->elephpants()->attach($elephpant->id, ['quantity' => 1]);

    $pivot = ElephpantUser::where('user_id', $user->id)->where('elephpant_id', $elephpant->id)->first();
    $pivot->quantity = 0;

    (new ElephpantUserObserver())->saved($pivot);

    expect($pivot->exists)->toBeFalse();
});
