<?php

use Faker\Generator as Faker;
use App\Models\Reservation;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Reservation::class, function (Faker $faker, array $attributes) {
    static $last = [];
    if (! isset($last[$attributes['room_id']])) {
        $last[$attributes['room_id']] = $faker->dateTimeBetween('-6 months')->format('Y-m-d');
    }
    $start                        = $faker->dateTimeBetween($last[$attributes['room_id']].' +1days', $last[$attributes['room_id']].' +14 days')->format('Y-m-d');
    $end                          = $faker->dateTimeBetween($start, $start.'  +4 days')->format('Y-m-d');
    $last[$attributes['room_id']] = $end;

    return [
        'start_date' => $start,
        'end_date'   => $end,
        'number'     => $faker->numberBetween(1, 2),
    ];
});
