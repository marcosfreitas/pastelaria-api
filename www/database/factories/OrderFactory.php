<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use App\Models\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) use ($factory) {
    return [
        'uuid' => $faker->uuid,
        'client_id' => function() {
            return factory(Client::class)->create()->id;
        },
        'created_at' => $faker->dateTime,
		'updated_at' => $faker->dateTime
    ];
});
