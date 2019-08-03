<template>
    <v-layout>
        <v-flex>
            <v-card-title>
                <div class="headline mb-0">
                    {{ item.detail.name }}（{{ item.detail.name_kana }}）
                </div>
            </v-card-title>
            <v-list
                two-line
                class="pt-2"
            >
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.detail.zip_code }}</v-list-item-title>
                        <v-list-item-subtitle>郵便番号</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.detail.address }}</v-list-item-title>
                        <v-list-item-subtitle>住所</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item>
                    <v-list-item-content>
                        <v-list-item-title>{{ item.detail.phone }}</v-list-item-title>
                        <v-list-item-subtitle>電話番号</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        props: ['item'],
    };
</script>
