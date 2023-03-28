<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">

                <errors-validation :validationErrors="errs"/>

                <div class="col-12" v-if="user">

                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Пользователь {{user.last_name}} {{user.first_name}} {{user.patronymic}}}</h4>
                        </div>
                        <div class="align-self-center">
                            <button @click.prevent="editUser()" type="button" class="btn btn-warning">
                                <font-awesome-icon icon="fa-solid fa-pencil" /> Редактировать
                            </button>
                        </div>
                    </div>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <form @submit.prevent="update" class="row" >
                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold ">Email</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" :value="user.email">
                            </div>
                        </div>
                        <div class="form-group row my-1">
                            <label for="last_name" class="col-sm-2 col-form-label fw-bold">
                                Фамилия<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="last_name" v-model.lazy="v$.user.last_name.$model"
                                       id="last_name" placeholder="Фамилия" class="form-control"
                                       :class="v$.user.last_name.$error ? 'border-danger' : '',
                                   edit ? '' : 'form-control-plaintext'">
                                <span v-if="v$.user.last_name.$error && edit" :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.last_name.minLength.$response">
                                      Поле фамилия должно содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Фамилия обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="first_name" class="col-sm-2 col-form-label fw-bold">
                                Имя<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="first_name" id="first_name"
                                       v-model="v$.user.first_name.$model" class="form-control"
                                       placeholder="Имя" :class="v$.user.first_name.$error ? 'border-danger' : '',
                                   edit ? '' : 'form-control-plaintext'">
                                <span v-if="v$.user.first_name.$error && edit" :class="v$.user.first_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.first_name.minLength.$response">
                                      Поле имя должен содержать не менее 3 символов.
                                    </template>
                                    <template v-else>
                                      Поле имя обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="patronymic" class="col-sm-2 col-form-label fw-bold">
                                Отчество<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-10">
                                <input type="text" name="patronymic" v-model="v$.user.patronymic.$model" id="patronymic"
                                       placeholder="Отчество" class="form-control"
                                       :class="v$.user.patronymic.$error ? 'border-danger' : '',
                                    edit ? '' : 'form-control-plaintext'">
                                <span v-if="v$.user.patronymic.$error && edit" :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.patronymic.minLength.$response">
                                      Поле отчество должен содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Поле отчество обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="verify_email" class="col-sm-2 col-form-label fw-bold">Потверждение почты</label>
                            <div class="col-sm-10 form-check form-switch">
                                <div class='form-check form-switch mt-3'>
                                    <input type="checkbox" v-model="verify_email" disabled  class="form-check-input" id="verify_email">
                                    <label class="form-check-label" for="verify_email">
                                        {{ verify_email ? 'Email подтвержден' : 'Email НЕ подтвержден'}}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="push" class="col-sm-2 col-form-label fw-bold">PUSH уведомления</label>
                            <div class="col-sm-10 form-check form-switch">
                                <div class='form-check form-switch mt-3'>
                                    <input type="checkbox" :disabled="edit ? false : true"  v-model="user.push" class="form-check-input" id="push">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="sms" class="col-sm-2 col-form-label fw-bold">SMS уведомления</label>
                            <div class="col-sm-10 form-check form-switch">
                                <div class='form-check form-switch mt-3'>
                                    <input type="checkbox" :disabled="edit ? false : true" v-model="user.sms" class="form-check-input" id="sms">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold">
                                Роль
                            </label>
                            <div class="col-sm-10">
                                <div class="form-control-plaintext">{{user.role_label}}</div>
<!--                                <div v-if="!edit" class="form-control-plaintext">{{user.role_name}}</div>-->
<!--                                <select v-else class="form-control form-select" v-model="user.role_id">-->
<!--                                    <option v-for="role in roles" :key="role.id" :value="role.id">-->
<!--                                        {{ role.name }}-->
<!--                                    </option>-->
<!--                                </select>-->
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label class="col-sm-2 col-form-label fw-bold">
                                Больница
                            </label>
                            <div class="col-sm-10">
                                <div class="form-control-plaintext">{{user.hospital_name}}</div>
<!--                                <div v-if="!edit" class="form-control-plaintext">{{user.hospital_name}}</div>-->
<!--                                <select v-else class="form-control form-select" v-model="user.hospital_id">-->
<!--                                    <option v-for="hospital in hospitals" :key="hospital.id" :value="hospital.id">-->
<!--                                        {{ hospital.short_name }}-->
<!--                                    </option>-->
<!--                                </select>-->
                            </div>
                        </div>

                        <div class="form-group row my-1">
                            <label for="phone" class="col-sm-2 col-form-label fw-bold">Телефон</label>
                            <div class="col-sm-10">
                                <input type="text" name="phone" v-model="user.phone" id="phone"
                                       placeholder="Телефон" @input="acceptNumber"
                                       :class="edit ? 'form-control' : 'form-control-plaintext'">
                            </div>
                        </div>


                        <div v-if="edit" class="col-12 my-3 text-center">
                            <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? wait : "Сохранить" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import useValidate from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'
import {wait} from "../consts";

export default {
    name: "Profile",
    data() {
        return {
            v$: useValidate(),
            user: null,
            verify_email: 1,
            success : null,
            processing: false,
            edit: false,
            roles: [],
            hospitals: [],
            smps: [],
            wait,
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            user: {
                last_name: {required, minLength: minLength(5)},
                first_name: {required, minLength: minLength(3)},
                patronymic: {required, minLength: minLength(5)},
            }
        }
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        getData() {
            console.log(this.auth_user)
            axios.get(`/api/users/${this.auth_user.id}`, {
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
        editUser() {
            axios.get(`/api/users/${this.auth_user.id}/edit`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.roles = res.data.roles;
                this.hospitals = res.data.hospitals;
                this.smps = res.data.smps;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.edit=!this.edit);

        },
        update() {
            this.errs = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                this.user.push = this.user.push ? 1 : 0;
                this.user.sms = this.user.sms ? 1 : 0;
                axios.patch(`/api/users/${this.auth_user.id}`, this.user, {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.user = res.data.data;
                    this.user.push = this.user.push == 1 ? true : false;
                    this.user.sms = this.user.sms == 1 ? true : false;
                    this.success = 'Данные успешно изменены.';
                    setTimeout(()=>{
                        this.success =null;
                    },3000)
                }).catch(err => {
                    this.errorsMessage(err);
                }).finally(() => {
                    this.processing = false;
                    this.edit = !this.edit;
                })
            } else {
                window.scrollTo(0,0);
            }
        },
        acceptNumber() {
            var x = this.user.phone.replace(/\D/g, '')
                .match(/^(\+7|7|8)(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);

            let y = x && x[1]
                ? ['+', '7', '8'].includes(x[1]) ? '+7' : ''
                : '';
            let z = x && x[2] ? '(' + x[2] + ')' : '';
            let c = x && x[3] ? x[3] : '';
            let v = x && x[4] ? '-' + x[4] : '';
            let b = x && x[5] ? '-' + x[5] : '';
            this.user.phone= y+z+c+v+b;
        },
    }
}
</script>

<style scoped>

</style>
