<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'x_handle' => fake()->userName(),
            'mastodon' => '@'.fake()->userName().'@elephpant.me',
            'bluesky' => '@'.fake()->userName().'.bsky.social',
            'country_code' => fake()->randomElement(['NLD', 'USA', 'BRA', 'FRA']),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
