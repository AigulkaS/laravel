<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" :class="$route.name == 'hospitals_booking' ? 'container' : ''">
            <div id="today" class="text-center fw-bold">
                <h3 class="mb-6">Сегодня - {{ $dayjs().format('DD.MM.YYYY') }}</h3>
            </div>

            <div v-if="success" class="alert alert-success" role="alert">
                {{success}}
            </div>

            <errors-validation :validationErrors="errs"/>

<!--            <button v-if="auth_user && auth_user.role_name == 'admin'" type="button" class="btn btn-primary" @click.prevent="pushDemo()">-->
<!--                PushDemo11-->
<!--            </button>-->

            <div v-if="auth_user && warning" class="alert alert-warning" role="alert">
                {{warning}}
                <div>
                    <div class="fw-bolder">Больницы, не указавщие дежурных врачей:</div>
                    <ul>
                        <li v-for="hospital in emptyOrderlies">{{hospital.hospital_name}}</li>
                    </ul>
                </div>
                <div v-if="auth_user && auth_user.role_name == roles.admin">
                    <div>
                        <b>Только для админа</b>
                        <br>
                        <button type="button" @click="addOrderlies()" class="btn btn-primary">
                            <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                        </button>
                    </div>
                </div>
            </div>


<!--            <div v-else-if="bookings.length > 0">-->
            <div>
                <div class="text-center" v-if="auth_user && auth_user.email_verified_at && [roles.admin, roles.smp, roles.moderator_smp].includes(auth_user.role_name)">
                    <button type="button" class="btn btn-lg btn-danger" @click.prevent="addBooking()">
                        Диагностирован ОКС
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
<!--                                            :class="'bg-'+statuses[val.status].color+'-300'"-->
<!--                                            :class="addCssClass(room, '')"-->
                                            <div class="col border p-3 rounded text-center"
                                                 v-for="(val, k) in room.val" :key="k"
                                                 :class="addCssClass(room, val)">
                                                <div>
                                                    <h4>
                                                        {{$dayjs(val.time).format('HH:mm')}}
                                                        <div class="line_height05">-</div>
                                                        {{ $dayjs(val.time).add(1, 'hour').format('HH:mm') }}
                                                    </h4>
                                                </div>
                                                <div class="fw-bolder" :class="colorRoomOffTime(room,val) ? 'text-black' : 'text-white'">
                                                    {{colorRoomOffTime(room,val) ? statuses[3].label : statuses[val.status].label}}
                                                </div>
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
                                    <label class="col-form-label">Состояние пациента</label>
                                    <Multiselect
                                        v-model="v$.condition_id.$model"
                                        :class="v$.condition_id.$error ? 'border-danger' : ''"
                                        :close-on-select="true"
                                        :hide-selected="false"
                                        label="name"
                                        valueProp="id"
                                        :options="conditions"
                                    />
                                    <span v-if="v$.condition_id.$error" :class="v$.condition_id.$error ? 'text-danger' : ''">
                                      Поле состояние пациента обязательное поле для заполнения
                                    </span>
                                </div>

<!--                                <div class="mb-3">-->
<!--                                    <button type="submit" :disabled="processing" @click.prevent="hospitalSearch()"-->
<!--                                            class="btn btn-primary btn-block">-->
<!--                                        {{ processing ? wait : "Поиск ближайшей больницы" }}-->
<!--                                    </button>-->
<!--                                </div>-->
                            </form>

                            <!--                        После того как нашли ближайщую свободную больницу-->
