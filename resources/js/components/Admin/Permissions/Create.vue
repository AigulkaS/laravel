<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h5 class="fw-bold">Добавить новое разрешение</h5>

                <div v-if="success" class="alert alert-success" role="alert">
                    {{success}}
                </div>
                <div class="col-12" v-if="errors && Object.keys(errors).length > 0">
                    <div class="alert alert-danger">
                        <template v-if="typeof errors == 'object'">
                            <ul class="mb-0">
                                <li v-for="(value, key) in errors" :key="key">{{ value[0] }}</li>
                            </ul>
                        </template>
                        <template v-else>
                            <div>{{errors}}</div>
                        </template>
                    </div>
                </div>

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
</template>

<script>
import useValidate from '@vuelidate/core';
import { required} from '@vuelidate/validators';
// import Multiselect from '@vueform/multiselect'
import {wait} from "../../../consts";

export default {
    name: "Create",
    // components: {
    //     Multiselect,
    // },
    data() {
        return {
            v$: useValidate(),
            permission: {name: null},
            roles: [],
            processing: false,
            errors : {},
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
                console.log(err.response)
            })
        },
        store() {
            // console.log(this.value)
            // console.log(Array.from(this.value));

            this.errors = null
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
                    // this.value=null;
                    this.success = 'Разрешение успешно добавлена. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'permissions'})
                    },3000)
                }).catch(err => {
                    console.log(err.response);
                    if(err.response.status == 422){
                        this.errors = err.response.data.errors
                    }
                    else if (err.response.status == 500) {
                        //что то придумать с ошикой 404 и 500, записала в тетрадь
                        this.errors = {}
                        this.errors = err.response.data.message
                    }
                    else{
                        this.errors = {}
                        this.errors = err.response.data.errors
                    }

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
/*.multiselect-option.is-selected {*/
/*    background: #0d6efd !important;*/
/*}*/
</style>
<!--<style src="@vueform/multiselect/themes/default.css"></style>-->

<style scoped>

</style>
