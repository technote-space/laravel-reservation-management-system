<?php

return [

    'primary_id'          => '主キー',
    'admins'              => [
        'name'              => '名前',
        'email'             => 'メールアドレス',
        'email_verified_at' => 'メール認証完了日時',
        'password'          => 'パスワード',
        'remember_token'    => 'パスワード再発行トークン',
    ],
    'guests'              => [],
    'guest_details'       => [
        'guest_id'  => '利用者ID',
        'name'      => '名前',
        'name_kana' => 'カナ名',
        'zip_code'  => '郵便番号',
        'address'   => '住所',
        'phone'     => '電話番号',
    ],
    'reservations'        => [
        'guest_id'   => '利用者ID',
        'room_id'    => '部屋ID',
        'start_date' => '利用開始日',
        'end_date'   => '利用終了日',
        'check_out'  => 'チェックアウト時間',
        'number'     => '利用人数',
    ],
    'reservation_details' => [
        'reservation_id'  => '予約ID',
        'number'          => '利用人数',
        'payment'         => '支払金額',
        'room_name'       => '部屋名',
        'guest_name'      => '利用者名',
        'guest_name_kana' => '利用者カナ名',
        'guest_zip_code'  => '利用者郵便番号',
        'guest_address'   => '利用者住所',
        'guest_phone'     => '利用者電話番号',
    ],
    'rooms'               => [
        'name'   => '部屋名',
        'number' => '最大人数',
        'price'  => '料金',
    ],
    'settings'            => [
        'key'   => '設定キー',
        'value' => '設定値',
        'type'  => 'タイプ',
    ],

];
