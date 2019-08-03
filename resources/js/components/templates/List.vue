<template>
    <v-container
        fluid
        fill-height
        grid-list-md
    >
        <v-layout
            wrap
            row
            justify-center
        >
            <v-flex
                xs12
                sm6
                md4
                v-for="item in items"
                :key="item.id"
            >
                <ListContent
                    :item="item"
                    :target-model="targetModel"
                    :list-content-component="listContentComponent"
                />
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import ListContent from '../organisms/ListContent';

    export default {
        props: {
            targetModel: String,
            listContentComponent: Object,
        },
        components: {
            ListContent,
        },
        created () {
            this.setModel(this.targetModel);
            this.setPage(1);
        },
        computed: {
            ...mapGetters({
                items: 'crud/getListItems',
            }),
        },
        methods: {
            ...mapActions({
                setModel: 'crud/setModel',
                setPage: 'crud/setPage',
            }),
        },
    };
</script>
