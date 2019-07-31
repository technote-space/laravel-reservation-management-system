<?php

use Faker\Generator as Faker;
use App\Models\GuestDetail;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(GuestDetail::class, function (Faker $faker) {
    return [
        'name'      => $faker->name,
        'name_kana' => $faker->kanaName,
        'zip_code'  => substr_replace($faker->postcode, '-', 3, 0),
        'address'   => preg_replace('#\A\d+\s+#', '', $faker->address),
        'phone'     => '0'.$faker->numberBetween(10, 99).'-'.$faker->numberBetween(10, 9999).'-'.$faker->numberBetween(100, 9999),
    ];
});
