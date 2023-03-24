<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">

                <errors-validation :validationErrors="errs"/>

                <div class="col-12" v-if="station">

                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Больница {{station.short_name}}</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'ambulance_station_edit'}" type="button"
                                         class="btn btn-warning">
                                <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                            </router-link>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th class="col-2">Полное наименование</th>
                            <td class="col-10">{{station.full_name}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Кароткое наименование</th>
                            <td class="col-10">{{station.short_name}}</td>
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
    </div>

</template>

<script>
export default {
    name: "Show",
    props: ['id'],
    data() {
        return {
            station: null,
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
                this.station = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
    }
}
</script>

