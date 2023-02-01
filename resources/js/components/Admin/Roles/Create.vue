<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить новую роль</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <form @submit.prevent="store" class="row" >
                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Роль</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.role.name.$model"
                                       :class="v$.role.name.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.role.name.$error" :class="v$.role.name.$error ? 'text-danger' : ''">
                                  Роль обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Наименование</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.role.label.$model"
                                       :class="v$.role.label.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.role.label.$error" :class="v$.role.label.$error ? 'text-danger' : ''">
                                  Наименование обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold">
                                Разрешения
                            </label>
                            <div class="col-sm-10" v-if="permissions">
                                <Multiselect
                                    v-model="value"
                                    mode="multiple"
                                    :close-on-select="false"
                                    :hide-selected="false"
                                    label="name"
                                    valueProp="id"
                                    :options="permissions"
                                >
                                    <template v-slot:multiplelabel="{ values }">
                                        <div>
                                        <span v-for="value in values" class="badge bg-primary">
                                            {{ value.name }}545454545465456</span>
                                        </div>
                                    </template>
                                </Multiselect>
                            </div>
                        </div>


                        <div class="col-12 my-3 text-center">
                            <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? wait : "Сохранить" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import useValidate from '@vuelidate/core';
import { required } from '@vuelidate/validators';
import {wait} from "../../../consts";

export default {
    name: "Create",
    data() {
        return {
            v$: useValidate(),
            role: {name: null, label: null},
            permissions: null,
            processing: false,
            success : null,
            value: [],
            wait
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            role: {
                name: {required},
                label: {required},
            }
        }
    },
    methods: {
        getData() {
            axios.get('/api/roles/create', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.permissions = res.data.permissions;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
        store() {
            // console.log(this.value)
            // this.value.map(v => {
            //     console.log(v)
            // })
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.post(`/api/roles`,
                    {name: this.role.name, label: this.role.label, permissions: Array.from(this.value)},
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.role = res.data.data;
                    this.success = 'Роль успешно добавлена. Перенаправление...';
                    this.$emit('add_data', this.role)
                    setTimeout(()=>{
                        this.$router.push({name:'roles'})
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                })
            } else {
                window.scrollTo(0,0);
            }

        }
    }
}
</script>
<style>
.multiselect-option.is-selected {
    background: #0d6efd !important;
}
</style>
<!--<style src="@vueform/multiselect/themes/default.css"></style>-->

<style scoped>

</style>
