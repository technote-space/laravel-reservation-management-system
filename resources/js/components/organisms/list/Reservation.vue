<template>
    <v-layout>
        <v-flex>
            <v-list>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.room.name }}</v-list-item-title>
                        <v-list-item-subtitle>部屋名</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.room.price }}円</v-list-item-title>
                        <v-list-item-subtitle>金額</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.number }}人</v-list-item-title>
                        <v-list-item-subtitle>人数</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.start_datetime | moment }}～{{ item.end_datetime | moment }}</v-list-item-title>
                        <v-list-item-subtitle>期間</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.guest.detail.name }}（{{ item.guest.detail.name_kana }}）</v-list-item-title>
                        <v-list-item-subtitle>名前</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.guest.detail.zip_code }}</v-list-item-title>
                        <v-list-item-subtitle>郵便番号</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.guest.detail.address }}</v-list-item-title>
                        <v-list-item-subtitle>住所</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.guest.detail.phone }}</v-list-item-title>
                        <v-list-item-subtitle>電話番号</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-flex>
    </v-layout>
</template>

<script>
    import moment from 'moment';

    export default {
        props: ['item'],
        filters: {
            moment: function (date) {
                return moment(date).format('YYYY/MM/DD HH:mm');
            },
        },
    };
</script>
