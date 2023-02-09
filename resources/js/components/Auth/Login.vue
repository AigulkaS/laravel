<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card shadow sm">
                        <div class="card-body">
                            <h1 class="text-center">Авторизация</h1>
                            <hr/>

                            <errors-validation :validationErrors="errs"/>

                            <form action="javascript:void(0)" class="row" method="post">

                                <div class="form-group col-12">
                                    <label for="email" class="font-weight-bold"
                                           :class="v$.auth.email.$error ? 'text-danger' : ''">
                                        Email<span class="text-danger">*</span>
                                    </label>
                                    <input type="text" v-model.lazy="v$.auth.email.$model"
                                           name="email" id="email" class="form-control"
                                           :class="v$.auth.email.$error ? 'border-danger' : ''">
                                    <span v-if="v$.auth.email.$error"
                                          :class="v$.auth.email.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.auth.email.email.$response">
                                      Неккоректный Email
                                    </template>
                                    <template v-else>
                                      Email обязательное поле для заполнения
                                    </template>
                                </span>
                                </div>

                                <div class="form-group col-12 my-2">
                                    <label for="password" class="font-weight-bold"
                                           :class="v$.auth.password.$error ? 'text-danger' : ''">
                                        Пароль<span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input :type="inputType" v-model="v$.auth.password.$model" name="password"
                                               id="password" class="form-control"
                                               :class="v$.auth.password.$error ? 'border-danger' : ''">
                                        <span @click.prevent="updateTypeInput()"
                                              class="input-group-text cursor">
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                        </span>
                                    </div>

                                    <span v-if="v$.auth.password.$error"
                                          :class="v$.auth.password.$error ? 'text-danger' : ''">
                                      Пароль обязательное поле для заполнения
                                </span>
                                </div>

                                <div class="col-12 mb-2">
                                    <button type="submit" :disabled="processing"
                                            @click.prevent="login" class="btn btn-primary btn-block">
                                        {{ processing ? wait : "Авторизация" }}
                                    </button>
                                </div>

                                <div class="col-12 mb-2 text-center">
                                    <router-link :to="{name: 'forgot-password'}">
                                        Забыли свой пароль</router-link>
                                </div>

                                <div class="col-12 text-center">
                                    <label>У вас нет аккаунта?
                                        <router-link :to="{name:'register'}">
                                            Зарегистрируйтесь сейчас!
                                        </router-link>
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
import { required, email } from '@vuelidate/validators'
import {wait} from "../../consts";

export default {
    name: "Login",
    data(){
        return {
            v$: useValidate(),
            auth:{
                email:"",
                password:""
            },
            inputType: 'password',
            processing:false,
            wait,
        }
    },
    validations() {
        return {
            auth: {
                email: { required, email },
                password: { required},
            }
        }
    },
    mounted() {
        this.successPage = true
    },
    methods:{
        login() {
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.errs = null;
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login', {
                        email: this.auth.email,
                        password: this.auth.password,
                    })
                        .then(res => {
                            console.log(res);
                            localStorage.setItem('access_token',
                                `${res.data.token_type} ${res.data.access_token}`);
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
        },
        updateTypeInput() {
            this.inputType = this.inputType == 'text' ? 'password' : 'text';
        },
    }
}
</script>

