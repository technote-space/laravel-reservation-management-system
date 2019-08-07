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
                <template v-slot:top>
                    <v-toolbar
                        flat
                        color="white"
                    >
                        <v-toolbar-title>
                            <v-list-item-icon>
                                <v-icon>{{ icon }}</v-icon>
                            </v-list-item-icon>
                            {{ title }}
                        </v-toolbar-title>
                        <v-divider
                            class="mx-4"
                            inset
                            vertical
                        />
                        <v-spacer />
                    </v-toolbar>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                    >
                        edit
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        delete
                    </v-icon>
                </template>
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

    export default {
        metaInfo () {
            return this.metaInfo;
        },
        props: {
            targetModel: {
                type: String,
                required: true,
            },
        },
        computed: {
            ...mapGetters({
                getModelName: 'getModelName',
                getModelIcon: 'getModelIcon',
                getMetaInfo: 'getMetaInfo',
                model: 'crud/getTargetModel',
                getListHeaders: 'crud/getListHeaders',
                items: 'crud/getListItems',
                perPage: 'crud/getPerPage',
                totalPage: 'crud/getTotalPage',
                page: 'crud/getPage',
            }),
            isValidPagination: function () {
                return 1 < this.totalPage;
            },
            metaInfo: function () {
                return Object.assign({}, {
                    title: this.$t(this.getModelName(this.model)),
                }, this.getMetaInfo(this.model));
            },
            title: function () {
                return this.metaInfo.title;
            },
            icon: function () {
                return this.getModelIcon(this.model);
            },
            headers: function () {
                return this.getListHeaders.map(item => {
                    return Object.assign({}, item, {
                        text: this.$t(item.text),
                    });
                });
            },
        },
        watch: {
            targetModel: {
                handler: 'setup',
                immediate: true,
            },
        },
        methods: {
            ...mapActions({
                setModel: 'crud/setModel',
                setPage: 'crud/setPage',
                create: 'crud/create',
                edit: 'crud/edit',
                destroy: 'crud/destroy',
            }),
            setup () {
                this.setModel(this.targetModel);
                this.setPage(1);
            },
            editItem (item) {

            },
            deleteItem (item) {
                if (confirm(this.$t('messages.delete_item'))) {
                    this.destroy({ model: this.model, id: item.id });
                }
            },
            close () {

            },
            save () {

            },
        },
    };
</script>
