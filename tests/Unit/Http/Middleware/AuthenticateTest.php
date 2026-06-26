<?php

declare(strict_types=1);

use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;

uses(Tests\TestCase::class);

test('authenticate redirectTo returns login route when request does not expect json', function (): void {
    $request = Request::create(route('herds.edit'), 'GET');
    $request->headers->set('Accept', 'text/html');

    $middleware = new Authenticate(app('auth'));
    $ref = new \ReflectionMethod($middleware, 'redirectTo');
    $ref->setAccessible(true);

    expect($ref->invoke($middleware, $request))->toBe(route('login'));
});

test('authenticate redirectTo returns null when request expects json', function (): void {
    $request = Request::create(route('herds.edit'), 'GET');
    $request->headers->set('Accept', 'application/json');

    $middleware = new Authenticate(app('auth'));
    $ref = new \ReflectionMethod($middleware, 'redirectTo');
    $ref->setAccessible(true);

    expect($ref->invoke($middleware, $request))->toBeNull();
});
