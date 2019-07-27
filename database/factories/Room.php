<?php

use Faker\Generator as Faker;
use App\Models\Room;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Room::class, function (Faker $faker) {
    return [
        'name'   => $faker->name,
        'number' => $faker->numberBetween(1, 5),
        'price'  => $faker->numberBetween(10000, 50000),
    ];
});
