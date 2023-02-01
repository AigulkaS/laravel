<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card shadow sm">
                        <div class="card-body">
                            <h1 class="text-center">Регистрация</h1>
                            <hr/>

                            <errors-validation :validationErrors="errs"/>


                            <form @submit.prevent="register" class="row">

                                <div class="form-group col-12 my-2 ">
                                    <label for="email" class="font-weight-bold"
                                           :class="v$.user.email.$error ? 'text-danger' : ''">
                                        Email<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="email" id="email" placeholder="Email"
                                           class="form-control "
                                           v-model.lazy="v$.user.email.$model"
                                           :class="v$.user.email.$error ? 'border-danger' : ''"
                                    >
                                    <span v-if="v$.user.email.$error"
                                          :class="v$.user.email.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.email.email.$response">
                                      Неккоректный Email
                                    </template>
                                    <template v-else>
                                      Email обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12">
                                    <label for="password" class="font-weight-bold"
                                           :class="v$.user.password.$error ? 'text-danger' : ''">
                                        Пароль<span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input :type="inputType" name="password" v-model.lazy="v$.user.password.$model"
                                               id="password" placeholder="Пароль" class="form-control"
                                               :class="v$.user.password.$error ? 'border-danger' : ''"
                                               aria-describedby="basic-addon2"
                                        >
                                        <span @click.prevent="updateTypeInput()"
                                              class="input-group-text cursor" id="basic-addon2" >
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                        </span>
                                    </div>
                                    <span v-if="v$.user.password.$error"
                                          :class="v$.user.password.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.password.minLength.$response">
                                      Пароль должен содержать не менее 6 символов.
                                    </template>
                                    <template v-else>
                                      Пароль обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12 my-2">
                                    <label for="password_confirmation" class="font-weight-bold"
                                           :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
                                        Повторите пароль<span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input :type="inputType" name="password_confirmation"
                                               v-model.lazy="v$.user.password_confirmation.$model"
                                               id="password_confirmation" placeholder="Пароль" class="form-control"
                                               :class="v$.user.password_confirmation.$error ? 'border-danger' : ''"
                                        >
                                        <span @click.prevent="updateTypeInput()"
                                              class="input-group-text cursor">
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                        </span>
                                    </div>

                                    <span v-if="v$.user.password_confirmation.$error"
                                          :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.password_confirmation.sameAs.$response">
                                      Данное значение не совпадает с паролем
                                    </template>
                                    <template v-else>
                                      Подтверждение пароля обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12 my-2">
                                    <label class="font-weight-bold"
                                           :class="v$.user.hospital_id.$error ? 'text-danger' : ''">
                                        Больница<span class="text-danger">*</span>
                                    </label>
                                    <Multiselect
                                        v-model="user.hospital_id"
                                        :close-on-select="true"
                                        :hide-selected="false"
                                        label="full_name"
                                        valueProp="id"
                                        :options="hospitals ? hospitals : []"
                                    />
                                    <span v-if="v$.user.hospital_id.$error"
                                          :class="v$.user.hospital_id.$error ? 'text-danger' : ''">
                                      Больница обязательное поле для заполнения
                                </span>
                                </div>

                                <div class="form-group col-12 my-2">
                                    <label class="font-weight-bold"
                                           :class="v$.user.role_id.$error ? 'text-danger' : ''">
                                        Роль<span class="text-danger">*</span>
                                    </label>
                                    <Multiselect
                                        v-model="user.role_id"
                                        :close-on-select="true"
                                        :hide-selected="false"
                                        label="name"
                                        valueProp="id"
                                        :options="roles ? roles : []"
                                    />
                                    <span v-if="v$.user.role_id.$error"
                                          :class="v$.user.role_id.$error ? 'text-danger' : ''">
                                      Роль обязательное поле для заполнения
                                </span>
                                </div>

                                <div class="form-group col-12">
                                    <label for="last_name" class="font-weight-bold"
                                           :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                        Фамилия<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="last_name" v-model.lazy="v$.user.last_name.$model"
                                           id="last_name" placeholder="Фамилия" class="form-control"
                                           :class="v$.user.last_name.$error ? 'border-danger' : ''">
                                    <span v-if="v$.user.last_name.$error"
                                          :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.last_name.minLength.$response">
                                      Поле фамилия должно содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Фамилия обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12">
                                    <label for="first_name" class="font-weight-bold"
                                           :class="v$.user.first_name.$error ? 'text-danger' : ''">
                                        Имя<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="first_name" v-model="v$.user.first_name.$model"
                                           id="first_name" placeholder="Имя" class="form-control"
                                           :class="v$.user.first_name.$error ? 'border-danger' : ''">
                                    <span v-if="v$.user.first_name.$error"
                                          :class="v$.user.first_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.first_name.minLength.$response">
                                      Поле имя должен содержать не менее 3 символов.
                                    </template>
                                    <template v-else>
                                      Поле имя обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12">
                                    <label for="patronymic" class="font-weight-bold"
                                           :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                        Отчество<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="patronymic" v-model="v$.user.patronymic.$model"
                                           id="patronymic" placeholder="Отчество" class="form-control"
                                           :class="v$.user.patronymic.$error ? 'border-danger' : ''">
                                    <span v-if="v$.user.patronymic.$error"
                                          :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.patronymic.minLength.$response">
                                      Поле отчество должен содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Поле отчество обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12">
                                    <label for="phone" class="font-weight-bold">Телефон</label>
                                    <input type="text" name="phone" v-model="user.phone" id="phone"
                                           placeholder="Телефон" class="form-control">
                                </div>

                                <div class="col-12 my-3 text-center">
                                    <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                        {{ processing ? wait : "Зарегистрироваться" }}
                                    </button>
                                </div>

                                <div class="col-12 text-center">
                                    <label>У вас уже есть аккаунт?
                                        <router-link :to="{name:'login'}">Войти сейчас!</router-link>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useValidate from '@vuelidate/core'
