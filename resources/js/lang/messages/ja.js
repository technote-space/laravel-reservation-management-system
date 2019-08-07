export default {
    title: '予約システム',
    pages: {
        dashboard: 'ダッシュボード',
        guest: '利用者',
        reservation: '予約',
        room: '部屋',
        login: 'ログイン',
        logout: 'ログアウト',
        'not_found': 'Not Found',
    },
    column: {
        id: 'ID',
        name: '名前',
        'room_name': '部屋名',
        katakana: 'カナ名',
        email: 'メールアドレス',
        password: 'パスワード',
        price: '料金',
        number: '人数',
        'max_number': '最大人数',
        'zip_code': '郵便番号',
        address: '住所',
        phone: '電話番号',
        'start_datetime': '開始日時',
        'end_datetime': '終了日時',
        actions: 'アクション',
    },
    validations: {
        attributes: {
            email: 'メールアドレス',
            password: 'パスワード',
        },
    },
    loading: {},
    messages: {
        'password_hint': '{min} 文字以上',
        'delete_item': 'この項目を削除してもよろしいですか？',
    },
    unit: {
        number: '{value}人',
        price: '{value}円',
    },
};