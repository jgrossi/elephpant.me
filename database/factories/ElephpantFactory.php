<?php

namespace Database\Factories;

use App\Elephpant;
use App\Format;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElephpantFactory extends Factory
{
    protected $model = Elephpant::class;

    public function definition(): array
    {
        return [
            'format'      => $this->faker->randomElement(Format::cases()),
            'name'        => $this->faker->firstName,
            'description' => $this->faker->sentence,
            'year'        => (int) $this->faker->dateTimeBetween('-12 years', 'now')->format('Y'),
            'color'       => $this->faker->colorName,
            'sponsor'     => $this->faker->company,
            'image'       => $this->faker->imageUrl(),
        ];
    }
}
