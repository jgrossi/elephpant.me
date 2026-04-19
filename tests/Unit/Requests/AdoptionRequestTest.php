<?php

declare(strict_types=1);

use App\Http\Requests\AdoptionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('AdoptionRequest allows valid quantity', function (): void {
    $request = new AdoptionRequest();
    $request->merge(['quantity' => 2]);

    $rules = $request->rules();

    expect($rules)->toHaveKey('quantity');
    expect($rules['quantity'])->toContain('required');
});

test('AdoptionRequest authorize returns true', function (): void {
    $request = new AdoptionRequest();

    expect($request->authorize())->toBeTrue();
});
