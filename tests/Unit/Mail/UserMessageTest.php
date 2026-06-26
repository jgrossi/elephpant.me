<?php

declare(strict_types=1);

use App\Mail\UserMessage;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('UserMessage build returns mailable with subject and recipients', function (): void {
    $sender = User::factory()->create();
    $receiver = User::factory()->create();

    $mailable = new UserMessage($sender, $receiver, 'Test message');
    $built = $mailable->build();

    expect($built->subject)->toBe('New trade request');
});
