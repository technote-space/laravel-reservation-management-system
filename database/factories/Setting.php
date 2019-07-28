<?php

use Faker\Generator as Faker;
use App\Models\Setting;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Setting::class, function (Faker $faker) {
    return [
        'key'   => $faker->word,
        'value' => $faker->unique()->word,
    ];
});
