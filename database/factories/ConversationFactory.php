<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Conversation;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'other_user_id' => User::factory(),
        ];
    }
}