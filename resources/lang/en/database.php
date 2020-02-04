<?php

return [

    'primary_id'          => 'Primary ID',
    'admins'              => [
        'name'              => 'Name',
        'email'             => 'E-Mail',
        'email_verified_at' => 'E-Mail verified at',
        'password'          => 'Password',
        'remember_token'    => 'Remember',
    ],
    'guests'              => [],
    'guest_details'       => [
        'guest_id'  => 'Guest ID',
        'name'      => 'Name',
        'name_kana' => 'Katakana',
        'zip_code'  => 'ZIP Code',
        'address'   => 'Address',
        'phone'     => 'Phone number',
    ],
    'reservations'        => [
        'guest_id'   => 'Guest ID',
        'room_id'    => 'Room ID',
        'start_date' => 'Start date',
        'end_date'   => 'End date',
        'checkout'   => 'Checkout Time',
        'number'     => 'Number',
        'status'     => 'Status',
    ],
    'reservation_details' => [
        'reservation_id'  => 'Reservation ID',
        'number'          => 'Number of guests',
        'payment'         => 'Payment',
        'room_name'       => 'Room name',
        'guest_name'      => 'Guest name',
        'guest_name_kana' => 'Guest katakana',
        'guest_zip_code'  => 'Guest ZIP Code',
        'guest_address'   => 'Guest address',
        'guest_phone'     => 'Guest phone',
    ],
    'rooms'               => [
        'name'   => 'Room name',
        'number' => 'Max number',
        'price'  => 'Price',
    ],
    'settings'            => [
        'key'   => 'Setting key',
        'value' => 'Setting value',
        'type'  => 'Type',
    ],

];
