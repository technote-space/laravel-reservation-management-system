<template>
    <v-layout>
        <v-flex>
            <v-card-title>
                <div class="headline mb-0">
                    {{ item.name }}
                </div>
            </v-card-title>
            <v-card-title class="pt-2">
                {{ item.price }}円/～{{ item.number }}人
            </v-card-title>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        props: ['item'],
    };
</script>
