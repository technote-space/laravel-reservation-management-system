<?php

use Carbon\Carbon;
use Faker\Generator as Faker;
use App\Models\Reservation;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Reservation::class, function (Faker $faker, array $attributes) {
    static $last = [];
    if (! isset($last[$attributes['room_id']])) {
        $last[$attributes['room_id']] = $faker->dateTimeBetween('-12 months')->format('Y-m-d');
    }

    if (empty($attributes['start_date']) || empty($attributes['end_date'])) {
        $start                        = $faker->dateTimeBetween($last[$attributes['room_id']].' +1days', $last[$attributes['room_id']].' +30 days')->format('Y-m-d');
        $end                          = $faker->dateTimeBetween($start, $start.'  +5 days')->format('Y-m-d');
        $last[$attributes['room_id']] = $end;
    } else {
        $start = $attributes['start_date'];
        $end   = $attributes['end_date'];
    }

    return [
        'start_date' => $start,
        'end_date'   => $end,
        'checkout'   => '10:00:00',
        'status'     => Carbon::parse("$start 15:00:00")->isAfter(now()) ? 'reserved' : (Carbon::parse("$end 10:00:00")->isAfter(now()) ? 'checkin' : 'checkout'),
    ];
});
