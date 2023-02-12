<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12" v-if="hospital">
                    <h5 class="fw-bold">Редактирвать разрешение - {{hospital.short_name}} </h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <form @submit.prevent="update" class="row" >
                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Полное наименование</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control"
                                       v-model.lazy="v$.hospital.full_name.$model"
                                       :class="v$.hospital.full_name.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.hospital.full_name.$error"
                                      :class="v$.hospital.full_name.$error ? 'text-danger' : ''">
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
                                <span v-if="v$.hospital.short_name.$error"
                                      :class="v$.hospital.short_name.$error ? 'text-danger' : ''">
                                  Кароткое наименование обязательное поле для заполнения
                            </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Адрес</label>
                            <div class="col-sm-10">

                                <div class="autocomplete">
                                    <input type="text" :value="v$.query.$model"
                                           @input="lazyCaller($event.target.value)"
                                           class="form-control" id="address_sick"
                                           :class="v$.suggestion.$error || v$.query.$error? 'border-danger' : ''"
                                    >
                                    <div v-if="addresses.length" class="autocomplete-items">
                                        <div
                                            v-for="address in addresses"
                                            :key="address.value"
                                            @click="selectAddress(address)"
                                            class="border-start border-end border-bottom"
                                        >
                                            {{ address.value }}
                                        </div>
                                    </div>
                                    <div v-else-if="!suggestions && query !=''" class="autocomplete-disabled-item">
                                        <div class="border-start border-end border-bottom" disabled="true">
                                            Неизвестный адрес
                                            {{suggestion = ''}}
                                        </div>
                                    </div>
                                    <span v-if="v$.suggestion.$error || v$.query.$error"
                                          :class="v$.suggestion.$error || v$.query.$error ? 'text-danger' : ''">
                                      Поле адрес обязательное поле для заполнения
                                    </span>
                                </div>

                                <!--                            <vue-dadata-->
                                <!--                                v-model="query"-->
                                <!--                                :token="token"-->
                                <!--                                v-model:suggestion="suggestion"-->
                                <!--                                :locationOptions="locationOptions"-->
                                <!--                            />-->
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Кабинет</label>
                            <div class="col-sm-10">
                                <div class="row my-1" v-for="(room, index) in this.hospital.rooms">
                                    <div class="col-7 col-sm-5">
                                        <input type="text" class="form-control" v-model="room.name">
                                    </div>
                                    <div class="col col-sm-3">
                                        <button type="button"
                                                @click.prevent="removeRoomm(index, room.id)"
                                                class="ml-1 mt-1 btn btn-danger btn-circle">
                                            <font-awesome-icon icon="fa-solid fa-trash-can"
                                                               :style="{ color: 'white' }" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="my-4 row">
                            <div class="col-sm-3">
                                <button type="button" @click.prevent="createRooms"
                                        class="btn btn-warning btn-block">
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
                                            <button type="button" @click.prevent="addRoomm(index)"
                                                    class="mt-1 btn btn-success btn-circle">
                                                <font-awesome-icon icon="fa-solid fa-plus" />
                                            </button>
                                            <button type="button" @click.prevent="deleteRoomm(index)"
                                                    class="ml-1 mt-1 btn btn-danger btn-circle">
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
    </div>

</template>

<script>
import useValidate from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';
// import { defineComponent, ref } from 'vue';
import { ref } from 'vue';
// import { VueDadata } from 'vue-dadata';
// import 'vue-dadata/dist/style.css';
import {wait} from "../../../consts";

// export default defineComponent ({
export default {
    name: "Edit",
    props: ['id'],
    // components: {
    //     VueDadata
    // },
    // setup() {
    //     const query = ref('');
    //     const suggestion = ref(undefined);
    //
    //     return {
    //         token: import.meta.env.VITE_APP_DADATA_API_KEY,
    //         query,
    //         suggestion,
    //         locationOptions: {
    //             locations: {
    //                 "country_iso_code": "RU",
    //                 "region_iso_code": "RU-BA"
    //             }
    //         }
    //     };
    // },
    setup() {
        const query = ref('');
        const suggestion = ref('');
        const addresses = ref([]);
        const selectAddress = (address) => {
            query.value = address.value;
            suggestion.value = address
            addresses.value = [];
        }

        return {
            query,
            suggestion,
            addresses,
            selectAddress,
        }
    },
    data() {
        return {
            v$: useValidate(),
            hospital: null,
            processing: false,
            success : null,
            rooms_show: false,
            rooms: [],

            timeout: null,
            url: import.meta.env.VITE_APP_DADATA_URL,
            token: import.meta.env.VITE_APP_DADATA_API_KEY,
            suggestions: true,

            wait,
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            hospital: {
                full_name: {required},
                short_name: {required},
            },
            suggestion: { required },
            query: { required },
        }
    },
    methods: {
        getData() {
            axios.get(`/api/hospitals/${this.id}/edit`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.hospital = res.data.data;
                this.query = this.hospital.address;
                // this.suggestion = this.hospital.address;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
        createRooms() {
            if (this.rooms.length == 0) {
                this.rooms.push({name: null})
            }
            this.rooms_show = !this.rooms_show;
        },
        addRoomm(index) {
            this.rooms.splice(index+1, 0, {name: null})
        },
        deleteRoomm(index) {
            this.rooms.splice(index, 1);
            if (this.rooms.length == 0) this.rooms_show=false;
        },
        removeRoomm(index, room_id) {
            axios.delete(`/api/hospital_rooms/${room_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res)
                this.hospital.rooms.splice(index, 1);
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        update() {
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            this.rooms = this.rooms.filter((el, index) => {
                return  el.name !== null && el.name !== ''
            });
            let rooms = this.hospital.rooms.concat(this.rooms);
            if (!this.v$.$error) {
                this.processing = true;
                let data = {};
                if (typeof this.suggestion == 'undefined') {
                    data = {
                        full_name: this.hospital.full_name,
                        short_name: this.hospital.short_name,
                        address: this.query,
                        hospital_rooms: rooms
                    }
                } else {
                    data = {
                        full_name: this.hospital.full_name,
                        short_name: this.hospital.short_name,
                        address: this.suggestion.unrestricted_value,
                        geo_lat: this.suggestion.data.geo_lat,
                        geo_lon: this.suggestion.data.geo_lon,
                        hospital_rooms: rooms
                    }
                }
                axios.patch(`/api/hospitals/${this.id}`, data,
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.hospital = res.data.data;
                    this.success = 'Данные успешно изменены. Перенаправление...';
                    this.$emit('change_data', res.data.data)
                    setTimeout(()=>{
                        this.$router.push({name:'hospitals'})
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                })
            } else {
                window.scrollTo(0,0);
            }
        },
        lazyCaller(value, time = 1000) {
            // console.log(this.url)
            this.errs = null;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.query = value;
                // "Access-Control-Allow-Origin": "http://127.0.0.1:8000"
                let options = {
                    method: "POST",
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Token " + this.token,

                    },
                    body: JSON.stringify({
                        query: this.query,
                        locations: {
                            "country_iso_code": "RU",
                            "region_iso_code": "RU-BA",
                        },
                        restrict_value: true,
                    })
                }
                fetch(this.url, options)
                    .then(response => response.text())
                    .then(result => {
                        // console.log(result);
                        // console.log(JSON.parse(result));
                        let address = JSON.parse(result)
                        this.addresses = address.suggestions;
                        this.suggestions = this.addresses.length == 0 ? false : true
                    })
                    .catch(err => this.errorsMessage(err));
            }, time)
        }
    }
}
// });
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
</style>
