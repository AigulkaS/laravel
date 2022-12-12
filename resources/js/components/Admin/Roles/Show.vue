<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="errors" class="alert alert-danger" role="alert">
                {{errors}}
            </div>
            <div class="col-12" v-if="role">

                <div class="d-flex my-3">
                    <div class="me-auto ">
                        <h4 class="my-3">Роль {{role.name}}</h4>
                    </div>
                    <div class="align-self-center">
                        <router-link :to="{name: 'role_edit'}" type="button" class="btn btn-warning">
                            <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                        </router-link>
                    </div>
                </div>

                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th class="col-2">Наименование</th>
                        <td class="col-10">{{role.name}}</td>
                    </tr>
                    <tr>
                        <th class="col-2">Имя</th>
                        <td class="col-10">{{role.label ? role.label : 'label'}}</td>
                    </tr>
                    <tr>
                        <th class="col-2">Разрешения</th>
                        <td class="col-10">
                            <div class="col-sm-10" v-if="role.permissions">
                                <ul v-for="permission in role.permissions">
                                    <li>{{permission.label}}</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>



<!--                <div class="row">-->
<!--                    <div class="col-sm-2 fw-bold ">Наименование</div>-->
<!--                    <div class="col-sm-10">{{role.name}}</div>-->
<!--                </div>-->

<!--                <div class="row">-->
<!--                    <div class="col-sm-2 fw-bold">Имя</div>-->
<!--                    <div class="col-sm-10">{{role.label ? role.label : 'label'}}</div>-->
<!--                </div>-->

<!--                <div class="row">-->
<!--                    <div class="col-sm-2 fw-bold">Разрешения </div>-->
<!--                    <div class="col-sm-10" v-if="role.permissions">-->
<!--                        <ul v-for="permission in role.permissions">-->
<!--                            <li>{{permission.label}}</li>-->
<!--                        </ul>-->
<!--                    </div>-->
<!--                </div>-->

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
            role: null,
            errors : null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get(`/api/roles/${this.id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.role = res.data.data;
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
