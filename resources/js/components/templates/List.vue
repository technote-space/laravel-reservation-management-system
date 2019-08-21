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
                            {{ $t(title) }}
                        </v-toolbar-title>
                        <v-divider
                            class="mx-4"
                            inset
                            vertical
                        />
                        <v-spacer />
                        <v-btn
                            @click.stop="createItem"
                            color="primary"
                            dark
                            class="mb-2"
                        >
                            {{ $t('misc.new_item') }}
                        </v-btn>
                        <v-dialog
                            v-model="dialog"
                            max-width="750px"
                            persistent
                        >
                            <Edit
                                :target-model="targetModel"
                                :target-id="targetId"
                                :increment="increment"
                                @close-edit="close"
                            />
                        </v-dialog>
                        <YesCancel
                            :dialog="deleteDialog"
                            @yes="deleteItem"
                            @cancel="deleteTargetId = null"
                            message="messages.delete_item"
                        />
                    </v-toolbar>
                </template>
                <template v-slot:item.action="{ item }">
                    <v-icon
                        @click="editItem(item)"
                        small
                        class="mr-2"
                    >
                        edit
                    </v-icon>
                    <v-icon
                        @click="deleteItemConfirm(item)"
                        small
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
    import Edit from '../organisms/Edit';
    import YesCancel from '../organisms/confirm/YesCancelDialog';

    export default {
        components: {
            Edit,
            YesCancel,
        },
        props: {
            targetModel: {
                type: String,
                required: true,
            },
        },
        data () {
            return {
                dialog: false,
                targetId: null,
                increment: 0,
                deleteTargetId: null,
            };
        },
        metaInfo () {
            return this.metaInfo;
        },
        computed: {
            ...mapGetters({
                getModelName: 'getModelName',
                getModelIcon: 'getModelIcon',
                getModelMetaInfo: 'getModelMetaInfo',
                model: 'crud/getTargetModel',
                getListHeaders: 'crud/getListHeaders',
                items: 'crud/getListItems',
                perPage: 'crud/getPerPage',
                totalPage: 'crud/getTotalPage',
                page: 'crud/getPage',
            }),
            isValidPagination () {
                return 1 < this.totalPage;
            },
            metaInfo () {
                return Object.assign({}, {
                    title: this.$t(this.title),
                }, this.getModelMetaInfo(this.model));
            },
            title () {
                return this.getModelName(this.model);
            },
            icon () {
                return this.getModelIcon(this.model);
            },
            headers () {
                return this.getListHeaders.map(item => {
                    return Object.assign({}, item, {
                        text: this.$t('column.' + item.text),
                    });
                });
            },
            deleteDialog () {
                return null !== this.deleteTargetId;
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
                destroy: 'crud/destroy',
            }),
            setup () {
                this.setModel(this.targetModel);
                this.setPage(1);
            },
            createItem () {
                this.editItem(null);
            },
            editItem (item) {
                this.targetId = item ? item.id - 0 : null;
                this.increment++;
                this.dialog = true;
            },
            deleteItemConfirm (item) {
                this.deleteTargetId = item.id;
            },
            deleteItem () {
                this.destroy({ model: this.model, id: this.deleteTargetId });
                this.deleteTargetId = null;
            },
            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.targetId = null;
                }, 300);
            },
        },
    };
</script>

<style lang='scss'>
    .v-data-table-header-mobile {
        display: none;
    }
</style>
