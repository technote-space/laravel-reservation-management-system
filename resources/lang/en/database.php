<?php

return [

    'primary_id'    => 'Primary ID',
    'admins'        => [
        'name'              => 'Name',
        'email'             => 'E-Mail',
        'email_verified_at' => 'E-Mail verified at',
        'password'          => 'Password',
        'remember_token'    => 'Remember',
    ],
    'guests'        => [],
    'guest_details' => [
        'guest_id'  => 'Guest ID',
        'name'      => 'Name',
        'name_kana' => 'Katakana',
        'zip_code'  => 'ZIP Code',
        'address'   => 'Address',
        'phone'     => 'Phone number',
    ],
    'reservations'  => [
        'guest_id'   => 'Guest ID',
        'room_id'    => 'Room ID',
        'start_date' => 'Start date',
        'end_date'   => 'End date',
        'number'     => 'Number',
    ],
    'rooms'         => [
        'name'   => 'Room name',
        'number' => 'Max number',
        'price'  => 'Price',
    ],
    'settings'      => [
        'key'   => 'Setting key',
        'value' => 'Setting value',
        'type'  => 'Type',
    ],

];
