<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить станцию СМП</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>


                    <form @submit.prevent="store" class="row">
                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Полное наименование</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.station.full_name.$model"
                                       :class="v$.station.full_name.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.station.full_name.$error"
                                      :class="v$.station.full_name.$error ? 'text-danger' : ''">
                                  Полное наименование обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Кароткое наименование</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.station.short_name.$model"
                                       :class="v$.station.short_name.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.station.short_name.$error"
                                      :class="v$.station.short_name.$error ? 'text-danger' : ''">
                                  Кароткое наименование обязательное поле для заполнения
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
import { required } from '@vuelidate/validators';
import {wait, hospital_type} from "../../../consts";

export default {
    name: "Create",
    data() {
        return {
            v$: useValidate(),
            station: {
                full_name: null,
                short_name: null,
            },
            processing: false,
            success : null,
            timeout: null,
            wait,
            hospital_type,
        }
    },
    mounted() {
        // this.getData();
        this.successPage = true
    },
    validations() {
        return {
            station: {
                full_name: {required},
                short_name: {required},
            },
        }
    },
    methods: {
        getData() {
            axios.get('/api/hospitals/create', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                // this.roles = res.data.roles;
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
                axios.post('/api/hospitals', {
                        type:  hospital_type.smp,
                        full_name: this.station.full_name,
                        short_name: this.station.short_name,
                        address: 'null',
                        geo_lat: 'null',
                        geo_lon: 'null',
                        hospital_rooms: 'null'
                    },
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.success = 'Станция СМП успешно добавлена. Перенаправление...';
                    this.$emit('add_data', res.data.data)
                    setTimeout(()=>{
                        this.$router.push({name:'ambulance_stations'})
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => this.processing = false)
            } else {
                window.scrollTo(0,0);
            }

        },
    }
}
</script>

