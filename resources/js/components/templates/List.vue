<template>
    <v-container
        fluid
        fill-height
    >
        <v-layout
            wrap
            child-flex
        >
            <v-data-table
                :headers="headers"
                :items="items"
                :page.sync="page"
                :items-per-page="perPage"
                hide-default-footer
                disable-sort
                class="elevation-1 pa-5 mb-3"
            >
                <template v-slot:footer>
                    <div class="text-center">
                        <v-pagination
                            v-if="isValidPagination"
                            :length="totalPage"
                            :total-visible="7"
                            :value="page"
                            @input="setPage"
                        />
                    </div>
                </template>
            </v-data-table>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import ListContent from '../organisms/ListContent';
    import { getModelDetailRouter } from '../../store/modules/crud/utils';

    export default {
        props: {
            targetModel: String,
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
                headers: 'crud/getListHeaders',
                items: 'crud/getListItems',
                perPage: 'crud/getPerPage',
                totalPage: 'crud/getTotalPage',
                page: 'crud/getPage',
            }),
            isValidPagination: function () {
                return this.totalPage > 1;
            },
        },
        methods: {
            ...mapActions({
                setModel: 'crud/setModel',
                setPage: 'crud/setPage',
            }),
            showDetail: function (item) {
                this.$router.push(getModelDetailRouter(this.targetModel, item.id));
            },
        },
    };
</script>
