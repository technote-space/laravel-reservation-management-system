<template>
    <v-dialog
        v-model="dialog"
        width="400"
    >
        <template v-slot:activator="{ on }">
            <v-text-field
                :label="label"
                :value="display"
                :prepend-icon="icon"
                readonly
                v-on="on"
                :error-messages="validateErrors"
            />
        </template>
        <v-layout
            wrap
            justify-center
        >
            <v-card
                class="pa-6"
                width="100%"
            >
                <v-card-text>
                    <v-select
                        ref="select"
                        :disabled="isDisabledSelect"
                        :label="label"
                        :value="value"
                        :items="items"
                        :item-text="searchText"
                        :item-value="searchValue"
                    />
                    <div class="text-right">
                        <v-btn
                            :disabled="isDisabledSelect"
                            @click="buttonClicked"
                        >
                            {{ $t('misc.ok') }}
                        </v-btn>
                    </div>
                </v-card-text>
                <v-card-text>
                    <v-form @submit.prevent="searchItem">
                        <v-text-field
                            :disabled="isSearching"
                            v-model="searchWord"
                        />
                        <div class="text-right">
                            <v-btn
                                :disabled="isSearching"
                                type="submit"
                            >
                                {{ $t('misc.search') }}
                            </v-btn>
                        </div>
                    </v-form>
                </v-card-text>
            </v-card>
        </v-layout>
    </v-dialog>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import { get } from 'lodash';
    import { arrayToObject } from '../../../utils/misc';

    export default {
        data () {
            return {
                dialog: false,
                searchWord: '',
                isSearching: false,
                items: [],
                selectedId: null,
                selectedItem: null,
            };
        },
        props: {
            detail: {
                type: Object,
                required: true,
            },
            formInputs: {
                type: Object,
                required: true,
            },
            form: {
                type: Object,
                required: true,
            },
            label: {
                type: String,
                required: true,
            },
            value: {
                type: [String, Number],
                required: true,
            },
            validateErrors: {
                type: Array,
                required: true,
            },
        },
        computed: {
            ...mapGetters({
                model: 'crud/getTargetModel',
                getModelIcon: 'getModelIcon',
            }),
            icon () {
                return this.getModelIcon(this.searchModel);
            },
            searchSettings () {
                return this.form.search.split(':');
            },
            searchModel () {
                return this.searchSettings[ 0 ];
            },
            searchValue () {
                return this.searchSettings[ 1 ];
            },
            searchText () {
                return this.searchSettings[ 2 ];
            },
            isDisabledSelect () {
                return this.isSearching || !this.items.length;
            },
            display () {
                if (this.selectedId === get(this.formInputs, this.form.name)) {
                    return this.selectedItem;
                }
                return get(this.detail, this.form.display);
            },
        },
        methods: {
            ...mapActions({
                search: 'crud/search',
            }),
            async searchItem () {
                if (this.isSearching) {
                    return;
                }
                if (!this.searchWord.trim()) {
                    return;
                }

                this.isSearching = true;
                this.items = await this.search({
                    model: this.searchModel,
                    query: {
                        s: this.searchWord.trim(),
                        count: 30,
                    },
                });
                this.isSearching = false;
            },
            buttonClicked () {
                if (this.$refs.select.selectedItems.length) {
                    this.selectedId = this.$refs.select.selectedItems[ 0 ].id;
                    this.selectedItem = get(arrayToObject(this.items, { getKey: ({ item }) => item.id })[ this.selectedId ], this.searchText);
                    this.$emit('input', this.selectedId);
                }
                this.dialog = false;
            },
        },
    };
</script>
