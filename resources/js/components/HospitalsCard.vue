<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div id="today" class="text-center fw-bold">
                <h3 class="mb-6">Сегодня - {{ $dayjs().format('DD.MM.YYYY') }}</h3>
            </div>

            <div v-if="success" class="alert alert-success" role="alert">
                {{success}}
            </div>

            <errors-validation :validationErrors="errs"/>

            <div v-if="warning" class="alert alert-warning" role="alert">
                {{warning}}
            </div>

            <div v-else-if="bookings.length > 0">
                <div v-if="auth_user && auth_user.email_verified_at && [roles.admin, roles.dispatcher].includes(auth_user.role_name)">
                    <button type="button" class="btn btn-primary" @click.prevent="addBooking()">
                        <font-awesome-icon icon="fa-solid fa-plus" /> Добавить бронь
                    </button>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 mb-3" v-for="(booking, index) in bookings" :key="index">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex bd-highlight mb-3">
                                    <div class="me-auto p-1 bd-highlight col-5 fw-bolder">
                                        {{booking.hospital_name}}
                                    </div>
                                    <div v-if="auth_user" class="p-1 bd-highlight align-self-center">
                                        <table class="table table-sm table-borderless m-0">
                                            <tbody>
                                            <tr>
                                                <th class="py-0">Деж. Хирург</th>
                                                <td class="py-0">
                                                    {{booking.surgeon_last_name}}
                                                    {{booking.surgeon_first_name.charAt(0)}}.
                                                    {{booking.surgeon_patronymic.charAt(0)}}.
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="py-0">Деж. Кардиолог</th>
                                                <td class="py-0">
                                                    {{booking.cardiologist_last_name}}
                                                    {{booking.cardiologist_first_name.charAt(0)}}.
                                                    {{booking.cardiologist_patronymic.charAt(0)}}.
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="container">
                                    <div v-for="(room, j) in booking.rooms" :key="j">
                                        <div class="row bg-gray-300 fw-bolder">
                                            Операционная {{ room.name }}
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col border p-3 rounded text-center"
                                                 v-for="(val, k) in room.val" :key="k"
                                                 :class="'bg-'+statuses[val.status].color+'-300'"
                                            >
                                                <div><h4>{{$dayjs(val.time).format('HH:mm')}}</h4></div>
                                                <div class="text-white fw-bolder">{{statuses[val.status].label}}</div>
                                            </div>
                                            <!--                                        <div class="col d-none d-sm-block border bg-yellow-300 p-3 rounded text-center">-->
                                            <!--                                            <div><h4>14:00</h4></div>-->
                                            <!--                                            <div class="text-white fw-bolder">Бронь</div>-->
                                            <!--                                        </div>-->
                                        </div>
                                    </div>
                                </div>
                                <router-link v-if="auth_user && auth_user.email_verified_at"
                                             :to="{name: 'hospital_booking', params: {id: booking.hospital_id}}"
                                             type="button" class="btn btn-primary">
                                    Посмотреть больше
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalBooking" data-bs-backdrop="static"
                 data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalBookingLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalBookingLabel">Добавить бронь</h5>
                            <button type="button" class="btn-close" @click.prevent="closeModal()"/>
                        </div>
                        <div class="modal-body">

                            <errors-validation :validationErrors="errs"/>

                            <form>
                                <div class="mb-3">
                                    <label for="address_sick" class="col-form-label">Введите адрес пациента</label>
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
                                      Поле адрес пациента обязательное поле для заполнения
                                    </span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label">Укажите диагноз</label>
                                    <Multiselect
                                        v-model="v$.disease_id.$model"
                                        :class="v$.disease_id.$error ? 'border-danger' : ''"
                                        :close-on-select="true"
                                        :hide-selected="false"
                                        label="name"
                                        valueProp="id"
                                        :options="diseases"
                                    />
                                    <span v-if="v$.disease_id.$error" :class="v$.disease_id.$error ? 'text-danger' : ''">
                                      Поле диагноз обязательное поле для заполнения
                                </span>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" :disabled="processing" @click.prevent="hospitalSearch()"
                                            class="btn btn-primary btn-block">
                                        {{ processing ? wait : "Поиск ближайшей больницы" }}
                                    </button>
                                </div>
                            </form>

                            <!--                        После того как нашли ближайщую свободную больницу-->
                            <div v-if="free_hospital">
                                <h4>Ближайщая свободная больница</h4>
                                <div class="fs-4 fw-bolder">{{free_hospital.full_name}}<br></div>
                                <div class="fs-5 fw-bolder">{{free_hospital.address}}<br></div>
                                <div class="fs-4">
                                    Забронировать на
                                    <div class="form-check form-check-inline" v-for="el in 3">
                                        <input class="form-check-input" type="radio"
                                               name="inlineRadioOptions" :id="'inlineRadio4'+el"
                                               :value="el" v-model="clock">
                                        <label class="form-check-label" :for="'inlineRadio4'+el">{{el}}</label>
                                    </div>
                                    часа
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click.prevent="closeModal()">Отмена</button>
                            <button v-if="free_hospital" type="button" :disabled="processing"
                                    @click.prevent="saveBooking()" class="btn btn-primary">
                                {{ processing ? wait : "Забронировать" }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import {ref} from 'vue';
import useValidate from '@vuelidate/core'
import { required} from '@vuelidate/validators'
import { Modal } from 'bootstrap'
import {roles, wait, statuses, server_url} from "../consts";

export default {
    name: "HospitalsCard",
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
            url: import.meta.env.VITE_APP_DADATA_URL,
            token: import.meta.env.VITE_APP_DADATA_API_KEY,
            suggestions: true,
            disease_id: null,
            processing: false,
            timeout: null,
            myModal: null,
            success: null,
            clock: 2,
            warning: null,
            bookings: [],
            diseases: [],
            free_hospital: null,
            roles,
            wait,
            statuses,
            server_url,
        }
    },
    validations() {
        return {
            suggestion: { required },
            query: { required },
            disease_id: { required },
        }
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null
        },
        app_url() {
            return import.meta.env.VITE_APP_URL;
        }
    },
    mounted() {
        const socket = io(this.server_url);

        socket.on('bookings-update:App\\Events\\BookingsUpdateEvent', (data) => {
            let arr = data.result;
            this.updateHospitalRoomStatus(arr);
        });
        socket.on('todays-update:App\\Events\\TodaysUpdateEvent', (data) => {
            let orderly = data.result;
            this.updateHospitalOrderly(orderly);
        });
        socket.on('bookings-store:App\\Events\\BookingsStoreEvent', (data) => {
            // console.log(data);
            let arr = data.result;
            this.updateHospitalRoomStatus(arr);
        });

        this.getData();
    },
    beforeUnmount() {
        if (this.myModal) this.myModal.hide();
    },
    methods: {
        getData() {
            axios.get(`/api/todays/`,{
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                let element = res.data.data.find(el => el.surgeon_id == '')
                if (typeof element !== 'undefined')  {
                    this.warning = `${element.hospital_name} не указала дежурных врачей!
                    Дождитесь пока укажут дежурных врачей, после чего вы получите возможность увидеть
                     график свободных операционных.`
                } else {
                    this.getBooking()
                }
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
        getBooking() {
            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
            }).then(res => {
                console.log(res);
                this.bookings = res.data;
            }).catch(err => {
                this.errorsMessage(err);
            });
        },
        updateHospitalRoomStatus(arr) {
            let hospitalIndex = this.bookings.findIndex(element => element.hospital_id == arr[0].hospital_id);
            if (hospitalIndex != -1) {
                let roomIndex = this.bookings[hospitalIndex].rooms.findIndex(el => el.id == arr[0].room_id);
                if (roomIndex != -1) {
                    arr.forEach(el => {
                        let valIndex = this.bookings[hospitalIndex].rooms[roomIndex].val.findIndex(e => e.time == el.date_time);
                        if (valIndex != -1) {
                            this.bookings[hospitalIndex].rooms[roomIndex].val[valIndex].status = el.status
                        }
                    })
                }
            }
        },
        updateHospitalOrderly(data) {
            let hospitalIndex = this.bookings.findIndex(element => element.hospital_id == data.hospital_id);
            if (hospitalIndex != -1) {
                this.bookings[hospitalIndex].cardiologist_id = data.cardiologist_id
                this.bookings[hospitalIndex].cardiologist_last_name = data.cardiologist_last_name
                this.bookings[hospitalIndex].cardiologist_first_name = data.cardiologist_first_name
                this.bookings[hospitalIndex].cardiologist_patronymic = data.cardiologist_patronymic
                this.bookings[hospitalIndex].surgeon_id = data.surgeon_id
                this.bookings[hospitalIndex].surgeon_last_name = data.surgeon_last_name
                this.bookings[hospitalIndex].surgeon_first_name = data.surgeon_first_name
                this.bookings[hospitalIndex].surgeon_patronymic = data.surgeon_patronymic
            }
        },
        addBooking() {
            this.free_hospital = null
            this.errs = null;
            this.myModal = new Modal(document.getElementById('modalBooking'), {});
            axios.get(`/api/bookings/create/disease`,{
                headers: {Authorization: localStorage.getItem('access_token')},
            }).then(res => {
                console.log(res);
                this.diseases = res.data.disease;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.myModal.show());
        },
        hospitalSearch() {
            this.errs = null;
            this.v$.$validate();
            if (!this.v$.$error) {
                this.processing = true;
                axios.get(`/api/bookings/create/hospital`, {
                    headers: {Authorization: localStorage.getItem('access_token')},
                    params: {
                            geo_lat: this.suggestion.data.geo_lat,
                            geo_lon: this.suggestion.data.geo_lon
                    }
                    }).then(res => {
                    console.log(res);
                    this.free_hospital = res.data.data;
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                })
            }
        },
        saveBooking() {
            this.processing = true;
            this.errs = null;
            axios.post(`/api/bookings`,
                {
                    disease_id: this.disease_id,
                    dispatcher_id: this.auth_user.id,
                    hospital_id: this.free_hospital.id,
                    booking_hours: this.clock
                },
                {headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                let data = res.data.data;
                let hospital_index = this.bookings.findIndex(el => el.hospital_id == data[0].hospital_id);
                let room_index = this.bookings[hospital_index].rooms.findIndex(el => el.name == data[0].room_name)
                let time_index = this.bookings[hospital_index].rooms[room_index].val.findIndex(el => el.time == data[0].date_time)
                for (let i = time_index; i < time_index+data.length; i++) {
                    if (i >= this.bookings[hospital_index].rooms[room_index].val.length) {
                        this.bookings[hospital_index].rooms[room_index].val[i-this.bookings[hospital_index].rooms[room_index].val.length].status = data[0].status;
                    } else {
                        this.bookings[hospital_index].rooms[room_index].val[i].status = data[0].status;
                    }
                }
                this.success = 'Бронь успешно дабавлена.';
                setTimeout(()=>{
                    this.success = null;
                },4000)
                this.myModal.hide();
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => {
                this.processing = false;
            })

        },
        closeModal() {
            this.free_hospital = null;
            this.errors = null;
            this.myModal.hide()
        },
        lazyCaller(value, time = 1000) {
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => {
                this.query = value;
                let options = {
                    method: "POST",
                    mode: "cors",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "Authorization": "Token " + this.token
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
        }

    },



}
</script>

<!--<style>-->
<!--.autocomplete {-->
<!--    /*the container must be positioned relative:*/-->
<!--    position: relative;-->
<!--    /*display: inline-block;*/-->
<!--}-->

<!--.autocomplete-items {-->
<!--    position: absolute;-->
<!--    border: 1px solid #d4d4d4;-->
<!--    border-bottom: none;-->
<!--    border-top: none;-->
<!--    z-index: 99;-->
<!--    /*position the autocomplete items to be the same width as the container:*/-->
<!--    top: 100%;-->
<!--    left: 0;-->
<!--    right: 0;-->
<!--}-->
<!--.autocomplete-items div {-->
<!--    /*padding: 10px;*/-->
<!--    cursor: pointer;-->
<!--    background-color: #fff;-->
<!--    border-bottom: 1px solid #d4d4d4;-->
<!--}-->
<!--autocomplete-disabled-item  div {-->
<!--    border-bottom: 1px solid #d4d4d4;-->
<!--}-->
<!--.autocomplete-items div:hover {-->
<!--    /*when hovering an item:*/-->
<!--    background-color: #e9e9e9;-->
<!--}-->

<!--.bg-gray-300 {-->
<!--    background-color: #e9ecef;-->
<!--}-->
<!--.bg-red-400 {-->
<!--    background-color: #e35d6a;-->
<!--}-->
<!--.bg-green-400 {-->
<!--    background-color: #479f76;-->
<!--}-->
<!--.bg-yellow-400 {-->
<!--    background-color: #ffcd39;-->
<!--}-->

<!--/*.bg-red-300 {*/-->
<!--/*    background-color: #ea868f;*/-->
<!--/*}*/-->
<!--/*.bg-green-300 {*/-->
<!--/*    background-color: #75b798;*/-->
<!--/*}*/-->
<!--/*.bg-yellow-300 {*/-->
<!--/*    background-color: #ffda6a;*/-->
<!--/*}*/-->

<!--.bg-red-200 {-->
<!--    background-color: #f1aeb5;-->
<!--}-->
<!--.bg-green-200 {-->
<!--    background-color: #a3cfbb;-->
<!--}-->
<!--.bg-yellow-200 {-->
<!--    background-color: #ffe69c;-->
<!--}-->
<!--</style>-->

<style scoped>

</style>
