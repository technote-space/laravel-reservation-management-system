<?php

use Faker\Generator as Faker;
use App\Models\GuestDetail;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(GuestDetail::class, function (Faker $faker) {
    return [
        'name'      => $faker->name,
        'name_kana' => $faker->kanaName,
        'zip_code'  => $faker->postcode,
        'address'   => preg_replace('#\A\d+\s+#', '', $faker->address),
        'phone'     => $faker->phoneNumber,
    ];
});
