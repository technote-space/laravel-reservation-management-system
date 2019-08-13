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
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Sales from '../organisms/Sales';
    import moment from 'moment';

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
                settings: [
                    {
                        label: this.$t('misc.monthly_sales'),
                        start: moment().subtract(11, 'months').startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'days').format('YYYY-MM-DD'),
                        type: 'monthly',
                    },
                    {
                        label: this.$t('misc.daily_sales'),
                        start: moment().startOf('month').format('YYYY-MM-DD'),
                        end: moment().add(1, 'days').format('YYYY-MM-DD'),
                        type: 'daily',
                    },
                ],
            };
        },
    };
</script>
