<?php

declare(strict_types=1);

use App\Providers\BroadcastServiceProvider;

uses(Tests\TestCase::class);

test('broadcast service provider boot runs without exception', function (): void {
    $provider = new BroadcastServiceProvider($this->app);
    $provider->boot();

    expect(true)->toBeTrue();
});
