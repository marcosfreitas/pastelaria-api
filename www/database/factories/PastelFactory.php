<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pastel;
use Faker\Generator as Faker;

$factory->define(Pastel::class, function (Faker $faker) {

    $pastels = [
        'Carne',
        'Queijo',
        'Presunto',
        'Atum',
        'Chocolate',
        'Pizza'
    ];

    shuffle($pastels);

    return [
        'uuid' => $faker->uuid,
        'name' => 'Pastel de '. $pastels[0] .' #'. rand(1,600),
        'price' => 5.35,
        'photo' => $faker->imageUrl(),
        'created_at' => $faker->dateTime,
		'updated_at' => $faker->dateTime
    ];
});
