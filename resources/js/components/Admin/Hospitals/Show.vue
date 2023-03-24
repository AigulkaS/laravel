<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div v-if="['hospital_show_user', 'hospital_show'].includes($route.name)" class="row justify-content-center">

                <errors-validation :validationErrors="errs"/>

                <div class="col-12" v-if="hospital">

                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">{{hospital.short_name}}</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: $route.name == 'hospital_show' ? 'hospital_edit' : 'hospital_edit_user'}" type="button" class="btn btn-warning">
                                <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                            </router-link>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th class="col-2">Полное наименование</th>
                            <td class="col-10">{{hospital.full_name}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Кароткое наименование</th>
                            <td class="col-10">{{hospital.short_name}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Адрес</th>
                            <td class="col-10">{{hospital.address}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Кабинеты</th>
                            <td class="col-10">
<!--                            <span v-for="(room, index) in hospital.rooms">-->
<!--                                {{(index+1 !== hospital.rooms.length) ? `${room.name}, ` : room.name}}-->
<!--                            </span>-->
                                <div v-for="(room, index) in hospital.rooms">
                                    <div class="fw-bolder">{{room.name}}</div>
                                    <div>Время работы: {{room.start ? room.start : '08^00'}}-{{ room.end ? room.end : '08^00' }}</div>
                                </div>

                            </td>

                        </tr>
                        </tbody>
                    </table>

                    <div v-if="$route.name == 'hospital_show'" class="col-12 my-3">
                        <button @click.prevent="$router.go(-1)"  class="btn btn-primary btn-block">
                            Назад
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <router-view @change_data = "onChangeChild"
                ></router-view>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Show",
    props: ['id'],
    data() {
        return {
            hospital: null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user')
                ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        getData() {
            console.log( this.auth_user.hospital_id)
            axios.get(`/api/hospitals/${this.id ? this.id : this.auth_user.hospital_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.hospital = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
        onChangeChild (value) {
            this.hospital = value;
        },
    }
}
</script>

<style scoped>

</style>
