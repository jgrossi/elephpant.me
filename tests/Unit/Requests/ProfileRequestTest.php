<?php

declare(strict_types=1);

use App\Http\Requests\ProfileRequest;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('ProfileRequest has expected rules for account section', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = ProfileRequest::create(route('profile.update'), 'PUT', ['section' => 'account']);
    $request->setContainer(app());
    $request->setUserResolver(fn () => $user);

    $rules = $request->rules();

    expect($rules)->toHaveKey('name');
    expect($rules)->toHaveKey('email');
    expect($rules)->toHaveKey('country_code');
    expect($rules)->toHaveKey('username');
});

test('ProfileRequest has expected rules for password section', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = ProfileRequest::create(route('profile.update'), 'PUT', ['section' => 'password']);
    $request->setContainer(app());
    $request->setUserResolver(fn () => $user);

    $rules = $request->rules();

    expect($rules)->toHaveKey('password');
});

test('ProfileRequest has expected rules for public_profile section', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = ProfileRequest::create(route('profile.update'), 'PUT', ['section' => 'public_profile']);
    $request->setContainer(app());
    $request->setUserResolver(fn () => $user);

    $rules = $request->rules();

    expect($rules)->toHaveKey('twitter');
    expect($rules)->toHaveKey('mastodon');
});

test('ProfileRequest authorize returns true', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = new ProfileRequest();
    $request->setContainer(app());
    $request->setUserResolver(fn () => $user);

    expect($request->authorize())->toBeTrue();
});
