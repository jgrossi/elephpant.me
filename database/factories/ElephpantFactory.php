<?php

namespace Database\Factories;

use App\Elephpant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElephpantFactory extends Factory
{
    protected $model = Elephpant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'description' => $this->faker->sentence,
            'year' => (int) $this->faker->dateTimeBetween('-12 years', 'now')->format('Y'),
            'sponsor' => $this->faker->company,
            'image' => $this->faker->imageUrl(),
        ];
    }
}
