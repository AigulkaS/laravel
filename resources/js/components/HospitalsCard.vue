<template>
    <div>
        <div id="today" class="text-center fw-bold">
            <h3 class="mb-6">Сегодня - {{ $dayjs().format('DD.MM.YYYY') }}</h3>
        </div>

        <div v-if="success" class="alert alert-success" role="alert">
            {{success}}
        </div>

        <div v-if="warning" class="alert alert-warning" role="alert">
            {{warning}}
        </div>
        <div v-else-if="bookings.length > 0">
            <div>
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
                                <div class="p-1 bd-highlight align-self-center">
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
<!--                                        <div class="col border bg-green-300 p-3 rounded text-center">-->
<!--                                            <div><h4>13:00</h4></div>-->
<!--                                            <div class="text-white fw-bolder">Свободна</div>-->
<!--                                        </div>-->
<!--                                        <div class="col d-none d-sm-block border bg-yellow-300 p-3 rounded text-center">-->
<!--                                            <div><h4>14:00</h4></div>-->
<!--                                            <div class="text-white fw-bolder">Бронь</div>-->
<!--                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <router-link :to="{name: 'hospital_booking', params: {id: booking.hospital_id}}"
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="address_sick" class="col-form-label">Введите адрес пациента</label>
                                <div class="autocomplete">
                                    <input type="text" :value="query"
                                       @input="lazyCaller($event.target.value)"
                                       class="form-control" id="address_sick"
                                       :class="v$.suggestion.$error ? 'border-danger' : ''"
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
                                    <span v-if="v$.suggestion.$error" :class="v$.suggestion.$error ? 'text-danger' : ''">
                                      Поле адрес пациента обязательное поле для заполнения
                                    </span>
                                </div>
<!--                                <label>{{query}}</label>-->
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Укажите диагноз</label>
                                <Multiselect
                                    v-model="v$.disease.$model"
                                    :class="v$.disease.$error ? 'border-danger' : ''"
                                    :close-on-select="true"
                                    :hide-selected="false"
                                    label="name"
                                    valueProp="id"
                                    :options="[
                                        {
                                            id: 1,
                                            name: 'Инсульт1'
                                        },
                                        {
                                            id: 2,
                                            name: 'Инсульт2'
                                        }
                                    ]"
                                />
                                <span v-if="v$.disease.$error" :class="v$.disease.$error ? 'text-danger' : ''">
                                      Поле диагноз обязательное поле для заполнения
                                </span>
                            </div>
                            <div class="mb-3">
                                <button type="submit" :disabled="processing" @click.prevent="hospitalSearch()" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Поиск ближайшей больницы" }}
                                </button>
                            </div>
                        </form>


<!--                        После того как нашли ближайщую свободную больницу-->
                        <h4>Ближайщая свободная больница</h4>
                        <div class="fs-4 fw-bolder">Больница № 5 операционня 504<br></div>
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="button" class="btn btn-primary" @click.prevent="saveBook()">Забронировать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {ref, computed} from 'vue';
import useValidate from '@vuelidate/core'
import { required} from '@vuelidate/validators'
import { Modal } from 'bootstrap'

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
            url: 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address',
            token: import.meta.env.VITE_APP_DADATA_API_KEY,
            // query: null,
            suggestions: true,
            // addresses: [],
            disease: null,
            processing: false,
            timeout: null,
            myModal: null,
            success: null,
            clock: 2,
            warning: null,
            bookings: [],
            statuses:[
                {val: 0, label: 'Свободна', color: 'green'},
                {val: 1, label: 'Занята', color: 'red'},
                {val: 2, label: 'Условно занята', color: 'yellow'}
            ],
        }
    },
    validations() {
        return {
            suggestion: { required },
            disease: { required },
        }
    },
    mounted() {
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
                console.log(err.response);
                this.errors = err.response.data.message;
            });
        },
        getBooking() {
            axios.get(`/api/bookings`,{
                headers: {Authorization: localStorage.getItem('access_token')},
            }).then(res => {
                console.log(res);
                this.bookings = res.data;
                console.log(this.rooms);
            }).catch(err => {
                console.log(err);
                console.log(err.response);
                this.errors = err.response.data.message;
            });
        },
        addBooking() {
            this.myModal = new Modal(document.getElementById('modalBooking'), {});
            this.myModal.show();
        },
        hospitalSearch() {
            console.log(1111);
            this.v$.$validate();
        },
        saveBook() {
            this.success = 'Бронь успешно дабавлена.';
            setTimeout(()=>{
                this.success = null;
            },1500)
            this.myModal.hide();
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
                    .catch(error => console.log("error", error));
            }, time)
        }

    },



}
</script>

<style>
.autocomplete {
    /*the container must be positioned relative:*/
    position: relative;
    /*display: inline-block;*/
}

.autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    /*position the autocomplete items to be the same width as the container:*/
    top: 100%;
    left: 0;
    right: 0;
}
.autocomplete-items div {
    /*padding: 10px;*/
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
}
autocomplete-disabled-item  div {
    border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
    /*when hovering an item:*/
    background-color: #e9e9e9;
}

.bg-gray-300 {
    background-color: #e9ecef;
}
.bg-red-400 {
    background-color: #e35d6a;
}
.bg-green-400 {
    background-color: #479f76;
}
.bg-yellow-400 {
    background-color: #ffcd39;
}

/*.bg-red-300 {*/
/*    background-color: #ea868f;*/
/*}*/
/*.bg-green-300 {*/
/*    background-color: #75b798;*/
/*}*/
/*.bg-yellow-300 {*/
/*    background-color: #ffda6a;*/
/*}*/

.bg-red-200 {
    background-color: #f1aeb5;
}
.bg-green-200 {
    background-color: #a3cfbb;
}
.bg-yellow-200 {
    background-color: #ffe69c;
}
</style>

<style scoped>

</style>
