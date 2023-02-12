<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div class="container" v-if="orderly">
                <h4 v-if="orderly">{{orderly.hospital_name}}</h4>

                <!--Errors-->
                <errors-validation :validationErrors="errs"/>

                <!--Add Orderlies-->
                <div v-if="canAddOrderly()" class="alert alert-warning fw-bolder" role="alert">
                    Для просмотра и редактирования графика занятости операционных укажите
                    дежурного хирирга и кардиолога на {{ $dayjs().format('DD.MM.YYYY') }}.
                    <div class="mt-3">
                        <button type="button" @click="modalOrder()" class="btn btn-primary">
                            <font-awesome-icon icon="fa-solid fa-pencil" /> Добавить дежурных
                        </button>
                    </div>
                </div>

                <!--Orderly FIO-->
                <div v-else>
                    <div class="mt-3" v-if="canUpdate()">
                        <button type="button" @click="modalOrder()" class="btn btn-primary">
                            <font-awesome-icon icon="fa-solid fa-pencil" /> Сменить дежурных
                        </button>
                    </div>
                    <div class="d-flex my-3">
                        <div class="me-auto fs-5">
                            <div>
                                <span class="fw-bold">Деж. кардиолог: </span>
                                <span>{{orderly.cardiologist_last_name}} {{orderly.cardiologist_first_name}} {{orderly.cardiologist_patronymic}}</span>
                            </div>
                            <div>
                                <span class="fw-bold">Деж. хирург: </span>
                                <span>{{orderly.surgeon_last_name}} {{orderly.surgeon_first_name}} {{orderly.surgeon_patronymic}}</span>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <h5>{{ $dayjs().format('DD.MM.YYYY') }}</h5>
                        </div>
                    </div>

                    <!--Badge Statuses-->
                    <div>
                        <h3 v-for="status in statuses" class="d-inline-flex me-1">
                            <span class="badge" :class="'bg-'+status.color+'-300'" style="color: black">{{status.label}}</span>
                        </h3>
                    </div>

                    <!--time columns-->
                    <!--Cards-->
                    <div class="row justify-content-center">
                        <div class="col-sm-6"
                             v-for="(room, index) in rooms" :key="index">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fs-4 fw-bold">Операционня {{room.name}}</h5>
                                    <div class="card-text">

                                        <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
                                            <div class="col text-center border-white p-0"
                                                 v-for="(val, i) in room.val" :key="i">

                                                <div class="square" @click="bookingRoom(room.name, val, index, i)"
                                                     :class="val.status == 0
                                             ? 'bg-green-300'
                                             : val.status == 1 ? 'bg-red-300' : 'bg-yellow-300',
                                              canUpdate() ? 'cursor' : ''">
                                                    <div>
                                                        <div><div class="fw-bold fs-5 text-wrap">{{ $dayjs(val.time).format('HH:mm') }}</div></div>
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
                                    Операционная {{modal ? modal.room_name : ''}}
                                </h5>
                                <button type="button" class="btn-close" @click.prevent="closeModal()"></button>
                            </div>
                            <div class="modal-body" v-if="modal">

                                <errors-validation :validationErrors="errs"/>

                                <h5 class="text-center">
                                    Время: <span class="fw-bold">{{$dayjs(modal.time).format('HH:mm')}}</span>
                                    Статус: <span class="fw-bold" :class="'bg-'+status.color+'-300'">{{status.label}}</span>
                                </h5>
                                <form  @submit.prevent="" class="row">
                                    <div class="form-group row my-1">
                                        <label class="col-sm-4 col-form-label fw-bold">
                                            Изменить статус:
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-select mb-3"
                                                    v-model="newStatus">
                                                <template v-for="el in statuses">
                                                    <option v-if="el.val !== status.val"
                                                            :value="el.val">{{el.label}}</option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                    <div v-if="newStatus !== 0 && newStatus !== status.val" >
                                        <div class="fs-4">
                                            Установить статус
                                            <span class="fw-bolder" :class="'bg-'+statuses[newStatus].color+'-300'">
                                        {{statuses[newStatus].label}}
                                    </span><br>
                                            на <div class="form-check form-check-inline" v-for="el in 3">
                                            <input class="form-check-input" type="radio"
                                                   name="inlineRadioOptions" :id="'inlineRadio4'+el"
                                                   :value="el" v-model="clock">
                                            <label class="form-check-label" :for="'inlineRadio4'+el">{{el}}</label>
                                        </div>
                                            часа
                                        </div>
                                    </div>
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
        orderly: null,
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
            axios.get(`/api/todays/`,{
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id}
            }).then(res => {
                console.log(res);
                let arr = res.data.data;
                this.orderly =arr.length > 0 ? arr[arr.length-1] : null;
                if (this.orderly && this.orderly.surgeon_id !== '') this.getBooking();
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
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
        bookingRoom(room_name, val, room_index, val_index) {
            if ([this.roles.cardiologist, this.roles.surgeon, this.roles.admin].includes(this.auth_user.role_name)) {
                this.errors = null;
                this.myModal = new Modal(document.getElementById('statusModal'), {})
                this.modal = {
                    room_name: room_name,
                    time: val.time,
                    status: val.status,
                    room_index: room_index,
                    val_index: val_index
                };
                this.newStatus = val.status;
                this.clock = 2;
                this.status = this.statuses.find(el => el.val == this.modal.status);
                this.myModal.show();
            }
        },
        saveStatus() {
            this.errs = null;
            axios.patch(`/api/bookings`,
                {
                    hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                    room_id: this.rooms[this.modal.room_index].id,
                    status: this.newStatus,
                    date_time: this.rooms[this.modal.room_index].val[this.modal.val_index].time,
                    booking_hours: this.newStatus == 0 ? 1 : this.clock
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
        modalOrder() {
            this.errs = null;
            this.myModalOrderly = new Modal(document.getElementById('modalOrderly'), {})
            axios.get(`/api/todays/edit`,{
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
                axios.patch(`/api/todays`,
                    {
                        hospital_id: this.$route.params.id ? this.$route.params.id : this.auth_user.hospital_id,
                        cardiologist_id: this.cardiologist_id,
                        surgeon_id: this.surgeon_id,
                    },
                    {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.orderly = res.data.data;
                    if (this.rooms.length == 0) this.getBooking();
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
        canAddOrderly() {
            return
            [this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name)
            && (!this.orderly || this.orderly.surgeon_id == '');
        },
        canUpdate() {
            return [this.roles.surgeon, this.roles.cardiologist, this.roles.admin].includes(this.auth_user.role_name);
        },
        closeModal() {
            this.errors = null;
            this.myModal.hide();
        }
    },
}
</script>

<style scoped>
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

