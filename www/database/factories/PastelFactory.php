<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pastel;
use Faker\Generator as Faker;

$factory->define(Pastel::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->unique()->name,
        'price' => 5.35,
        'photo' => $faker->imageUrl(),
        'created_at' => $faker->dateTime,
		'updated_at' => $faker->dateTime
    ];
});
