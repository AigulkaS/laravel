<template>
    <div class="container">
        <h4>Больница № 5</h4>
        <div class="d-flex my-3">
            <div class="me-auto ">
                <div>
                    <span class="fw-bold">Деж. хирург: </span>
                    <span>Иванов Иван Иванович</span>
                </div>
                <div>
                    <span class="fw-bold">Деж. кардиолог: </span>
                    <span>Иванов Иван Иванович</span>
                </div>
            </div>
            <div class="align-self-center">
                <h5>28.12.2022 (СР)</h5>
            </div>
        </div>
        <div>
            <h3 v-for="status in statuses" class="d-inline-flex me-1">
                <span class="badge" :class="'bg-'+status.color+'-300'" style="color: black">{{status.label}}</span>
            </h3>
        </div>

        <!--time columns-->
        <div class="row row-cols-3 row-cols-sm-6 p-0 mx-1">
            <div class="col text-center border-white p-0" v-for="(clock, i) in clocks" :key="i">
                <template v-if="clock.rooms.length > 1">
                    <div class="square"
                         :style="gradientColor(clock.rooms[0].status, clock.rooms[1].status)"
                    >
                        <div>
                            <div><h4 class="fw-bold">{{clock.time }}</h4></div>
                            <div class="row">
                                <div class="col">
                                    <div class="fw-bolder">{{clock.rooms[0].name}}</div>
                                    <div class="room1" @click="bookingRoom(clock.time, clock.rooms[0])"
                                         data-bs-toggle="modal"
                                         data-bs-target="#statusModal"></div>
                                </div>
                                <div class="col">
                                    <div class="fw-bolder">{{clock.rooms[1].name}}</div>
                                    <div class="room2" @click="bookingRoom(clock.time, clock.rooms[1])"
                                         data-bs-toggle="modal"
                                         data-bs-target="#statusModal"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
                <template v-else>
                    <div class="square" @click="bookingRoom(clock.time, clock.rooms[0])"
                         data-bs-toggle="modal"
                         data-bs-target="#statusModal"
                         :class="clock.rooms[0].status == 0 ? 'bg-green-300'
                             : clock.rooms[0].status == 1 ? 'bg-red-300' : 'bg-yellow-300'">
                        <div>
                            <div><h4 class="fw-bold">{{clock.time }}</h4></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="statusModal"
             data-bs-keyboard="false" tabindex="-1" aria-labelledby="statusModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div v-if="modal" class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel">
                            Операционная {{modal ? modal.room.name : ''}}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" v-if="modal">
                        <h5 class="text-center">
                            Время: <span class="fw-bold">{{modal.time}}</span> Статус: <span class="fw-bold" :class="'bg-'+status.color+'-300'">{{status.label}}</span>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" @click.prevent="saveStatus()">OK</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import { Modal } from 'bootstrap'
export default {
    name: "HospitalBooking",
    data() {return {
        hospital: null,
        clocks: [],
        modal: null,
        statuses:[
            {val: 0, label: 'Свободна', color: 'green'},
            {val: 1, label: 'Занята', color: 'red'},
            {val: 2, label: 'Условно занята', color: 'yellow'}
        ],
        status: null,
        newStatus: null,
        clock: 2,
        myModal: null
    }},
    mounted() {
        this.myModal = new Modal(document.getElementById('statusModal'), {})
        this.getData()
    },
    // beforeRouteLeave() {
    //     this.myModal.hide();
    // },
    beforeUnmount() {
        this.myModal.hide();
    },
    methods: {
        getData() {
            this.hospital = {
                id: 1,
                hospital_name: '21 Больница',
                surgeon_id: 2, //hirurg
                surgeon_last_name: '',
                surgeon_first_name: '',
                surgeon_patronymic: '',
                cardiologist_id: 3,
                cardiologist_last_name: '',
                cardiologist_first_name: '',
                cardiologist_patronymic: '',
                rooms: [
                    {
                        name: '101',
                        val: [
                            { time: '00:00', status: 1},
                            { time: '08:00', status: 0},
                            { time: '15:00', status: 2},
                        ]
                    },
                    {
                        name: '102',
                        val: [
                            { time: '00:00', status: 2},
                            { time: '07:00', status: 0},
                            { time: '05:00', status: 1},
                            { time: '10:00', status: 2},
                            { time: '12:00', status: 0},
                            { time: '15:00', status: 2},
                            { time: '20:00', status: 1},
                        ]
                    }
                ]
            };
            let rooms=this.hospital.rooms;

            this.clocks = [
                {time: '00:00'},
                {time: '01:00'},
                {time: '02:00'},
                {time: '03:00'},
                {time: '04:00'},
                {time: '05:00'},
                {time: '06:00'},
                {time: '07:00'},
                {time: '08:00'},
                {time: '09:00'},
                {time: '10:00'},
                {time: '11:00'},
                {time: '12:00'},
                {time: '13:00'},
                {time: '14:00'},
                {time: '15:00'},
                {time: '16:00'},
                {time: '17:00'},
                {time: '18:00'},
                {time: '19:00'},
                {time: '20:00'},
                {time: '21:00'},
                {time: '22:00'},
                {time: '23:00'},
            ];

            this.clocks.forEach((el, index) => {
                el['rooms'] = [];
                rooms.forEach(room => {
                    let time = room.val.find(element => el.time == element.time)
                    el.rooms.push({name: room.name, status: time != undefined ? time.status : 0});
                })
            });

            console.log(this.clocks)


        },
        gradientColor(statusRoom1, statusRoom2) {
            return `background: linear-gradient(to right, ${statusRoom1 == 0 ? '#75b798'
                : statusRoom1 == 1 ? '#ea868f' : '#ffda6a'} 50%,  ${statusRoom2 == 0 ? '#75b798'
                : statusRoom2 == 1 ? '#ea868f' : '#ffda6a'} 50%)`;
        },
        toggleModal() {
            console.log(555);
        },
        booking() {
            console.log('booking');
        },
        bookingRoom(clock, room) {
            this.modal = {time: clock, room: room};
            this.newStatus = room.status;
            this.clock = 2;
            this.status = this.statuses.find(el => el.val == this.modal.room.status);
            console.log('bookingRoom12');
        },
        // bookingRoom2(clock, room) {
        //     this.modal = {time: clock, room: room};
        //     this.status = this.statuses.find(el => el.val == this.modal.room.status);
        //     console.log('bookingRoom2');
        // },
        saveStatus() {
            if (this.newStatus == 0 || this.clock == 1) {
                this.modal.room.status = this.newStatus
            } else {
                let roomIndex = 0;
                let clockIndex = this.clocks.findIndex(el => {
                    if (el.time == this.modal.time && el.rooms.length > 1) {
                        roomIndex = el.rooms.findIndex(e => e.name == this.modal.room.name)
                    }
                    return el.time == this.modal.time;
                });
                for (let i=clockIndex;i<clockIndex+this.clock; i++) {
                    console.log(this.clocks[i])
                    if (i >= this.clocks.length) {
                        this.clocks[i-this.clocks.length].rooms[roomIndex].status = this.newStatus
                    } else {
                        this.clocks[i].rooms[roomIndex].status = this.newStatus
                    }
                }
            }
            this.myModal.hide();

        }
    },
}
</script>

<style>
</style>
<style scoped>
.border-white {
    border: 3px solid #fff;
}
.square {
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    aspect-ratio: 1;
    width: 100%;
    position: relative;
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
