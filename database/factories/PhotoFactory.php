<?php

namespace Database\Factories;

use App\Photo;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhotoFactory extends Factory
{
    protected $model = Photo::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'url' => $this->faker->url,
        ];
    }
}
