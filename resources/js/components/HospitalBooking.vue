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
                        <div v-if="canAddOrderly(dateToday.date)" class="alert alert-warning fw-bolder">
                            Укажите дежурного хирирга и кардиолога на
                            {{ $dayjs(dateToday.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                            {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}.
                            <div class="mt-3">
                                <button type="button" @click="modalOrder(dateToday)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                                </button>
                            </div>
                        </div>


                        <div>
                            <!--Orderly FIO-->
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
                            </div>
                            <div class="align-self-center text-center">
                                <h5 class="fw-bold">{{ $dayjs(dateToday.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                                    {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}</h5>
                            </div>

                            <!--time columns--> <!--Cards-->
                            <div class="row justify-content-center">
                                <!--<div class="col-sm-6"-->
                                <div class="col-sm-12" v-for="(room, index) in rooms" :key="index">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title fs-4 fw-bold">{{room.name}}</h5>
                                            <h5 class="card-title">
                                                Время работы {{room.start ? room.start : '08:00'}} -
                                                {{room.end ? room.end : '08:00'}}
                                            </h5>

                                            <div class="my-1 d-flex" v-if="canUpdateClock()">
                                                <div v-if="btnChangeStatus(dateToday, index)">
                                                    <button type="button"
                                                            @click="bookingRoom(room,room.val[choice_time.day == dateToday.date ? choice_time.start_index : choice_time.start_index+24],index,choice_time.start_index,room.condition,false,dateToday)"
                                                            class="btn btn-sm btn-danger">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        Сменить статус
                                                    </button>
                                                </div>
                                                <div v-if="allowOnOffRoom()" class="ms-auto">
                                                    <button type="button"
                                                            @click="OffOnHospitalRoom(room.id, room.condition, index)"
                                                            class="btn  btn-sm"
                                                            :class="room.condition == 0 ? 'btn-success' : 'btn-secondary'">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        {{room.condition == 0 ? 'Открыть' : 'Закрыть'}} операционную
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-text"
                                                 :class="room.condition == 0 ? 'disabledcard' : ''">
                                                <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
                                                    <div class="col text-center border-white p-0"
                                                         v-for="(val, i) in roomsVal(room.val, dateToday)" :key="i">

                                                        <div class="square"
                                                             @click="onClick(room, val, index, i, room.condition, true, dateToday)"
                                                             :class="addCssClass(room, index, val, i, dateToday)">
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

                    <div class="col-sm-6">

                        <!--Add Orderlies-->
                        <div v-if="canAddOrderly(dateTommorow.date)" class="alert alert-warning fw-bolder">
                            Укажите дежурного хирирга и кардиолога на
                            {{ $dayjs(dateTommorow.date).set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }} -
                            {{ $dayjs(dateTommorow.date).add(1,'day').set('hour', 8).set('minute', 0).format('DD.MM.YYYY HH:mm') }}.
                            <div class="mt-3">
                                <button type="button" @click="modalOrder(dateTommorow)" class="btn btn-primary">
                                    <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                                </button>
                            </div>
                        </div>


                        <div>
                            <!--Orderly FIO-->
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

                            <!--time columns--><!--Cards-->
                            <div class="row justify-content-center">
                                <!-- <div class="col-sm-6"-->
                                <div class="col-sm-12"
                                     v-for="(room, index) in rooms" :key="index">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title fs-4 fw-bold">{{room.name}}</h5>
                                            <h5 class="card-title">
                                                Время работы {{room.star ? room.start : '08:00'}} -
                                                {{room.end ? room.end : '08:00'}}
                                            </h5>

                                            <div class="my-1 d-flex" v-if="canUpdateClock()">
                                                <div v-if="btnChangeStatus(dateTommorow, index)">
                                                    <button type="button"
                                                            @click="bookingRoom(room, room.val[choice_time.day == dateToday.date ? choice_time.start_index : choice_time.start_index+24], index, choice_time.start_index, room.condition, false, dateTommorow)"
                                                            class="btn btn-sm btn-danger">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        Сменить статус
                                                    </button>
                                                </div>
                                                <div v-if="allowOnOffRoom()" class="ms-auto">
                                                    <button type="button"
                                                            @click="OffOnHospitalRoom(room.id, room.condition, index)"
                                                            class="btn  btn-sm"
                                                            :class="room.condition == 0 ? 'btn-success' : 'btn-secondary'">
                                                        <font-awesome-icon icon="fa-solid fa-pencil" />
                                                        {{room.condition == 0 ? 'Открыть' : 'Закрыть'}} операционную
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="card-text" :class="room.condition == 0 ? 'disabledcard' : ''">

                                                <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
<!--                                                    v-for="(val, i) in roomsVal(room.val, dateTommorow)" :key="i"-->
                                                    <div class="col text-center border-white p-0"
                                                         v-for="(val, i) in roomsVal(room.val, dateTommorow)" :key="i">

                                                        <div class="square"
                                                             @click="onClick(room, val, index, i, room.condition, true, dateTommorow)"
                                                             :class="addCssClass(room, index, val, i, dateTommorow)">
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

                                <div v-if="message_free_status" class="alert alert-danger d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    <div>
                                        Статус
                                        <span class="badge" :class="'bg-'+statuses[1].color+'-300'" style="color: black">
                                            {{statuses[1].label}}
                                        </span>
                                        можно поставить только если предыдущее время не стоит в статусе
                                        <span class="badge" :class="'bg-'+statuses[0].color+'-300'" style="color: black">
                                            {{statuses[0].label}}
                                        </span>
                                        <p>
                                            Если вам нужно занять этот период времеи выбрите статус
                                            <span class="badge" :class="'bg-'+statuses[2].color+'-300'" style="color: black">
                                                {{statuses[2].label}}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <h5 class="text-center">
                                    <div>
                                        {{ChangeTime()}}
                                    </div>
                                    Статус: <span class="fw-bold" :class="'bg-'+status.color+'-300'">
                                    {{status.label}}</span>
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
                                                    <option v-if="el.val !== status.val && el.val != 10"
                                                            :value="el.val">{{el.label}}</option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click.prevent="closeModal()">Отмена</button>
                                <button type="button" class="btn btn-primary" @click.prevent="saveStatus()" :disabled="processing">
                                    {{ processing ? wait : "OK" }}
                                </button>
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
        hospital_id: null,
        condition_id: null,
        conditions: [],
        choice_time: {},
        clicks: 0,
        timer: null,
        message_free_status: false,

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
    watch: {
        newStatus (newVal, oldVal) {
            if (newVal !== oldVal) {
                this.message_free_status = false;
            }
        }
    },
    mounted() {
        // const socket = io(this.server_url);
        // socket.on('operators-update:App\\Events\\OperatorsUpdateEvent', (data) => {
        //     let doctor = data.result;
        //     this.getOrderly(doctor);
        // });
        //
        // socket.on('bookings-update:App\\Events\\BookingsUpdateEvent', (data) => {
        //     let arr = data.result;
        //     this.updateRoomStatus(arr);
        // });
        // socket.on('bookings-store:App\\Events\\BookingsStoreEvent', (data) => {
        //     // console.log(data);
        //     let arr = data.result;
        //     this.updateRoomStatusDispet(arr.bookings);
        // });
        //
        // socket.on('bookings-all-time-index:App\\Events\\BookingsAllTimeIndexEvent', (data) => {
        //     let result = data.result;
        //     let hospital_id = this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id;
        //     let index = result.findIndex(el => el.hospital_id == hospital_id)
        //     if (index > -1) {
        //         this.hospitalBookings(result[index]);
        //     }
        // });
        this.getData();
    },

    methods: {
        getData() {
            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id},
            }).then(res => {
                console.log(res);
                this.hospitalBookings(res.data)
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);

        },
        hospitalBookings(data) {
            this.hospital_name = data.hospital_name;
            this.hospital_id = data.hospital_id;
            this.dateToday = data.dates[0];
            this.dateTommorow = data.dates[1];
            data.dates.forEach(el => {
                if (el.surgeon_id == 'default') {
                    this.orderly.push({date: el.date})
                }
            });
            this.rooms = data.rooms;
        },
        bookingRoom(room, val, room_index, val_index, room_condition, col, day) {
            this.v$.$reset();
            if (this.$dayjs().get('date') == this.$dayjs(val.time).get('date')
                && this.$dayjs().get('hour') > this.$dayjs(val.time).get('hour')) {
                return;
            }

            if (Object.keys(this.choice_time).length == 0 || this.choice_time.day != day.date
                || this.choice_time.room_index != room_index
                || this.choice_time.time.findIndex(el => el.val_index == val_index) == -1) {
                this.choicesTime(room, val, room_index, val_index, room_condition, day)
            }

            if (room_condition == 0
                || (this.$dayjs().get('date') == this.$dayjs(this.choice_time.time[0].val.time).get('date')
                    && this.$dayjs().get('hour') > this.$dayjs(this.choice_time.time[0].val.time).get('hour'))
            ) {
                if (!col && this.choice_time.start_index != this.choice_time.end_index) {
                    this.choice_time.start_index = this.choice_time.start_index+1;
                } else return;
            }

            if ([this.roles.cardiologist, this.roles.surgeon, this.roles.moderator, this.roles.admin].includes(this.auth_user.role_name)) {
                this.errors = null;
                this.myModal = new Modal(document.getElementById('statusModal'), {})
                this.modal = {
                    room_name: room.name,
                    time_start: this.rooms[room_index].val[this.choice_time.start_index].time, //val.time,
                    time_end: this.rooms[room_index].val[this.choice_time.end_index].time, //val.time,
                    status: this.choice_time.time[0].val.status,
                    room_index: room_index,
                    val_index: val_index,
                    day: this.choice_time.day
                };
                this.newStatus = val.status;
                this.clock = 2;
                this.status = this.statuses.find(el => el.val == this.modal.status);
                this.condition_id = null;
                this.myModal.show();
            }
        },
        onClick(room, val, room_index, val_index, room_condition, col, day)  {
            if (this.canUpdateClock()) {
                this.clicks++;
                if (this.clicks === 1) {
                    this.timer = setTimeout( () => {
                        this.choicesTime(room, val, room_index, val_index, room_condition, day);
                        this.clicks = 0
                    }, 190);
                } else {
                    clearTimeout(this.timer);
                    this.bookingRoom(room, val, room_index, val_index, room_condition, col, day)
                    this.clicks = 0;
                }
            }
        },
        choicesTime(room, val, room_index, val_index, room_condition, day) {
            if ((room_condition == 0) || !this.oldTime(val) || this.colorRoomOffTime(room,room_index,val_index,val )) return;
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
                            this.choice_time.time.push({
                                val: this.rooms[this.choice_time.room_index].val[day.date == this.dateToday.date ? i : i+24],
                                val_index: i})
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
                                    arr.push({
                                        val: this.rooms[this.choice_time.room_index].val[day.date == this.dateToday.date ? i : i+24],
                                        val_index: i})
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
            // console.log(this.choice_time)
        },
        colorChoiseTime(room_index, val_index, day) {
            return this.choice_time.day == day.date && this.choice_time.time
            && this.choice_time.room_index == room_index
            && this.choice_time.time.findIndex(el => el.val_index == val_index) > -1 ? true : false;
        },
        oldTime(val) {
            if (this.$dayjs().set('minute', 0).set('second', 0).format('YYYY-MM-DD HH:mm:ss') <= val.time) {
                return true;
            } else return false;
        },
        colorRoomOffTime(room, index, i, val ) {
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
        btnChangeStatus(day, room_index) {
            return Object.keys(this.choice_time).length > 0
            && this.choice_time.day == day.date
            && this.choice_time.room_index == room_index ? true :false;
        },
        saveStatus() {
            this.errs = null;
            this.message_free_status = false

            if (this.newStatus == 1) { // Статус = занят
                if ((this.choice_time.start_index - 1 >= 0 && this.choice_time.day == this.dateToday.date)
                    || this.choice_time.day == this.dateTommorow.date) {

                    let val_index = this.choice_time.start_index - 1;
                    if (this.choice_time.day == this.dateTommorow.date) {
                        val_index = val_index + 24;
                    }

                    if (this.rooms[this.choice_time.room_index].val[val_index].status == 0) {
                        if (this.rooms[this.choice_time.room_index].start == this.rooms[this.choice_time.room_index].end) {
                            if (this.$dayjs().isBefore(this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).add(1, 'hour'))) {
                                if (this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).add(1, 'hour').format('HH:mm') !== '08:00') {
                                    console.log('message error start == end');
                                    this.message_free_status = true;
                                    return;
                                }
                            }

                        } else if (Number(this.rooms[this.choice_time.room_index].start.substr(0, 2)) < Number(this.rooms[this.choice_time.room_index].end.substr(0, 2))) {
                            if (Number(this.rooms[this.choice_time.room_index].start.substr(0, 2)) <= this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour')
                                && this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour') <= Number(this.rooms[this.choice_time.room_index].end.substr(0, 2))-1
                                && this.$dayjs().isBefore(this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).add(1, 'hour'))
                            )
                            {
                                console.log('message error start < end');
                                this.message_free_status = true;
                                return
                            }
                        } else {
                            if (
                                this.$dayjs().isBefore(this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).add(1, 'hour'))
                                    &&
                                (
                                    (Number(this.rooms[this.choice_time.room_index].start.substr(0, 2)) <= this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour')
                                    && this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour') <= 23)
                                    || (0 <= this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour')
                                    && this.$dayjs(this.rooms[this.choice_time.room_index].val[val_index].time).get('hour') <= Number(this.rooms[this.choice_time.room_index].end.substr(0, 2))-1)
                                )
                            )
                            {
                                this.message_free_status = true;
                                console.log('start > end')
                                return
                            }
                        }

                    }

                }
            }

            let date_times = this.choice_time.time.map(a => a.val.time);
            this.processing = true;
            axios.patch(`/api/bookings`,
                {
                    hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                    room_id: this.rooms[this.modal.room_index].id,
                    status: this.newStatus,
                    date_times: date_times,

                },
                {headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.choice_time = {};
                let arr = res.data.data;
                this.updateRoomStatus(arr);
                this.myModal.hide();
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => {
                this.processing = false;
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
        updateRoomStatusDispet(arr) {
            if (this.hospital_id && this.hospital_id == arr[0].hospital_id)  {
                let roomIndex = this.rooms.findIndex(el => el.id == arr[0].room_id);
                if (roomIndex != -1) {
                    arr.forEach(el => {
                        let valIndex = this.rooms[roomIndex].val.findIndex(e => e.time == el.date_time);
                        if (valIndex != -1) {
                            this.rooms[roomIndex].val[valIndex].status = el.status
                        }
                    })
                }
            }
        },
        modalOrder(orderly) {
            this.v$.$reset();
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
                let data = {
                    date: this.date.date,
                    hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                    cardiologist_id: this.cardiologist_id,
                    surgeon_id: this.surgeon_id,
                };
                axios.post(`/api/operators`, data, {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    let doctor = res.data.data;
                    this.getOrderly(doctor);
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
        getOrderly(doctor) {
            let i = this.orderly.findIndex(el => el.date == doctor.date);
            if (i > -1) {
                this.orderly.splice(i, 1)
            };
            if (this.dateToday.date == doctor.date) {
                this.dateToday.cardiologist_id = doctor.cardiologist_id;
                this.dateToday.cardiologist_first_name = doctor.cardiologist_first_name;
                this.dateToday.cardiologist_last_name = doctor.cardiologist_last_name;
                this.dateToday.cardiologist_patronymic = doctor.cardiologist_patronymic;

                this.dateToday.surgeon_id = doctor.surgeon_id;
                this.dateToday.surgeon_first_name = doctor.surgeon_first_name;
                this.dateToday.surgeon_last_name = doctor.surgeon_last_name;
                this.dateToday.surgeon_patronymic = doctor.surgeon_patronymic;
            } else if (this.dateTommorow.date == doctor.date) {
                this.dateTommorow.cardiologist_id = doctor.cardiologist_id;
                this.dateTommorow.cardiologist_first_name = doctor.cardiologist_first_name;
                this.dateTommorow.cardiologist_last_name = doctor.cardiologist_last_name;
                this.dateTommorow.cardiologist_patronymic = doctor.cardiologist_patronymic;

                this.dateTommorow.surgeon_id = doctor.surgeon_id;
                this.dateTommorow.surgeon_first_name = doctor.surgeon_first_name;
                this.dateTommorow.surgeon_last_name = doctor.surgeon_last_name;
                this.dateTommorow.surgeon_patronymic = doctor.surgeon_patronymic;
            }

        },
        canAddOrderly(date) {
            if (this.orderly.length > 0 && this.orderly.findIndex(el => el.date == date)>-1) {
                if ([this.roles.surgeon, this.roles.cardiologist,   this.roles.admin].includes(this.auth_user.role_name)) {
                    return true
                }
                if (this.roles.moderator == this.auth_user.role_name && this.hospital_id == this.auth_user.hospital_id) {
                    return true
                }
            }
            return false
            // return [this.roles.surgeon, this.roles.cardiologist, this.roles.moderator,  this.roles.admin].includes(this.auth_user.role_name)
            //     && this.orderly.length > 0 && this.orderly.findIndex(el => el.date == date)>-1;

        },
        canUpdate(date) {
            if (this.orderly.length == 0 || !this.orderly.find(el => el.date == date)) {
                if ([this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name)) {
                    return true;
                }
                if (this.roles.moderator== this.auth_user.role_name && this.hospital_id == this.auth_user.hospital_id) {
                    return true;
                }
            }
            return false;
            // return [this.roles.surgeon, this.roles.cardiologist, this.roles.moderator, this.roles.admin].includes(this.auth_user.role_name)
            //     && (this.orderly.length == 0 || !this.orderly.find(el => el.date == date));
        },
        canUpdateClock() {
                if ([this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name)) {
                    return true
                }
                if (this.roles.moderator == this.auth_user.role_name && this.hospital_id == this.auth_user.hospital_id) {
                    return true
                }
                return false
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
        allowOnOffRoom() {
            return this.auth_user &&
            [this.roles.admin, this.roles.cardiologist, this.roles.surgeon, this.roles.moderator].includes(this.auth_user.role_name)
            ? true : false;
        },
        addCssClass(room, room_index, val, val_index, day) {
            let columnColor = this.colorChoiseTime(room_index, val_index, day)
                ? 'bg-choice' : this.colorRoomOffTime(room,room_index,val_index,val)
                    ? 'bg-gray-300' : val.status == 0
                        ? 'bg-green-300' : val.status == 1
                            ? 'bg-red-300' : 'bg-yellow-300';

            let disabledCard = this.oldTime(val) ? '' : 'disabledcard';

            let cursor = this.canUpdate() && room.condition == 1 && this.oldTime(val)
            && !this.colorRoomOffTime(room,room_index,val_index,val) ? 'cursor' : '';

            let str = `${columnColor} ${disabledCard} ${cursor}`

            return str
        },
        ChangeTime() {
            let start = this.$dayjs(this.modal.time_start).get('date')
                == this.$dayjs(this.modal.time_end).add(1, 'hour').get('date')
                ? this.$dayjs(this.modal.time_start).format('HH:mm')
                : this.modal.day == this.dateToday.date
                    ? this.$dayjs(this.modal.time_start).format('HH:mm DD.MM.YY')
                    : this.$dayjs(this.modal.time_start).add(1, 'day').format('HH:mm DD.MM.YY');
            let end = this.modal.day == this.dateToday.date
                ? this.$dayjs(this.modal.time_end).add(1, 'hour').format('HH:mm DD.MM.YY')
                : this.$dayjs(this.modal.time_end).add(1, 'hour').add(1, 'day').format('HH:mm DD.MM.YY')
            return `Время: ${start} - ${end}`
        }


    },
}
</script>

<style scoped>

</style>

