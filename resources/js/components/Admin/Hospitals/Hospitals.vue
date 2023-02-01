<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="$route.name == 'hospitals'">
                <div class="row" v-if="hospitals">
                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Больницы</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'hospital_create'}" type="button" class="btn btn-primary">
                                <font-awesome-icon icon="fa-solid fa-plus" /> Добавить новую больницу
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
                                <th scope="col">Адрес</th>
                                <th scope="col">Кабинеты</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(hospital, index) in hospitals">
                                <td scope="row" data-label="id">{{hospital.id}}</td>
                                <td data-label="Наименование">
                                    <router-link :to="{name: 'hospital_show', params: {id: hospital.id}}">
                                        {{hospital.short_name}}</router-link>
                                </td>
                                <td data-label="Адрес">
                                    {{hospital.address}}
                                </td>
                                <td data-label="Кабинеты">
                                    <ul class="list-style-none my-1 px-1" v-for="room in hospital.rooms">
                                        <li class="pl-1">{{ room.name }}</li>
                                    </ul>
                                </td>
                                <td data-label="Редактировать">
                                    <router-link :to="{name: 'hospital_edit', params: {'id': hospital.id}}" tag="button" class="btn btn-warning" title="Редактировать">
                                        <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                                    </router-link>
                                </td>
                                <td data-label="Удалить">
                                    <button @click.prevent="deleteItem(hospital.id)" class="btn btn-danger" title="Удалить">
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
export default {
    name: "Hospitals",
    data() {
        return {
            hospitals: null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/hospitals', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.hospitals = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteItem(hospital_id) {
            this.eers = null;
            axios.delete(`/api/hospitals/${hospital_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData();
                this.success = 'Вы удалили больницу';
                setTimeout(()=>{
                    this.success = null;
                },1500)
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        onAddChild (value) {
            this.hospitals.push(value);
        },
        onChangeChild (value) {
            let hospitalIndex = this.hospitals.findIndex(el => el.id == value.id);
            if (hospitalIndex != -1) {
                this.hospitals[hospitalIndex].address = value.address;
                this.hospitals[hospitalIndex].full_name = value.full_name;
                this.hospitals[hospitalIndex].short_name = value.short_name;
                this.hospitals[hospitalIndex].rooms = value.rooms;
            }
        },
    }
}
</script>

<style scoped>
.list-style-none {
    list-style-type: none;
}
</style>
