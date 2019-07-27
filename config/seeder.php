<?php

return [

    'admin_number'           => env('SEEDER_ADMIN_NUMBER', 2),
    'guest_number'           => env('SEEDER_GUEST_NUMBER', 25),
    'room_number'            => env('SEEDER_ROOM_NUMBER', 5),
    'reservation_number_min' => env('SEEDER_RESERVATION_MIN_NUMBER', 1),
    'reservation_number_max' => env('SEEDER_RESERVATION_MAX_NUMBER', 10),

    'admin_name'     => env('SEEDER_ADMIN_NAME', ''),
    'admin_email'    => env('SEEDER_ADMIN_EMAIL', ''),
    'admin_password' => env('SEEDER_ADMIN_PASSWORD', ''),

];
