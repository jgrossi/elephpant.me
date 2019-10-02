<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Elephpant;
use Faker\Generator as Faker;

$factory->define(Elephpant::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'popular_name' => $faker->name,
        'description' => $faker->sentence,
        'year' => (int)$faker->dateTimeBetween('-12 years', 'now')->format('Y'),
        'manufacturer' => $faker->company,
        'sponsor' => $faker->company,
        'quantity' => $faker->randomElement([100, 200, 300, 400]),
    ];
});
