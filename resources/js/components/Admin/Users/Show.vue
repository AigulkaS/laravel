<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div class="row justify-content-center">

                <errors-validation :validationErrors="errs"/>

                <div class="col-12" v-if="user">

                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Пользователь {{user.last_name}} {{user.first_name}} {{user.patronymic}}}</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'user_edit'}" type="button" class="btn btn-warning">
                                <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                            </router-link>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th class="col-2">Email</th>
                            <td class="col-10">{{user.email}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Фамилия</th>
                            <td class="col-10">{{user.last_name}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Имя</th>
                            <td class="col-10">{{user.first_name}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Отчество</th>
                            <td class="col-10">{{user.patronymic}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Потверждение почты</th>
                            <td class="col-10">
                                <div class="form-check form-switch">
                                    <div class='form-check form-switch'>
                                        <input type="checkbox" v-model="verify_email" disabled class="form-check-input">
                                        <label class="form-check-label">
                                            {{ !!verify_email ? 'Email подтвержден' : 'Email НЕ подтвержден'}}
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-2">PUSH уведомления</th>
                            <td class="col-10">
                                <div class="form-check form-switch">
                                    <div class='form-check form-switch'>
                                        <input type="checkbox" v-model="user.push" disabled class="form-check-input" id="push">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-2">SMS уведомления</th>
                            <td class="col-10">
                                <div class="col-sm-10 form-check form-switch">
                                    <div class='form-check form-switch'>
                                        <input type="checkbox" :v-model="user.sms" disabled class="form-check-input" id="sms">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-2">Роль</th>
                            <td class="col-10">{{user.role_id}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Больница</th>
                            <td class="col-10">{{user.hospital_id}}</td>
                        </tr>
                        <tr>
                            <th class="col-2">Телефон</th>
                            <td class="col-10">{{user.phone}}</td>
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
            user: null,
            verify_email: 1,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get(`/api/users/${this.id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.user = res.data.data;
                this.user.push = this.user.push ? true : false;
                this.user.sms = this.user.sms ? true : false;
                this.verify_email = this.verify_email ? true : false;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
    }
}
</script>

<style scoped>

</style>
