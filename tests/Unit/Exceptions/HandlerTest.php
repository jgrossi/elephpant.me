<?php

declare(strict_types=1);

use App\Exceptions\Handler;
use Illuminate\Http\Request;

uses(Tests\TestCase::class);

test('handler report delegates to parent', function (): void {
    $handler = app(Handler::class);
    $e = new \RuntimeException('Test');

    $handler->report($e);

    expect(true)->toBeTrue();
});

test('handler render delegates to parent', function (): void {
    $handler = app(Handler::class);
    $request = Request::create('/test');
    $e = new \RuntimeException('Test');

    $response = $handler->render($request, $e);

    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\Response::class);
});
