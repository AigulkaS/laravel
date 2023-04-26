<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" :class="$route.name == 'user_show_for_moderator' ? 'container' : ''">
            <div class="row justify-content-center">

                <errors-validation :validationErrors="errs"/>

                <div class="col-12" v-if="user">

                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Пользователь {{user.last_name}} {{user.first_name}} {{user.patronymic}}}</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: $route.name == 'user_show' ? 'user_edit' : 'user_edit_for_moderator'}" type="button" class="btn btn-warning">
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
                                <div class="form-check form-switch">
                                    <div class='form-check form-switch'>
                                        <input type="checkbox" v-model="user.sms" disabled class="form-check-input" id="sms">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="col-2">Роль</th>
                            <td class="col-10">{{user.role_label}}</td>
                        </tr>
                        <tr v-if="user.role_name !== roles.admin">
                            <th class="col-2">
                                {{user.hospital_type && user.hospital_type == hospital_type.hospital
                                ? 'Больница' : 'СМП'}}
                            </th>
                            <td class="col-10">{{user.hospital_name}}</td>
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
import {hospital_type, roles} from "../../../consts";

export default {
    name: "Show",
    props: ['id'],
    data() {
        return {
            user: null,
            verify_email: 1,
            success : null,
            hospital_type, roles
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
                this.verify_email = this.verify_email == 1 ? true : false;
                this.user.push = this.user.push == 1 ? true : false;
                this.user.sms = this.user.sms == 1 ? true : false;
                console.log(this.user)
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true);
        },
    }
}
</script>

<style scoped>

</style>
