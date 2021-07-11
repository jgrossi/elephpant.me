<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Elephpant;
use Faker\Generator as Faker;

$factory->define(Elephpant::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName,
        'year' => (int) $faker->dateTimeBetween('-12 years', 'now')->format('Y'),
        'sponsor' => $faker->company,
        'image' => $faker->imageUrl(),
        'description' => $faker->text,
    ];
});
