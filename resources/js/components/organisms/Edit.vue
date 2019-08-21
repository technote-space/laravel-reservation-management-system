<template>
    <v-card>
        <v-form @submit.prevent="save">
            <v-card-title>
                <span class="headline">{{ $t(formTitle) }}</span>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-layout
                        v-if="isValid"
                        wrap
                    >
                        <v-flex
                            v-for="form in forms"
                            :key="form.name"
                            xs12
                            sm6
                            md4
                        >
                            <FormItem
                                v-model="formInputs[form.name]"
                                v-validate="form.validate"
                                :form="form"
                                :detail="detail"
                                :form-inputs="formInputs"
                                :data-vv-name="form.text"
                                :validate-errors="errors.collect(form.text)"
                                :increment="increment"
                            />
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn
                    @click="close"
                    color="blue darken-1"
                    text
                >
                    {{ $t('misc.cancel') }}
                </v-btn>
                <v-btn
                    @click="save"
                    color="blue darken-1"
                    text
                >
                    {{ $t('misc.save') }}
                </v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import { get, set } from 'lodash';
    import FormItem from '../molecules/FormItem';

    export default {
        components: {
            FormItem,
        },
        props: {
            targetModel: {
                type: String,
                required: true,
            },
            targetId: {
                required: true,
                validator: prop => null === prop || 'number' === typeof prop,
            },
            increment: {
                type: Number,
                required: true,
            },
        },
        data () {
            return {
                formInputs: {},
                nowModel: null,
            };
        },
        computed: {
            ...mapGetters({
                model: 'crud/getTargetModel',
                getModelForms: 'getModelForms',
                getDetailData: 'crud/getDetailData',
            }),
            formTitle () {
                return null === this.targetId ? 'misc.new_item' : 'misc.edit_item';
            },
            forms () {
                return this.getModelForms(this.model);
            },
            detail () {
                return this.targetId ? (this.getDetailData || {}) : {};
            },
            sendInputs () {
                const inputs = {};
                this.forms.map(form => {
                    set(inputs, form.name, this.formInputs[ form.name ]);
                });
                return inputs;
            },
            isValid () {
                return this.targetModel === this.nowModel;
            },
        },
        watch: {
            increment: {
                handler: 'setup',
                immediate: true,
            },
        },
        methods: {
            ...mapActions({
                setModel: 'crud/setModel',
                setDetail: 'crud/setDetail',
                create: 'crud/create',
                edit: 'crud/edit',
            }),
            async setup () {
                this.errors.clear();
                this.clearForm();
                if (!await this.getDetail()) {
                    return;
                }

                this.fillForm();
                this.nowModel = this.targetModel;
            },
            clearForm () {
                this.formInputs = {};
                this.forms.map(form => {
                    this.formInputs[ form.name ] = '';
                });
            },
            async getDetail () {
                await this.setModel(this.targetModel);
                if (this.targetId) {
                    await this.setDetail(this.targetId);
                    if (!this.getDetailData) {
                        this.close();
                        return false;
                    }
                }
                return true;
            },
            fillForm () {
                const inputs = {};
                this.forms.map(form => {
                    inputs[ form.name ] = get(this.detail, form.value, '');
                });
                this.formInputs = inputs;
            },
            update (form, value) {
                this.formInputs[ form.name ] = value;
            },
            save () {
                this.$validator.validateAll().then(async result => {
                    if (!result) {
                        return;
                    }
                    if (this.targetId) {
                        if (await this.edit({ model: this.model, id: this.targetId, data: this.sendInputs })) {
                            this.close();
                        }
                    } else {
                        if (await this.create({ model: this.model, data: this.sendInputs })) {
                            this.close();
                        }
                    }
                });
            },
            close () {
                this.$emit('close-edit');
            },
        },
    };
</script>
