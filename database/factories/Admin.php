<?php

use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;
use App\Models\Admin;

/** @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => Hash::make('test1234'),
        'remember_token' => Str::random(10),
    ];
});
