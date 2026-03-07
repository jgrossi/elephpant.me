<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'username' => Str::slug($name).$this->faker->unique()->numberBetween(1, 99999),
            'twitter' => $this->faker->userName,
            'country_code' => $this->faker->randomElement(['NLD', 'USA', 'BRA', 'GBR']),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'mastodon' => '@'.$this->faker->userName.'@elephpant.me',
        ];
    }
}
