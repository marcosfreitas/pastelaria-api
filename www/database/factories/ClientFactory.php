<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->unique()->name,
        'email' => preg_replace('#@example\..*#', '@getnada.com', $faker->unique()->safeEmail),
        'phone' => $faker->unique()->phoneNumber,
        'birth' => $faker->date,
        'address' => $faker->streetAddress,
        'complement' => $faker->country . ' ' . $faker->city,
        'district' => $faker->monthName,
        'zip_code' => '05686700',
        'created_at' => $faker->dateTime,
		'updated_at' => $faker->dateTime
    ];
});
