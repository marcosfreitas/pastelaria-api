<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order;
use App\Models\OrderPastel;
use App\Models\Pastel;
use Faker\Generator as Faker;

$factory->define(OrderPastel::class, function (Faker $faker) use ($factory) {
    return [
        'order_id' => function() {
            return factory(Order::class)->create()->id;
        },
        'pastel_id' => function() {
            return factory(Pastel::class)->create()->id;
        },
        'created_at' => $faker->dateTime,
		'updated_at' => $faker->dateTime
    ];
});
