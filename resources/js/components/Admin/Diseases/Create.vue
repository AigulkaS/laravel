<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить новый диагноз</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

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
            disease: {name: null, code: null},
            processing: false,
            success : null,
            wait,
        }
    },
    mounted() {
        this.successPage = true;
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
        store() {
            this.errs = null
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
                    // this.$emit('clicked', this.disease)
                    this.$emit('taked_data', this.disease)
                    setTimeout(()=>{
                        this.$router.push({name:'diseases'})
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
