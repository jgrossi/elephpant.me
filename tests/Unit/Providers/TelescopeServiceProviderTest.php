<?php

declare(strict_types=1);

use App\Providers\TelescopeServiceProvider;

uses(Tests\TestCase::class);

test('telescope service provider register runs without exception', function (): void {
    $provider = new TelescopeServiceProvider($this->app);
    $provider->register();

    expect(true)->toBeTrue();
});

test('telescope service provider hideSensitiveRequestDetails runs when not local', function (): void {
    $this->app['env'] = 'production';

    $provider = new TelescopeServiceProvider($this->app);
    $ref = new \ReflectionMethod($provider, 'hideSensitiveRequestDetails');
    $ref->setAccessible(true);
    $ref->invoke($provider);

    expect(true)->toBeTrue();
});

test('telescope service provider gate is invokable', function (): void {
    $provider = new TelescopeServiceProvider($this->app);
    $ref = new \ReflectionMethod($provider, 'gate');
    $ref->setAccessible(true);
    $ref->invoke($provider);

    expect(\Illuminate\Support\Facades\Gate::has('viewTelescope'))->toBeTrue();
});
