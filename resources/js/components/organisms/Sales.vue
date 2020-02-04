<template>
    <v-card
        raised
        class="pa-6 ma-4"
    >
        <v-card-title>
            {{ label }}
        </v-card-title>
        <v-card-text>
            <Bar
                :label="label"
                :dataset="data"
            />
            <v-overlay
                :value="isLoading"
                absolute
                class="text-center"
            >
                <v-progress-circular
                    indeterminate
                    size="32"
                />
            </v-overlay>
        </v-card-text>
    </v-card>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Bar from '../molecules/Bar';
    import { apiGet } from '../../utils/api';
    import { arrayToObject } from '../../utils/misc';

    export default {
        components: {
            Bar,
        },
        props: {
            label: {
                type: String,
                required: true,
            },
            start: {
                type: String,
                required: true,
            },
            end: {
                type: String,
                required: true,
            },
            type: {
                type: String,
                required: true,
            },
            roomId: {
                type: [Number, String],
                required: true,
            },
        },
        data () {
            return {
                data: [],
            };
        },
        computed: {
            ...mapGetters({
                isLoading: 'loading/isLoading',
            }),
        },
        watch: {
            roomId: {
                handler: 'setup',
                immediate: true,
            },
        },
        methods: {
            async setup () {
                const { response } = await apiGet('summary', {
                    data: {
                        'start_date': this.start,
                        'end_date': this.end,
                        type: this.type,
                        'room_id': this.roomId,
                    },
                });
                if (response) {
                    this.data = Object.values(arrayToObject(Object.keys(response.data), {
                        getItem: key => ({ label: key, value: response.data[ key ] }),
                    }));
                }
            },
        },
    };
</script>
