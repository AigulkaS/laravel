<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить больницу</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>


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

                            <!--                        <div class="col-sm-10">-->
                            <!--                            <vue-dadata-->
                            <!--                                v-model="query"-->
                            <!--                                :token="token"-->
                            <!--                                v-model:suggestion="suggestion"-->
                            <!--                                :locationOptions="locationOptions"-->
                            <!--                            />-->
                            <!--                        </div>-->
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
                                            <input type="text" class="form-control" v-model="room.name" @change="verificationTime(room)">
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
                                    <div class="my-1">
                                        <label class=" col-form-label fw-bold ">Время работы: </label>
                                        <span class="mx-2">с</span>
                                        <select class="form-control form-inline form-select"
                                                v-model="room.start" :disabled="room.round_the_clock"
                                                @change="verificationTime(room)"
                                        >
                                            <option v-for="(clock, i) in timepiece" :key="i" :value="clock">
                                                {{ clock }}
                                            </option>
                                        </select>
                                        <span class="mx-2">по</span>
                                        <select class="form-control form-inline form-select"
                                                v-model="room.end" :disabled="room.round_the_clock"
                                                @change="verificationTime(room)"
                                        >
                                            <option v-for="(clock, i) in timepiece" :key="i" :value="clock">
                                                {{ clock }}
                                            </option>
                                        </select>
                                        <div v-if="room.error" class="pl-sm-4 text-danger">
                                            {{room.error}}
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" :value="true"
                                                   v-model="room.round_the_clock" id="day_and_night"
                                                   @change="chekedChanged(room)">
                                            <label class="form-check-label" for="day_and_night">
                                                Круглосуточно
                                            </label>
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
import { required } from '@vuelidate/validators';
// import { defineComponent, ref } from 'vue';
import { ref } from 'vue';
// import { VueDadata } from 'vue-dadata';
// import 'vue-dadata/dist/style.css';
import {wait, hospital_type, timepiece} from "../../../consts";

// export default defineComponent ({
export default {
    name: "Create",
    // components: {
    //   VueDadata
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
    //                 "region_iso_code": "RU-BA",
    //                 "restrict_value": true,
    //             },
    //         }
    //     };
    // },
    data() {
        return {
            v$: useValidate(),
            hospital: {
                full_name: null,
                short_name: null,
                address: null,
            },
            processing: false,
            success : null,
            rooms: [],
            rooms_show: false,


            timeout: null,
            url: import.meta.env.VITE_APP_DADATA_URL,
            token: import.meta.env.VITE_APP_DADATA_API_KEY,
            suggestions: true,

            wait,
            hospital_type,
            timepiece

        }
    },
    mounted() {
        // this.getData();
        this.successPage = true
        // console.log(this.suggestion)
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
            axios.get('/api/hospitals/create', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                // this.roles = res.data.roles;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        createRooms() {
            if (this.rooms.length == 0) {
                this.rooms.push({name: null, start: null, end:null, round_the_clock: true, error: null})
            }
            this.rooms_show = !this.rooms_show;
        },
        addRoomm(index) {
            // this.rooms.push({name: null})
            this.rooms.splice(index+1, 0, {name: null, start: null, end:null, round_the_clock: true, error: null})
        },
        deleteRoomm(index) {
            this.rooms.splice(index, 1);
            if (this.rooms.length == 0) this.rooms_show=false;
        },
        store() {
            // console.log(this.suggestion)
            // console.log(this.suggestion.unrestricted_value)
            this.errs = null
            this.success = null;
            let timeErr = this.rooms.findIndex(el => el.error != null);

            this.v$.$validate() // checks all inputs
            if (!this.v$.$error && timeErr == -1) {
                this.processing = true;

                this.rooms = this.rooms.filter((el, index) => {
                    return  el.name !== null && el.name !== ''
                })
                this.rooms.forEach(el => {
                    if (!el.start) {
                        delete el.start;
                        delete el.end;
                    }
                    delete el.round_the_clock;
                    delete el.error;
                });

                axios.post('/api/hospitals', {
                        type:  hospital_type.hospital,
                        full_name: this.hospital.full_name,
                        short_name: this.hospital.short_name,
                        address: this.suggestion.unrestricted_value,
                        geo_lat: this.suggestion.data.geo_lat,
                        geo_lon: this.suggestion.data.geo_lon,
                        hospital_rooms: this.rooms

                    },
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.success = 'Больница успешно добавлена. Перенаправление...';
                    this.$emit('add_data', res.data.data)
                    setTimeout(()=>{
                        this.$router.push({name:'hospitals'})
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => this.processing = false)
            } else {
                window.scrollTo(0,0);
            }

        },
        lazyCaller(value, time = 1000) {
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
                        console.log(JSON.parse(result));
                        let address = JSON.parse(result)
                        this.addresses = address.suggestions;
                        this.suggestions = this.addresses.length == 0 ? false : true
                    })
                    .catch(err => this.errorsMessage(err));
            }, time)
        },
        chekedChanged(room) {
            console.log(room);
            if (room.round_the_clock) {
                room.start = null;
                room.end = null;
            }
        },
        verificationTime(room) {
            if (!room.start && room.end) {
                room.error = 'Укажите время начала и конца работы'
            } else if (room.start && room.end==room.start) {
                room.error = 'Время начала и конца совпадают. Укажите, время работы крулосуточно'
            } else if (room.start && room.end && Number(room.start.substr(0, 2)) > Number(room.end.substr(0, 2)) ) {
                room.error = 'Время начала не должно быть больше время конца'
            } else if (!room.name || room.name == '') {
                console.log(room.name)
                room.error = 'Поле Кабинет не может быть пустым'
            } else {
                room.error = null;
            }

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
