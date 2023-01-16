<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h5 class="fw-bold">Добавить новый диагноз</h5>

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
                        <label class="col-sm-2 col-form-label fw-bold ">Наименование</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.disease.name.$model"
                                   :class="v$.disease.name.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.disease.name.$error" :class="v$.disease.name.$error ? 'text-danger' : ''">
                                  Наименование обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Код диагноза</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.disease.code.$model"
                                   :class="v$.disease.code.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.disease.code.$error" :class="v$.disease.code.$error ? 'text-danger' : ''">
                                  Код диагноза обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="col-12 my-3 text-center">
                        <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                            {{ processing ? "Please wait" : "Сохранить" }}
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
export default {
    name: "Create",
    data() {
        return {
            v$: useValidate(),
            disease: {name: null, code: null},
            processing: false,
            errors : {},
            success : null,
        }
    },
    mounted() {
        // this.getData();
    },
    validations() {
        return {
            disease: {
                name: {required},
                code: {required},
            }
        }
    },
    methods: {
        // getData() {
        //     axios.get('/api/permissions/create', {
        //         headers: {Authorization: localStorage.getItem('access_token')}
        //     }).then(res => {
        //         console.log(res);
        //         this.roles = res.data.roles;
        //     }).catch(err => {
        //         console.log(err.response)
        //     })
        // },
        store() {
            this.errors = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.post(`/api/diseases`,
                    this.disease,
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.disease = res.data.data;
                    this.success = 'Диагноз успешно добавлен. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'diseases'})
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
