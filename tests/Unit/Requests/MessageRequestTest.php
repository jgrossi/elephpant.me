<?php

declare(strict_types=1);

use App\Http\Requests\MessageRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('MessageRequest has required receiver_id and message', function (): void {
    $request = new MessageRequest();

    $rules = $request->rules();

    expect($rules)->toHaveKey('receiver_id');
    expect($rules)->toHaveKey('message');
    expect($rules['receiver_id'])->toContain('required', 'exists:users,id');
    expect($rules['message'])->toContain('required');
});

test('MessageRequest authorize returns true', function (): void {
    $request = new MessageRequest();

    expect($request->authorize())->toBeTrue();
});
