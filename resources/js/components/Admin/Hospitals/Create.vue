<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h5 class="fw-bold">Добавить больницу</h5>

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

                <form @submit.prevent="store" class="row">
                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Полное наименование</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.hospital.full_name.$model"
                                   :class="v$.hospital.full_name.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.hospital.full_name.$error" :class="v$.hospital.full_name.$error ? 'text-danger' : ''">
                                  Полное наименование обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Кароткое наименование</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.hospital.short_name.$model"
                                   :class="v$.hospital.short_name.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.hospital.short_name.$error" :class="v$.hospital.short_name.$error ? 'text-danger' : ''">
                                  Кароткое наименование обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Адрес</label>
                        <div class="col-sm-10">
                            <vue-dadata
                                v-model="query"
                                :token="token"
                                v-model:suggestion="suggestion"
                                :locationOptions="locationOptions"
                            />
<!--                            <span v-if="query==''" class="text-danger">-->
<!--                                Адрес обязательное поле для заполнения-->
<!--                            </span>-->
                        </div>
                    </div>

                    <div class="my-4 row">
                        <div class="col-sm-3">
                            <button type="button" @click.prevent="createRooms" class="btn btn-warning btn-block">
                                Добавить кабинеты
                            </button>
                        </div>
                        <div class="col-sm-8" v-if="rooms_show">
                            <template v-for="(room, index) in rooms">
                                <div class="row my-1">
                                    <label class="col col-sm-2 col-form-label fw-bold ">Кабинет</label>
                                    <div class="col-5 col-sm-5">
                                        <input type="text" class="form-control" v-model="room.name">
                                    </div>
                                    <div class="col col-sm-3">
                                        <button type="button" @click.prevent="addRoomm(index)" class="mt-1 btn btn-success btn-circle">
                                            <font-awesome-icon icon="fa-solid fa-plus" />
                                        </button>
                                        <button type="button" @click.prevent="deleteRoomm(index)" class="ml-1 mt-1 btn btn-danger btn-circle">
                                            <font-awesome-icon icon="fa-solid fa-minus" />
                                        </button>
                                    </div>
                                </div>
                            </template>
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
import { required } from '@vuelidate/validators';
import { defineComponent, ref } from 'vue';
import { VueDadata } from 'vue-dadata';
import 'vue-dadata/dist/style.css';
import {wait} from "../../../consts";

export default defineComponent ({
    name: "Create",
    components: {
      VueDadata
    },
    setup() {
        const query = ref('');
        const suggestion = ref(undefined);

        return {
            token: import.meta.env.VITE_APP_DADATA_API_KEY,
            query,
            suggestion,
            locationOptions: {
                locations: {
                    "country_iso_code": "RU",
                    "region_iso_code": "RU-BA",
                    "restrict_value": true,
                },

            }


        };
    },
    data() {
        return {
            v$: useValidate(),
            hospital: {
                full_name: null,
                short_name: null,
                address: null,
            },
            processing: false,
            errors : {},
            success : null,
            rooms: [],
            rooms_show: false,
            wait,
        }
    },
    mounted() {
        this.getData();
        console.log(this.suggestion)
    },
    validations() {
        return {
            hospital: {
                full_name: {required},
                short_name: {required},
            }
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
                console.log(err.response)
            })
        },
        createRooms() {
            if (this.rooms.length == 0) {
                this.rooms.push({name: null})
            }
            this.rooms_show = !this.rooms_show;
        },
        addRoomm(index) {
            // this.rooms.push({name: null})
            this.rooms.splice(index+1, 0, {name: null})
        },
        deleteRoomm(index) {
            this.rooms.splice(index, 1);
            if (this.rooms.length == 0) this.rooms_show=false;
        },
        store() {
            // console.log(this.suggestion)
            // console.log(this.suggestion.unrestricted_value)

            this.errors = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            this.rooms = this.rooms.filter((el, index) => {
               return  el.name !== null && el.name !== ''
            })
            console.log(this.v$.$error)
            if (!this.v$.$error) {
                console.log(444)
                this.processing = true;
                axios.post('/api/hospitals', {
                        full_name: this.hospital.full_name,
                        short_name: this.hospital.short_name,
                        address: this.suggestion.unrestricted_value,
                        hospital_rooms: this.rooms
                    },
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.success = 'Больница успешно добавлена. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'hospitals'})
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
                console.log(5555)
                window.scrollTo(0,0);
            }

        }
    }
});
</script>
<style>
.vue-dadata__input {
    display: block;
    width: 100%;
    height: 37.04px;
    padding: 0.375rem 0.75rem;
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.6;
    color: #212529;
    background-color: #f8fafc;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    appearance: none;
    border-radius: 0.375rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.vue-dadata__input:focus-within {
    border-color: #0d6efd;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
}
</style>
<style scoped>
.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}
</style>
