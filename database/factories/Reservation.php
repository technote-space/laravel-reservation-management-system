<?php

use Faker\Generator as Faker;
use App\Models\Reservation;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Reservation::class, function (Faker $faker) {
    $start = $faker->dateTimeBetween('-10days', '+10days')->format('Y-m-d');

    return [
        'start_date' => $start,
        'end_date'   => $faker->dateTimeBetween($start, $start.'  +4 days')->format('Y-m-d'),
        'number'     => $faker->numberBetween(1, 2),
    ];
});
