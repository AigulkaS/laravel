<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="errors" class="alert alert-danger" role="alert">
                {{errors}}
            </div>
            <div class="col-12" v-if="hospital">

                <div class="d-flex my-3">
                    <div class="me-auto ">
                        <h4 class="my-3">Больница {{hospital.short_name}}</h4>
                    </div>
                    <div class="align-self-center">
                        <router-link :to="{name: 'hospital_edit'}" type="button" class="btn btn-warning">
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
                            <span v-for="(room, index) in hospital.rooms">
                                {{(index+1 !== hospital.rooms.length) ? `${room.name}, ` : room.name}}
                            </span>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="col-12 my-3">
                    <button @click.prevent="$router.go(-1)"  class="btn btn-primary btn-block">
                        Назад
                    </button>
                </div>
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
            errors : null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get(`/api/hospitals/${this.id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.hospital = res.data.data;
            }).catch(err => {
                console.log(err.response);
                this.errors = err.response.data.message;
            });
        },
    }
}
</script>

<style scoped>

</style>
