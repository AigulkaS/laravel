<template>
    <div>
        <div id="today" class="text-center fw-bold">
            <h3 class="mb-6">Сегодня - 23.12.2022 (ПТ)</h3>
        </div>

        <div v-if="success" class="alert alert-success" role="alert">
            {{success}}
        </div>

        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalBooking">
                <font-awesome-icon icon="fa-solid fa-plus" /> Добавить бронь
            </button>
        </div>
        <div class="row mt-4">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">

                        <div class="d-flex bd-highlight mb-3">
                            <div class="me-auto p-1 bd-highlight col-5 fw-bolder">Больница №5 45а45 4а5п4в5 454а5 4в6аа4в65</div>
                            <!--                        <div class="p-2 bd-highlight align-self-center">Дежурные</div>-->
                            <div class="p-1 bd-highlight align-self-center">
                                <table class="table table-sm table-borderless m-0">
                                    <tbody>
                                    <tr>
                                        <th class="py-0">Деж. Хирург</th>
                                        <td class="py-0">Иваgdfdfнов И.И.</td>
                                    </tr>
                                    <tr>
                                        <th class="py-0">Деж. Кардиолог</th>
                                        <td class="py-0">Петfdgdfgdров П.П.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                    <div class="card-body">

                        <div class="container">

                            <div class="row bg-gray-300 fw-bolder">Операционная 104</div>
                            <div class="row mb-3">
                                <div class="col border bg-red-300 p-3 rounded text-center">
                                    <div><h4>12:00</h4></div>
                                    <div class="text-white fw-bolder">Занята</div>
                                </div>
                                <div class="col border bg-green-300 p-3 rounded text-center">
                                    <div><h4>13:00</h4></div>
                                    <div class="text-white fw-bolder">Свободна</div>
                                </div>
                                <div class="col d-none d-sm-block border bg-yellow-300 p-3 rounded text-center">
                                    <div><h4>14:00</h4></div>
                                    <div class="text-white fw-bolder">Бронь</div>
                                </div>
                            </div>


                        </div>

                        <a href="#" class="btn btn-primary">Read more</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
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
            clock: 2
        }
    },
    validations() {
        return {
            suggestion: { required },
            disease: { required },
        }
    },
    mounted() {
        this.myModal = new Modal(document.getElementById('modalBooking'), {})
    },
    methods: {
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
    beforeUnmount() {
        this.myModal.hide();
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
