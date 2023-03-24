<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
<!--            <div class="container" v-if="orderly">-->
<!--                <h4 v-if="orderly">{{orderly.hospital_name}}</h4>-->
            <div class="container">
                <h4 class="mb-3">{{hospital_name}}</h4>

                <!--Errors-->
                <errors-validation :validationErrors="errs"/>

                <!--Badge Statuses-->
                <div>
                    <h3 v-for="status in statuses" class="d-inline-flex me-1">
                            <span class="badge" :class="'bg-'+status.color+'-300'" style="color: black">
                                {{status.label}}
                            </span>
                    </h3>
                </div>

                <div class="row">

                    <div class="col-sm-6">

                        <!--Add Orderlies-->

                        <!--Для просмотра и редактирования графика занятости операционных-->
                        <div v-if="canAddOrderly(dateToday.date)" class="alert alert-warning fw-bolder" role="alert">
                            Укажите дежурного хирирга и кардиолога на
                            {{ $dayjs(dateToday.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                            {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}.
                            <div class="mt-3">
                                <button type="button" @click="modalOrder(dateToday)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                                </button>
                            </div>
                        </div>

                        <!--Orderly FIO-->
<!--                        <div v-else>-->
                        <div>
                            <div class="mt-3" v-if="canUpdate(dateToday.date)">
                                <button type="button" @click="modalOrder(dateToday)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Сменить дежурных
                                </button>
                            </div>
                            <div class="d-flex my-3">
                                <div class="me-auto fs-5">
                                    <div>
                                        <span class="fw-bold">Деж. кардиолог: </span>
                                        <span>{{dateToday.cardiologist_last_name}} {{dateToday.cardiologist_first_name}} {{dateToday.cardiologist_patronymic}}</span>
                                    </div>
                                    <div>
                                        <span class="fw-bold">Деж. хирург: </span>
                                        <span>{{dateToday.surgeon_last_name}} {{dateToday.surgeon_first_name}} {{dateToday.surgeon_patronymic}}</span>
                                    </div>
                                </div>
<!--                                <div class="align-self-center">-->
<!--                                    <h5>{{ $dayjs().format('DD.MM.YYYY') }}</h5>-->
<!--                                </div>-->
                            </div>

                            <div class="align-self-center text-center">
                                <h5 class="fw-bold">{{ $dayjs(dateToday.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                                    {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}</h5>
                            </div>

                            <!--time columns-->
                            <!--Cards-->
                            <div class="row justify-content-center">
                                <!--                        <div class="col-sm-6"-->
                                <div class="col-sm-12"
                                     v-for="(room, index) in rooms" :key="index">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title fs-4 fw-bold">{{room.name}}</h5>

                                            <div class="my-1 d-flex">
                                                <div v-if="Object.keys(choice_time).length > 0 && choice_time.day == dateToday.date">
                                                    <button type="button"
                                                            @click="bookingRoom(room.name, room.val[choice_time.start_index], index, choice_time.start_index, room.condition, false, dateToday)"
                                                            class="btn btn-sm btn-danger">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        Сменить статус
                                                    </button>
                                                </div>
                                                <div v-if="auth_user && [roles.admin, roles.cardiologist, roles.surgeon].includes(auth_user.role_name)"
                                                     class="ms-auto">
                                                    <button type="button"
                                                            @click="OffOnHospitalRoom(room.id, room.condition, index)"
                                                            class="btn  btn-sm"
                                                            :class="room.condition == 0 ? 'btn-success' : 'btn-secondary'">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        {{room.condition == 0 ? 'Открыть операционную' : 'Закрыть операционную'}}
                                                    </button>
                                                </div>

                                            </div>

                                            <div class="card-text" :class="room.condition == 0 ? 'disabledcard' : ''">

                                                <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
                                                    <div class="col text-center border-white p-0"
                                                         v-for="(val, i) in roomsVal(room.val, dateToday)" :key="i">

                                                        <div class="square" @dblclick="bookingRoom(room.name, val, index, i, room.condition, true, dateToday)"
                                                             @click="choicesTime(room.name, val, index, i, room.condition, dateToday)"
                                                             :class="colorChoiseTime(index, i, dateToday) ? 'bg-choice' : val.status == 0
                                                             ? 'bg-green-300'
                                                             : val.status == 1 ? 'bg-red-300' : 'bg-yellow-300',
                                                             oldTime(val) ? '' : 'disabledcard',
                                                                canUpdate() && room.condition == 1 && oldTime(val) ? 'cursor' : ''">
                                                            <div>
                                                                <div class="fw-bold fs-5 text-wrap">
                                                                    {{ $dayjs(val.time).format('HH:mm') }}
                                                                    <div class="line_height">-</div>
                                                                    {{ $dayjs(val.time).add(1, 'hour').format('HH:mm') }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-6">

                        <!--Add Orderlies-->

                        <!--Для просмотра и редактирования графика занятости операционных-->
                        <div v-if="canAddOrderly(dateTommorow.date)" class="alert alert-warning fw-bolder" role="alert">
                            Укажите дежурного хирирга и кардиолога на
                            {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                            {{ $dayjs(dateTommorow.date).add(1,'day').set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}.
                            <div class="mt-3">
                                <button type="button" @click="modalOrder(dateTommorow)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                                </button>
                            </div>
                        </div>

                        <!--Orderly FIO-->
                        <!--                        <div v-else>-->
                        <div>
                            <div class="mt-3" v-if="canUpdate(dateTommorow.date)">
                                <button type="button" @click="modalOrder(dateTommorow)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Сменить дежурных
                                </button>
                            </div>
                            <div class="d-flex my-3">
                                <div class="me-auto fs-5">
                                    <div>
                                        <span class="fw-bold">Деж. кардиолог: </span>
                                        <span>{{dateTommorow.cardiologist_last_name}} {{dateTommorow.cardiologist_first_name}} {{dateTommorow.cardiologist_patronymic}}</span>
                                    </div>
                                    <div>
                                        <span class="fw-bold">Деж. хирург: </span>
                                        <span>{{dateTommorow.surgeon_last_name}} {{dateTommorow.surgeon_first_name}} {{dateTommorow.surgeon_patronymic}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="align-self-center text-center">
                                <h5 class="fw-bold">{{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                                    {{ $dayjs(dateTommorow.date).add(1, 'day').set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}</h5>
                            </div>

                            <!--time columns-->
                            <!--Cards-->
                            <div class="row justify-content-center">
                                <!--                        <div class="col-sm-6"-->
                                <div class="col-sm-12"
                                     v-for="(room, index) in rooms" :key="index">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title fs-4 fw-bold">{{room.name}}</h5>

                                            <div class="my-1 d-flex">
                                                <div v-if="Object.keys(choice_time).length > 0 && choice_time.day == dateTommorow.date">
                                                    <button type="button"
                                                            @click="bookingRoom(room.name, room.val[choice_time.start_index], index, choice_time.start_index, room.condition, false, dateTommorow)"
                                                            class="btn btn-sm btn-danger">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        Сменить статус
                                                    </button>
                                                </div>
                                                <div v-if="auth_user && [roles.admin, roles.cardiologist, roles.surgeon].includes(auth_user.role_name)"
                                                     class="ms-auto">
                                                    <button type="button"
                                                            @click="OffOnHospitalRoom(room.id, room.condition, index)"
                                                            class="btn  btn-sm"
                                                            :class="room.condition == 0 ? 'btn-success' : 'btn-secondary'">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        {{room.condition == 0 ? 'Открыть операционную' : 'Закрыть операционную'}}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-text">

                                                <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
                                                    <div class="col text-center border-white p-0"
                                                         v-for="(val, i) in roomsVal(room.val, dateTommorow)" :key="i">

                                                        <div class="square" @dblclick="bookingRoom(room.name, val, index, i, null,true, dateTommorow)"
                                                             @click="choicesTime(room.name, val, index, i, room.condition, dateTommorow)"
                                                             :class="colorChoiseTime(index, i, dateTommorow) ? 'bg-choice' : val.status == 0
                                                             ? 'bg-green-300'
                                                             : val.status == 1 ? 'bg-red-300' : 'bg-yellow-300',
                                                                canUpdate() && oldTime(val) ? 'cursor' : ''">
                                                            <div>
                                                                <div class="fw-bold fs-5 text-wrap">
                                                                    {{ $dayjs(val.time).format('HH:mm') }}
                                                                    <div class="line_height">-</div>
                                                                    {{ $dayjs(val.time).add(1, 'hour').format('HH:mm') }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


                <!-- Modal clocks -->
                <div class="modal fade" id="statusModal"
                     data-bs-keyboard="false" tabindex="-1" aria-labelledby="statusModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div v-if="modal" class="modal-header">
                                <h5 class="modal-title" id="statusModalLabel">
                                    {{modal ? modal.room_name : ''}}
                                </h5>
                                <button type="button" class="btn-close" @click.prevent="closeModal()"></button>
                            </div>
                            <div class="modal-body" v-if="modal">

                                <errors-validation :validationErrors="errs"/>

                                <h5 class="text-center">
                                    <div>
                                        Время:
                                        {{$dayjs(modal.time_start).get('date') == $dayjs(modal.time_end).add(1, 'hour').get('date')
                                        ? $dayjs(modal.time_start).format('HH:mm') : $dayjs(modal.time_start).format('HH:mm DD.MM.YY')}} -
                                        {{$dayjs(modal.time_end).add(1, 'hour').format('HH:mm DD.MM.YY')}}
                                    </div>
                                    Статус: <span class="fw-bold" :class="'bg-'+status.color+'-300'">{{status.label}}</span>
                                </h5>
                                <form  @submit.prevent="" class="row">

                                    <div class="form-group row my-3">
                                        <label class="col-sm-5 col-form-label fw-bold">
                                            Изменить статус:
                                        </label>
                                        <div class="col-sm-7">
                                            <select class="form-select mb-3"
                                                    v-model="newStatus">
                                                <template v-for="el in statuses">
                                                    <option v-if="el.val !== status.val"
                                                            :value="el.val">{{el.label}}</option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

<!--                                    <div v-if="newStatus !== 0 && newStatus !== status.val" >-->
<!--                                        <div class="fs-5">-->
<!--                                            Установить статус-->
<!--                                            <span class="fw-bolder" :class="'bg-'+statuses[newStatus].color+'-300'">-->
<!--                                                {{statuses[newStatus].label}}-->
<!--                                            </span><br>-->
<!--                                            на-->
<!--                                            <div class="form-check form-check-inline" v-for="el in 3">-->
<!--                                                <input class="form-check-input" type="radio"-->
<!--                                                       name="inlineRadioOptions" :id="'inlineRadio4'+el"-->
<!--                                                       :value="el" v-model="clock">-->
<!--                                                <label class="form-check-label" :for="'inlineRadio4'+el">{{el}}</label>-->
<!--                                            </div>-->
<!--                                            часа-->
<!--                                        </div>-->
<!--                                    </div>-->

<!--                                    <div class="form-group row my-3" v-if="newStatus !== 0">-->
<!--                                        <label class="col-sm-5 col-form-label fw-bold">Состояние пациента</label>-->
<!--                                        <div class="col-sm-7">-->
<!--                                            <Multiselect-->
<!--                                                v-model="condition_id"-->
<!--                                                :close-on-select="true"-->
<!--                                                :hide-selected="false"-->
<!--                                                label="name"-->
<!--                                                valueProp="id"-->
<!--                                                :options="conditions"-->
<!--                                            />-->
<!--                                        </div>-->
<!--                                    </div>-->
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click.prevent="closeModal()">Отмена</button>
                                <button type="button" class="btn btn-primary" @click.prevent="saveStatus()">OK</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Orderly -->
                <div class="modal fade" id="modalOrderly"
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
                                <div v-if="date" class="alert alert-primary">
                                    Дежурные на {{$dayjs(date.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm')}}
                                    - {{$dayjs(date.date).add(1, 'day').set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm')}}
                                </div>
                                <form class="row">
                                    <div class="form-group row my-1">
                                        <label class="col-sm-4 col-form-label fw-bold">
                                            Деж. кардиолог
                                        </label>
                                        <div class="col-sm-8">
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
                                        <label class="col-sm-4 col-form-label fw-bold">
                                            Деж. хирург
                                        </label>
                                        <div class="col-sm-8">
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


            </div>
        </div>
    </div>

</template>

<script>
import { Modal } from 'bootstrap'
import useValidate from '@vuelidate/core'
import { required} from '@vuelidate/validators';
import {roles, wait, statuses, server_url} from "../consts";
export default {
    name: "HospitalBooking",
    data() {return {
        v$: useValidate(),
        orderly: [],
        modal: null,
        status: null,
        newStatus: null,
        clock: 2,
        myModal: null,
        myModalOrderly: null,
        success : null,
        cardiologists: [],
        surgeons: [],
        surgeon_id: null,
        cardiologist_id: null,
        processing: false,
        rooms: [],
        disabled_hospital_room:false,

        dateToday: null,
        dateTommorow: null,
        date: null,
        hospital_name: null,
        condition_id: null,
        conditions: [],
        choice_time: {},


        roles,
        wait,
        statuses,
        server_url,
    }},
    validations() {
        return {
            surgeon_id: {required},
            cardiologist_id: {required}
        }
    },
    mounted() {
        // const socket = io(this.server_url);
        // socket.on('todays-update:App\\Events\\TodaysUpdateEvent', (data) => {
        //     this.orderly = data.result;
        // });
        //
        // socket.on('bookings-update:App\\Events\\BookingsUpdateEvent', (data) => {
        //     let arr = data.result;
        //     this.updateRoomStatus(arr);
        // });
        // socket.on('bookings-store:App\\Events\\BookingsStoreEvent', (data) => {
        //     // console.log(data);
        //     let arr = data.result;
        //     this.updateHospitalRoomStatus(arr);
        // });
        this.getData();
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null
        },
        app_url() {
            return import.meta.env.VITE_APP_URL;
        }
    },
    beforeUnmount() {
        if (this.myModal) this.myModal.hide();
        if (this.myModalOrderly) this.myModalOrderly.hide();
    },
    methods: {
        getData() {
            // axios.get(`/api/operators`,{
            //     headers: {Authorization: localStorage.getItem('access_token')},
            //     params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id}
            // }).then(res => {
            //     console.log(res);
            //     let arr = res.data.data;
            //     this.orderly =arr.length > 0 ? arr[arr.length-1] : null;
            //     if (this.orderly && this.orderly.surgeon_id !== '') this.getBooking();
            // }).catch(err => {
            //     this.errorsMessage(err);
            // }).finally(() => this.successPage = true)

            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id},
            }).then(res => {
                console.log(res);
                this.hospital_name = res.data.hospital_name;
                this.dateToday = res.data.dates[0];
                this.dateTommorow = res.data.dates[1];
                res.data.dates.forEach(el => {
                    if (el.surgeon_id == 'default') {
                        this.orderly.push({date: el.date})
                    }
                });
                this.rooms = res.data.rooms;
                // this.bookings = res.data;
                // this.bookings.forEach(el => {
                //     if (el.surgeon_id == 'default') {
                //         this.emptyOrderlies.push({hospital_id: el.hospital_id, hospital_name: el.hospital_name})
                //     }
                // });
                // if (this.emptyOrderlies.length > 0) {
                //     this.warning = `Не указаны дежурные врачи больниц!
                //     Дождитесь пока укажут дежурных врачей, после чего вы получите возможность увидеть
                //      график свободных операционных.`
                // }
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);

        },
        getBooking() {
            this.errs = null;
            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id}
            }).then(res => {
                console.log(res);
                this.rooms = res.data.rooms;
            }).catch(err => {
                this.errorsMessage(err);
            });
        },
        bookingRoom(room_name, val, room_index, val_index, room_condition, col, day) {
            if ((room_condition == 0 && day.date == this.dateToday.date) || this.$dayjs().get('hour') > this.$dayjs(this.choice_time.time[0].time).get('hour')) {
                if (!col && this.choice_time.start_index !== this.choice_time.end_index) {
                    this.choice_time.start_index = this.choice_time.start_index+1;
                } else {
                    return;
                }
            }
            console.log(this.choice_time)
            if ([this.roles.cardiologist, this.roles.surgeon, this.roles.moderator, this.roles.admin].includes(this.auth_user.role_name)) {
                this.errors = null;
                // if (this.conditions.length == 0) {
                //     axios.get(`/api/bookings/create/disease`,{
                //         headers: {Authorization: localStorage.getItem('access_token')},
                //     }).then(res => {
                //         console.log(res);
                //         this.conditions = res.data.conditions;
                //     }).catch(err => {
                //         this.errorsMessage(err);
                //     });
                // }
                this.myModal = new Modal(document.getElementById('statusModal'), {})
                this.modal = {
                    room_name: room_name,
                    time_start: this.rooms[room_index].val[this.choice_time.start_index].time, //val.time,
                    time_end: this.rooms[room_index].val[this.choice_time.end_index].time, //val.time,
                    status: val.status,
                    room_index: room_index,
                    val_index: val_index
                };
                // console.log(this.modal)
                // console.log(this.choice_time)
                this.newStatus = val.status;
                this.clock = 2;
                this.status = this.statuses.find(el => el.val == this.modal.status);
                this.condition_id = null;
                this.myModal.show();
            }
        },
        choicesTime(room_name, val, room_index, val_index, room_condition, day) {
            if ((room_condition == 0 && day.date == this.dateToday.date) || !this.oldTime(val)) return;
            if (Object.keys(this.choice_time).length == 0) {
                this.choice_time.room_index = room_index;
                this.choice_time.day = day.date;
                this.choice_time.start_index = val_index;
                this.choice_time.end_index = val_index;
                this.choice_time.time = [
                    {val: val, val_index: val_index}
                ]
            } else {
                if (this.choice_time.day == day.date && this.choice_time.room_index == room_index) {
                    if (val_index > this.choice_time.end_index) {
                        for (let i=this.choice_time.end_index+1; i <= val_index; i++) {
                            this.choice_time.time.push({val: this.rooms[this.choice_time.room_index].val[i], val_index: i})
                        }
                        this.choice_time.end_index = val_index;
                    } else if (val_index <= this.choice_time.end_index) {
                        if (val_index == this.choice_time.start_index) {
                            this.choice_time = {};
                        } else {
                            if (val_index > this.choice_time.start_index) {
                                let col = this.choice_time.time.filter(el => el.val_index >= val_index).length;
                                let index = this.choice_time.time.findIndex(el => el.val_index == val_index);
                                this.choice_time.time.splice(index, col);
                                this.choice_time.end_index = val_index-1;
                            } else if (val_index < this.choice_time.start_index) {
                                let arr = [];
                                for (let i=val_index; i < this.choice_time.start_index; i++) {
                                    arr.push({val: this.rooms[this.choice_time.room_index].val[i], val_index: i})
                                }
                                this.choice_time.time = arr.concat(this.choice_time.time)
                                this.choice_time.start_index = val_index;
                            }
                        }
                    }
                } else {
                    this.choice_time.room_index = room_index;
                    this.choice_time.day = day.date;
                    this.choice_time.start_index = val_index;
                    this.choice_time.end_index = val_index;
                    this.choice_time.time = [
                        {val: val, val_index: val_index}
                    ]
                }
            }
            console.log(this.choice_time)
        },
        colorChoiseTime(room_index, val_index, day) {
            return this.choice_time.day == day.date && this.choice_time.time &&
            this.choice_time.time.findIndex(el => el.val_index == val_index) > -1 ? true : false;
        },
        oldTime(val) {
            if (this.$dayjs().set('minute', 0).set('second', 0).format('YYYY-MM-DD HH:mm:ss') <= val.time) {
                return true;
            } else return false;
        },
        saveStatus() {
            this.errs = null;
            axios.patch(`/api/bookings`,
                {
                    hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                    room_id: this.rooms[this.modal.room_index].id,
                    status: this.newStatus,
                    date_time: this.rooms[this.modal.room_index].val[this.modal.val_index].time,
                    booking_hours: this.newStatus == 0 ? 1 : this.clock,
                    user_id: this.auth_user.id,
                    condition_id: this.condition_id ? this.condition_id : 1
                },
                {headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                let arr = res.data.data;
                this.updateRoomStatus(arr);

                // if (this.newStatus == 0 || this.clock == 1) {
                //     this.rooms[this.modal.room_index].val[this.modal.val_index].status = this.newStatus
                // } else {
                //     for (let i=this.modal.val_index;i<this.modal.val_index+this.clock; i++) {
                //         console.log(this.rooms[this.modal.room_index].val[i])
                //         if (i >= this.rooms[this.modal.room_index].val.length) {
                //             this.rooms[this.modal.room_index].val[i-this.rooms[this.modal.room_index].val.length].status = this.newStatus
                //         } else {
                //             this.rooms[this.modal.room_index].val[i].status = this.newStatus
                //         }
                //     }
                // }
                this.myModal.hide();
            }).catch(err => {
                this.errorsMessage(err);
            });
        },
        updateRoomStatus(arr) {
            let roomIndex = this.rooms.findIndex(el => el.id == arr[0].room_id);
            if (roomIndex != -1) {
                arr.forEach(el => {
                    let valIndex = this.rooms[roomIndex].val.findIndex(e => e.time == el.date_time);
                    if (valIndex != -1) {
                        this.rooms[roomIndex].val[valIndex].status = el.status
                    }
                })
            }
        },
        modalOrder(orderly) {
            this.errs = null;
            this.date = orderly;
            if (orderly.surgeon_id == 'default') {
                this.surgeon_id = null;
                this.cardiologist_id = null;
            } else {
                this.surgeon_id = orderly.surgeon_id;
                this.cardiologist_id = orderly.cardiologist_id;
            }
            this.myModalOrderly = new Modal(document.getElementById('modalOrderly'), {})
            axios.get(`/api/operators/edit`,{
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id}
            }).then(res => {
                console.log(res);
                this.cardiologists = res.data.cardiologist;
                this.surgeons = res.data.surgeons;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => {
                this.myModalOrderly.show();
            });
        },
        updateOrderly() {
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;

                axios.post(`/api/operators`,
                    {
                        date: this.date.date,
                        hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                        cardiologist_id: this.cardiologist_id,
                        surgeon_id: this.surgeon_id,
                    },
                    {
                        headers: {Authorization: localStorage.getItem('access_token')}
                    }).then(res => {
                    console.log(res);
                    let i = this.orderly.findIndex(el => el.date == res.data.data.date);
                    if (i > -1) {
                        this.orderly.splice(i, 1)
                    };
                    if (this.dateToday.date == res.data.data.date) {
                        this.dateToday.cardiologist_id = res.data.data.cardiologist_id;
                        this.dateToday.cardiologist_first_name = res.data.data.cardiologist_first_name;
                        this.dateToday.cardiologist_last_name = res.data.data.cardiologist_last_name;
                        this.dateToday.cardiologist_patronymic = res.data.data.cardiologist_patronymic;

                        this.dateToday.surgeon_id = res.data.data.surgeon_id;
                        this.dateToday.surgeon_first_name = res.data.data.surgeon_first_name;
                        this.dateToday.surgeon_last_name = res.data.data.surgeon_last_name;
                        this.dateToday.surgeon_patronymic = res.data.data.surgeon_patronymic;
                    } else if (this.dateTommorow.date == res.data.data.date) {
                        this.dateTommorow.cardiologist_id = res.data.data.cardiologist_id;
                        this.dateTommorow.cardiologist_first_name = res.data.data.cardiologist_first_name;
                        this.dateTommorow.cardiologist_last_name = res.data.data.cardiologist_last_name;
                        this.dateTommorow.cardiologist_patronymic = res.data.data.cardiologist_patronymic;

                        this.dateTommorow.surgeon_id = res.data.data.surgeon_id;
                        this.dateTommorow.surgeon_first_name = res.data.data.surgeon_first_name;
                        this.dateTommorow.surgeon_last_name = res.data.data.surgeon_last_name;
                        this.dateTommorow.surgeon_patronymic = res.data.data.surgeon_patronymic;
                    }

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
        canAddOrderly(date) {
            return [this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name)
                && this.orderly.length > 0 && this.orderly.findIndex(el => el.date == date)>-1;

        },
        canUpdate(date) {
            return [this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name)
                && (this.orderly.length == 0 || !this.orderly.find(el => el.date == date));
        },
        closeModal() {
            this.errors = null;
            this.myModal.hide();
        },
        OffOnHospitalRoom(room_id, condition, index) {
            axios.get(`/api/hospital_rooms/disable`,
                {
                    headers: {Authorization: localStorage.getItem('access_token')},
                    params: {
                        room_id,
                        condition: condition == 1 ? 0 : 1
                    }
                }).then(res => {
                console.log(res);
                this.rooms[index].condition = res.data.data.condition
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => {
                if (Object.keys(this.choice_time).length > 0 && this.choice_time.day == this.dateToday.date) {
                    this.choice_time = {}
                }
            });

        },
        roomsVal(val, date) {
            let arr = [];
            if (date == this.dateToday) {
                arr = val.slice(0,24)
            } else {
                arr = val.slice(24,48)
            }
            return arr;
        },
    },
}
</script>

<style scoped>


.line_height {
    line-height: 0.3 !important;
}
.border-white {
    border: 1px solid #fff;
}
.square {
    display: flex;
    justify-content: center;
    align-items: center;
    /*cursor: pointer;*/
    aspect-ratio: 1;
    width: 100%;
    position: relative;
}
.cursor {
    cursor: pointer !important;
}
.striped {
    background: linear-gradient(to right, cyan 50%, palegoldenrod 50%);
}
.room1 {
    position: absolute;
    left: 0;
    top: 0;
    width: 50%;
    height: 100%;
    cursor: pointer;
    /*z-index: 0;*/
    border-right: 1px solid white;
}
.room2 {
    position: absolute;
    right: 0;
    top: 0;
    width: 50%;
    height: 100%;
    cursor: pointer;
    /*z-index: 0;*/
    border-left: 1px solid white;
}

</style>

