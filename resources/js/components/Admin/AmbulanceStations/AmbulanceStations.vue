<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="$route.name == 'ambulance_stations'">
                <div class="row" v-if="stations">
                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Станции СМП</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'ambulance_station_create'}" type="button" class="btn btn-primary">
                                <font-awesome-icon icon="fa-solid fa-plus" /> Добавить новую станцию СМП
                            </router-link>
                        </div>
                    </div>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <div class="col-12 table-mobile">
                        <table id="table" ref="table" class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Наименование</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(station, index) in stations">
                                <td scope="row" data-label="id">{{station.id}}</td>
                                <td data-label="Наименование">
                                    <router-link :to="{name: 'ambulance_station_show', params: {id: station.id}}">
                                        {{station.short_name}}</router-link>
                                </td>
                                <td data-label="Редактировать">
                                    <router-link :to="{name: 'ambulance_station_edit',
                                    params: {'id': station.id}}" tag="button" class="btn btn-warning" title="Редактировать">
                                        <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                                    </router-link>
                                </td>
                                <td data-label="Удалить">
                                    <button @click.prevent="deleteItem(station.id)" class="btn btn-danger" title="Удалить">
                                        <font-awesome-icon icon="fa-solid fa-trash-can" :style="{ color: 'white' }" />
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                <router-view @add_data="onAddChild"
                             @change_data = "onChangeChild"
                ></router-view>
            </div>
        </div>
    </div>

</template>

<script>
import {hospital_type} from "../../../consts";

export default {
    name: "AmbulanceStations",
    data() {
        return {
            stations: null,
            success : null,
            hospital_type,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/hospitals', {
                headers: {Authorization: localStorage.getItem('access_token')},
                params: {type: this.hospital_type.smp}
            }).then(res => {
                console.log(res);
                this.stations = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteItem(station_id) {
            this.eers = null;
            axios.delete(`/api/hospitals/${station_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData();
                this.success = 'Вы удалили станцию СМП';
                setTimeout(()=>{
                    this.success = null;
                },1500)
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        onAddChild (value) {
            this.stations.push(value);
        },
        onChangeChild (value) {
            let hospitalIndex = this.stations.findIndex(el => el.id == value.id);
            if (hospitalIndex != -1) {
                this.stations[hospitalIndex].full_name = value.full_name;
                this.stations[hospitalIndex].short_name = value.short_name;
            }
        },
    }
}
</script>

<style scoped>
</style>
