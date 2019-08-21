<template>
    <v-container
        fluid
        fill-height
    >
        <v-layout
            wrap
            align-center
            justify-center
        >
            <v-flex sm12>
                <v-select
                    v-model="roomId"
                    :disabled="isLoading"
                    :items="rooms"
                    class="d-inline-block float-right"
                />
            </v-flex>
            <v-flex
                v-for="setting in settings"
                :key="setting.label"
                sm12
                md6
            >
                <Sales
                    :label="setting.label"
                    :start="setting.start"
                    :end="setting.end"
                    :type="setting.type"
                    :room-id="roomId"
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions } from 'vuex';
    import moment from 'moment';
    import Sales from '../organisms/Sales';

    export default {
        components: {
            Sales,
        },
        metaInfo () {
            return {
                title: this.$t('pages.dashboard'),
            };
        },
        data () {
            return {
                isLoading: false,
                rooms: [],
                roomId: '',
                settings: [
                    {
                        label: this.$t('misc.monthly_sales'),
                        start: moment().subtract(11, 'months').startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'months').startOf('month').format('YYYY-MM-DD'),
                        type: 'monthly',
                    },
                    {
                        label: this.$t('misc.daily_sales'),
                        start: moment().startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'months').startOf('month').format('YYYY-MM-DD'),
                        type: 'daily',
                    },
                ],
            };
        },
        async mounted () {
            this.isLoading = true;
            this.rooms = [
                {
                    text: this.$t('misc.all_rooms'),
                    value: '',
                },
            ].concat((await this.search({
                model: 'rooms',
            })).map(item => ({
                text: item.name,
                value: item.id,
            })));
            this.roomId = '';
            this.isLoading = false;
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
            }),
        },
    };
</script>
