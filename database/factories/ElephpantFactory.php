<?php

namespace Database\Factories;

use App\Elephpant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Elephpant>
 */
class ElephpantFactory extends Factory
{
    protected $model = Elephpant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'popular_name' => fake()->name(),
            'year' => (int) fake()->dateTimeBetween('-12 years', 'now')->format('Y'),
            'sponsor' => fake()->company(),
            'image' => fake()->imageUrl(),
        ];
    }
}
