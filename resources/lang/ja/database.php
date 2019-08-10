<?php

return [

    'primary_id'    => '主キー',
    'admins'        => [
        'name'              => '名前',
        'email'             => 'メールアドレス',
        'email_verified_at' => 'メール認証完了日時',
        'password'          => 'パスワード',
        'remember_token'    => 'パスワード再発行トークン',
    ],
    'guests'        => [],
    'guest_details' => [
        'guest_id'  => '利用者ID',
        'name'      => '名前',
        'name_kana' => 'カナ名',
        'zip_code'  => '郵便番号',
        'address'   => '住所',
        'phone'     => '電話番号',
    ],
    'reservations'  => [
        'guest_id'   => '利用者ID',
        'room_id'    => '部屋ID',
        'start_date' => '利用開始日',
        'end_date'   => '利用終了日',
        'number'     => '利用人数',
    ],
    'rooms'         => [
        'name'   => '部屋名',
        'number' => '最大人数',
        'price'  => '料金',
    ],
    'settings'      => [
        'key'   => '設定キー',
        'value' => '設定値',
        'type'  => 'タイプ',
    ],

];
