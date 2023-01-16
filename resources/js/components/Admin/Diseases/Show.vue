<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="errors" class="alert alert-danger" role="alert">
                {{errors}}
            </div>
            <div class="col-12" v-if="disease">

                <div class="d-flex my-3">
                    <div class="me-auto ">
                        <h4 class="my-3">Диагноз {{disease.name}}</h4>
                    </div>
                    <div class="align-self-center">
                        <router-link :to="{name: 'disease_edit'}" type="button" class="btn btn-warning">
                            <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                        </router-link>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="col-2">Наименование</th>
                        <td class="col-10">{{disease.name}}</td>
                    </tr>
                    <tr>
                        <th class="col-2">Код диагноза</th>
                        <td class="col-10">{{disease.code}}</td>
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
            disease: null,
            errors : null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get(`/api/diseases/${this.id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.disease = res.data.data;
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
