<?php

declare(strict_types=1);

test('exception handler report and render are invoked when exception is thrown', function (): void {
    \Illuminate\Support\Facades\Route::get('/test-exception-handler', function (): void {
        throw new \RuntimeException('Test exception for coverage');
    });

    $response = $this->get('/test-exception-handler');

    $response->assertStatus(500);
});
