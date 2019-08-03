<template>
    <v-container
        fluid
        fill-height
    >
        <v-btn
            color="accent"
            dark
            fab
            absolute
            right
            top
            class="mt-12 close-button"
            @click="backToList"
        >
            <v-icon>close</v-icon>
        </v-btn>
        <v-layout
            wrap
            justify-center
        >
            <v-flex>
                <DetailContent
                    :item="item"
                    :target-model="targetModel"
                    :detail-content-component="detailContentComponent"
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import DetailContent from '../organisms/DetailContent';

    export default {
        props: {
            targetModel: String,
            detailContentComponent: Object,
        },
        components: {
            DetailContent,
        },
        created () {
            this.setModel(this.targetModel);
            this.setDetail(this.$route.params.id);
        },
        computed: {
            ...mapGetters({
                item: 'crud/getDetailData',
                getListRouter: 'crud/getListRouter',
            }),
        },
        methods: {
            ...mapActions({
                setModel: 'crud/setModel',
                setDetail: 'crud/setDetail',
            }),
            backToList: function () {
                this.$router.push(this.getListRouter);
            },
        },
    };
</script>
