<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить новое разрешение</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <form @submit.prevent="store" class="row" >
                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Разрешение</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.permission.name.$model"
                                       :class="v$.permission.name.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.permission.name.$error" :class="v$.permission.name.$error ? 'text-danger' : ''">
                                  Разрешение обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Наименование</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.permission.label.$model"
                                       :class="v$.permission.label.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.permission.label.$error" :class="v$.permission.label.$error ? 'text-danger' : ''">
                                  Наименование обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold">
                                Роли
                            </label>
                            <div class="col-sm-10">
                                <Multiselect
                                    v-model="value"
                                    mode="multiple"
                                    :close-on-select="false"
                                    :hide-selected="false"
                                    label="name"
                                    valueProp="id"
                                    :options="roles"
                                >
                                    <template v-slot:multiplelabel="{ values }">
                                        <div>
                                        <span v-for="value in values" class="badge bg-primary">
                                            {{ value.name }}</span>
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
import { required} from '@vuelidate/validators';
import {wait} from "../../../consts";

export default {
    name: "Create",
    data() {
        return {
            v$: useValidate(),
            permission: {name: null},
            roles: [],
            processing: false,
            success : null,
            value: [],
            wait,
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            permission: {
                name: {required},
                label: {required},
            }
        }
    },
    methods: {
        getData() {
            axios.get('/api/permissions/create', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.roles = res.data.roles;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        store() {
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.post(`/api/permissions`,
                    {name: this.permission.name, label: this.permission.label, roles: Array.from(this.value)},
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.permission = res.data.data;
                    this.success = 'Разрешение успешно добавлена. Перенаправление...';
                    this.$emit('add_data', this.permission)
                    setTimeout(()=>{
                        this.$router.push({name:'permissions'})
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {this.processing = false;})
            } else {
                window.scrollTo(0,0);
            }

        }
    }
}
</script>

<style scoped>

</style>