<!--                            <div v-if="free_hospital">-->
<!--                                <h4>Ближайщая свободная больница</h4>-->
<!--                                <div class="fs-4 fw-bolder">{{free_hospital.full_name}}<br></div>-->
<!--                                <div class="fs-5 fw-bolder">{{free_hospital.address}}<br></div>-->
<!--                                <div class="fs-4">-->
<!--                                    Забронировать на-->
<!--                                    <div class="form-check form-check-inline" v-for="el in 3">-->
<!--                                        <input class="form-check-input" type="radio"-->
<!--                                               name="inlineRadioOptions" :id="'inlineRadio4'+el"-->
<!--                                               :value="el" v-model="clock">-->
<!--                                        <label class="form-check-label" :for="'inlineRadio4'+el">{{el}}</label>-->
<!--                                    </div>-->
<!--                                    часа-->
<!--                                </div>-->
<!--                            </div>-->

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click.prevent="closeModal()">Отмена</button>
<!--                            <button v-if="free_hospital" type="button" :disabled="processing"-->
<!--                                    @click.prevent="saveBooking()" class="btn btn-primary">-->
<!--                                {{ processing ? wait : "Забронировать" }}-->
<!--                            </button>-->
                            <button type="button" :disabled="processing"
                                    @click.prevent="saveBooking()" class="btn btn-primary">
                                {{ processing ? wait : "Забронировать" }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal Orderly -->
            <div class="modal fade" id="modalOrderlyHospital"
                 data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalOrderlyLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalOrderlyLabel">
                                Добавить дежурных
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-primary">
                                Дежурные на {{$dayjs().set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm')}} - {{$dayjs().add(1, 'day').set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm')}}
                            </div>
                            <form class="row">
                                <div class="form-group row my-1">
                                    <label class="col-sm-5 col-form-label fw-bold">
                                        Выберите больницу
                                    </label>
                                    <div class="col-sm-7">
                                        <select class="form-control form-select" v-model="v$.hospital_id.$model"
                                                :class="v$.hospital_id.$error ? 'border-danger' : ''">
                                            <option v-for="hospital in emptyOrderlies" :key="hospital.hospital_id"
                                                    :value="hospital.hospital_id">
                                                {{ hospital.hospital_name }}
                                            </option>
                                        </select>
                                        <span v-if="v$.hospital_id.$error" class="text-danger">
                                          Выберите больницу обязательное поле для заполнения
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row my-1">
                                    <label class="col-sm-5 col-form-label fw-bold">
                                        Деж. кардиолог
                                    </label>
                                    <div class="col-sm-7">
                                        <select class="form-control form-select" v-model="v$.cardiologist_id.$model"
                                                :class="v$.cardiologist_id.$error ? 'border-danger' : ''">
                                            <option v-for="cardiologist in cardiologists" :key="cardiologist.id"
                                                    :value="cardiologist.id">
                                                {{ cardiologist.last_name }} {{cardiologist.first_name}} {{cardiologist.patronymic}}
                                            </option>
                                        </select>
                                        <span v-if="v$.cardiologist_id.$error" class="text-danger">
                                          Деж. кардиолог обязательное поле для заполнения
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group row my-1">
                                    <label class="col-sm-5 col-form-label fw-bold">
                                        Деж. хирург
                                    </label>
                                    <div class="col-sm-7">
                                        <select class="form-control form-select" v-model="v$.surgeon_id.$model"
                                                :class="v$.surgeon_id.$error ? 'border-danger' : ''">
                                            <option v-for="surgeon in surgeons" :key="surgeon.id" :value="surgeon.id">
                                                {{ surgeon.last_name }} {{surgeon.first_name}} {{surgeon.patronymic}}
                                            </option>
                                        </select>
                                        <span v-if="v$.surgeon_id.$error" class="text-danger">
                                          Деж. хирург обязательное поле для заполнения
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <button type="button" @click.prevent="updateOrderly()" :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? wait : "Сохранить" }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Hospital Address -->
            <div class="modal fade" id="modalAddressHospital"
                 data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddressLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAddressLabel">
                                Бронь создана
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" v-if="messages">
                           <div class="fs-4 fw-bold">{{messages.hospital}}</div>
                           <div class="fs-5 fw-bolder">{{messages.address}}</div>
                           <div class="fs-5"><span class="fw-bolder">Диагноз:</span> {{messages.disease}}</div>
                           <div class="fs-5"><span class="fw-bolder">Состояние пациента:</span> {{messages.condition}}</div>
                           <div class="fs-5"><span class="fw-bolder">Время брониования:</span> {{messages.time}}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ОК</button>
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
import {roles, wait, statuses, server_url, hospital_type} from "../consts";

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
            condition_id: null,
            processing: false,
            timeout: null,
            myModal: null,
            success: null,
            clock: 2,
            warning: null,
            bookings: [],
            diseases: [],
            conditions: [],
            free_hospital: null,
            emptyOrderlies: [],
            hospital_id: null,
            surgeon_id: null,
            cardiologist_id: null,
            hospitals: [],
            myModalOrderly: null,
            cardiologists: [],
            surgeons: [],
            myModalAddress: null,
            messages: null,

            roles,
            wait,
            statuses,
            server_url,
            hospital_type,
        }
    },
    validations() {
        return {
            suggestion: { required },
            query: { required },
            disease_id: { required },
            condition_id: { required },

            hospital_id: { required },
            cardiologist_id: { required },
            surgeon_id: { required },
        }
    },
    watch: {
        hospital_id (newValue, oldValue) {
            if (newValue !== oldValue) {
                this.errs = null;
                this.cardiologist_id = null;
                this.surgeon_id = null;
                axios.get(`/api/operators/edit`,{
                    headers: {Authorization: localStorage.getItem('access_token')},
                    params: {hospital_id: newValue}
                }).then(res => {
                    console.log(res);
                    this.cardiologists = res.data.cardiologist;
                    this.surgeons = res.data.surgeons;
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.myModalOrderly.show();
                });
            }

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
        // const socket = io(this.server_url);
        //
        // socket.on('bookings-update:App\\Events\\BookingsUpdateEvent', (data) => {
        //     let arr = data.result;
        //     this.updateHospitalRoomStatus(arr);
        // });
        // socket.on('operators-update:App\\Events\\OperatorsUpdateEvent', (data) => {
        //     let orderly = data.result;
        //     this.updateHospitalOrderly(orderly);
        // });
        // socket.on('bookings-store:App\\Events\\BookingsStoreEvent', (data) => {
        //     console.log(data);
        //     let arr = data.result;
        //     this.updateHospitalRoomStatus(arr.bookings)
        // });
        // socket.on('bookings-index:App\\Events\\BookingsIndexEvent', (data) => {
        //     let result = data.result;
        //     console.log(result)
        //     this.allBookings(result);
        // });

        this.getData();
    },
    beforeUnmount() {
        if (this.myModal) this.myModal.hide();
        if (this.myModalOrderly) this.myModalOrderly.hide();
        if (this.myModalAddress) this.myModalAddress.hide();
    },
    methods: {
        getData() {
            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
            }).then(res => {
                console.log(res);
                this.allBookings(res.data);

            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
        allBookings(data) {
            this.bookings = data;
            this.bookings.forEach(el => {
                if (el.surgeon_id == 'default') {
                    this.emptyOrderlies.push({hospital_id: el.hospital_id, hospital_name: el.hospital_name})
                }
            });
            if (this.emptyOrderlies.length > 0) {
                this.warning = `Не указаны дежурные врачи больниц!
                    Врачи не получат уведомления о забронированных операционных.`
            }
        },
        // getBooking() {
        //     axios.get(`/api/bookings`,{
        //         headers: {Authorization: localStorage.getItem('access_token')},
        //     }).then(res => {
        //         console.log(res);
        //         this.bookings = res.data;
        //     }).catch(err => {
        //         this.errorsMessage(err);
        //     }).finally(() => this.successPage = true);
        // },
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
        addBooking() {
            this.v$.$reset();
            this.free_hospital = null
            this.errs = null;
            this.suggestion = '';
            this.query = '';
            this.disease_id = null;
            this.condition_id = null;

            if (!this.myModal) {
                this.myModal = new Modal(document.getElementById('modalBooking'), {});
                axios.get(`/api/bookings/create/disease`,{
                    headers: {Authorization: localStorage.getItem('access_token')},
                }).then(res => {
                    console.log(res);
                    this.diseases = res.data.disease;
                    this.conditions = res.data.conditions;
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => this.myModal.show());
            } else {
                this.myModal.show()
            }

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
            this.errs = null;
            this.v$.$validate();
            if (!this.v$.suggestion.$error && !this.v$.disease_id.$error && !this.v$.condition_id.$error) {
                this.processing = true;
                axios.post(`/api/bookings`,
                    {
                        geo_lat: this.suggestion.data.geo_lat,
                        geo_lon: this.suggestion.data.geo_lon,
                        disease_id: this.disease_id,
                        condition_id: this.condition_id,
                    },
                    {
                        headers: {Authorization: localStorage.getItem('access_token')}
                    }).then(res => {
                    console.log(res);
                    this.updateHospitalRoomStatus(res.data.bookings)
                    this.messages = res.data.messages;
                    if (!this.myModalAddress) {
                        this.myModalAddress = new Modal(document.getElementById('modalAddressHospital'), {});
                    }
                    this.myModal.hide();
                    this.myModalAddress.show();
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                });
            }

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
        },
        pushDemo() {
            console.log('push.demo')
            axios.get('/api/push', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
              console.log(res)
            }).catch(err => {
                console.log(err)
                console.log(err.response)
            })
        },
        addOrderlies() {
            this.v$.$reset();
            if (!this.myModalOrderly) {
                this.myModalOrderly = new Modal(document.getElementById('modalOrderlyHospital'), {});
            }
            this.myModalOrderly.show()
        },
        updateOrderly() {
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            // if (!this.v$.$error) {
            if (!this.v$.hospital_id.$error && !this.v$.surgeon_id.$error && !this.v$.cardiologist_id.$error) {
                console.log(this.$dayjs().format('YYYY-MM-DD HH:mm:ss'))
                this.processing = true;
                axios.post(`/api/operators`,
                    {
                        date: this.$dayjs().set('hour', 0).set('minute', 0).set('second', 0).format('YYYY-MM-DD HH:mm:ss'),
                        hospital_id: this.hospital_id,
                        cardiologist_id: this.cardiologist_id,
                        surgeon_id: this.surgeon_id,
                    },
                    {
                        headers: {Authorization: localStorage.getItem('access_token')}
                    }).then(res => {
                    console.log(res);
                    this.updateHospitalOrderly(res.data.data);
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                    this.myModalOrderly.hide();
                })
            } else {
                window.scrollTo(0,0);
            }
        },
        updateHospitalOrderly(data) {
            if (this.$dayjs(data.date).format("YY-MM-DD") == this.$dayjs().format('YY-MM-DD')
                ||
                (this.$dayjs().format('YY-MM-DD') > this.$dayjs(data.date).format("YY-MM-DD")
                    && Number(this.$dayjs().format('HH')) < 8)) {
                this.bookings.find(el => {
                    if (el.hospital_id == data.hospital_id) {
                        el.cardiologist_id = data.cardiologist_id;
                        el.cardiologist_first_name = data.cardiologist_first_name;
                        el.cardiologist_last_name = data.cardiologist_last_name;
                        el.cardiologist_patronymic = data.cardiologist_patronymic;

                        el.surgeon_id = data.surgeon_id;
                        el.surgeon_first_name = data.surgeon_first_name;
                        el.surgeon_last_name = data.surgeon_last_name;
                        el.surgeon_patronymic = data.surgeon_patronymic;
                    }
                });
                let index = this.emptyOrderlies.findIndex(el => el.hospital_id == data.hospital_id);
                if (index > -1) {
                    this.emptyOrderlies.splice(index, 1);
                }
                if (this.emptyOrderlies.length == 0) {
                    this.warning = false;
                }
            }
        },
        addCssClass(room, val) {
            let columnColor = this.colorRoomOffTime(room,val)
                    ? 'bg-gray-300' : val.status == 0
                        ? 'bg-green-300' : val.status == 1
                            ? 'bg-red-300' : 'bg-yellow-300';


            let str = `${columnColor}`

            return str
        },
        colorRoomOffTime(room, val ) {
            if (!room.start) return false;
            if (room.start.substr(0, 2) == room.end.substr(0, 2)) {
                return false
            } else if (Number(room.start.substr(0, 2)) < Number(room.end.substr(0, 2))) {
                if (this.$dayjs(val.time).get('hour') >= Number(room.start.substr(0, 2))
                    && this.$dayjs(val.time).get('hour') <= Number(room.end.substr(0, 2))-1) {
                    return false
                } else return true
            } else { //start > end
                if ((this.$dayjs(val.time).get('hour') >= Number(room.start.substr(0, 2))
                        && this.$dayjs(val.time).get('hour') <= 23) ||
                    (this.$dayjs(val.time).get('hour') <= Number(room.end.substr(0, 2))-1)) {
                    return false
                } else return true
            }
        },
    },



}
</script>