import { required, email, minLength, sameAs } from '@vuelidate/validators'
import {wait} from "../../consts";

export default {
    name: "Register",
    data(){
        return {
            v$: useValidate(),
            user:{
                email:"",
                password:"",
                password_confirmation:"",
                hospital_id: "",
                last_name:"",
                first_name: "",
                patronymic: "",
                phone: ""
            },
            inputType: 'password',
            hospitals: null,
            roles: null,
            processing:false,
            wait,
        }
    },
    validations() {
        return {
            user: {
                email: { required, email },
                password: { required, minLength: minLength(6) },
                password_confirmation: { required, sameAs: sameAs(this.user.password)},
                hospital_id: {required},
                role_id: {required},
                last_name: {required, minLength: minLength(5)},
                first_name: {required, minLength: minLength(3)},
                patronymic: {required, minLength: minLength(5)},
            }
        }
    },
    mounted() {
        this.getData();
    },
    methods:{
        getData() {
            axios.get('/api/users/create').then(res => {
                console.log(res);
                this.hospitals = res.data.hospitals;
                this.roles = res.data.roles;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        updateTypeInput() {
            this.inputType = this.inputType == 'text' ? 'password' : 'text';
        },
        register(){
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                this.errs = null;
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/register', this.user)
                        .then(res => {
                            console.log(res);
                            localStorage.setItem('access_token', `${res.data.token_type} ${res.data.access_token}`);
                            localStorage.setItem('auth_user', JSON.stringify(res.data.auth_user));
                            this.$router.push({name: "home"})
                        })
                        .catch(err => {
                            this.errorsMessage(err);
                        })
                        .finally(() => {
                            this.processing = false
                        });
                });
            } else {
                window.scrollTo(0,0);
            }
        }
    }
}
</script>
