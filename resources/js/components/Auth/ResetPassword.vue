<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Сбросить пароль</div>
                    <div class="card-body">
                        <div v-if="success" class="alert alert-success" role="alert">
                            {{success}}
                        </div>
                        <div v-if="errors" class="alert alert-danger" role="alert">
                            {{errors}}
                        </div>

                        <form class="row" @submit.prevent="resetPassword" method="post">
                            <div class="form-group col-12 my-2">
                                <label class="font-weight-bold" for="password" :class="v$.password.$error ? 'text-danger' : ''">
                                    Пароль<span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" v-model.lazy="v$.password.$model"
                                       id="password" placeholder="Пароль" class="form-control"
                                       :class="v$.password.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.password.$error" :class="v$.password.$error ? 'text-danger' : ''">
<!--                                    {{ v$.password.$errors[0].$message }}-->
                                    <template v-if="!v$.password.minLength.$response">
                                      Пароль должен содержать не менее 6 символов.
                                    </template>
                                    <template v-else>
                                      Пароль обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password_confirmation" class="font-weight-bold"
                                       :class="v$.password_confirmation.$error ? 'text-danger' : ''">
                                    Повторите пароль<span class="text-danger">*</span>
                                </label>
                                <input type="password_confirmation" name="password_confirmation"
                                       v-model.lazy="v$.password_confirmation.$model"
                                       id="password_confirmation" placeholder="Пароль" class="form-control"
                                       :class="v$.password_confirmation.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.password_confirmation.$error" :class="v$.password_confirmation.$error ? 'text-danger' : ''">
<!--                                    {{ v$.password_confirmation.$errors[0].$message }}-->
                                    <template v-if="!v$.password_confirmation.sameAs.$response">
                                      Данное значение не совпадает с паролем
                                    </template>
                                    <template v-else>
                                      Подтверждение пароля обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>

                            <div class="col-12 my-3 ">
<!--                                <div v-if="busy"  class="flex justify-center items-center p-2 px-6 rounded-sm text-white bg-blue-500 hover:bg-blue-600">-->
<!--                                    <circle-svg class="w-6 h-6" />-->
<!--                                </div>-->
<!--                                <button v-else type="submit"-->
                                <button type="submit"
                                        :disabled="processing" class="btn btn-primary btn-block">
                                    {{ processing ? wait : "отправить ссылку для сброса пароля" }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import useValidate from '@vuelidate/core'
import {minLength, required, sameAs} from "@vuelidate/validators";
import {wait} from "../../consts";

export default {
    name: "ResetPassword",
    props: ['token', 'email'],
    data(){
        return {
            v$: useValidate(),
            password:"",
            password_confirmation:"",
            errors: null,
            success : null,
            busy : false,
            processing:false,
            wait,
        }
    },
    validations() {
        return {
            password: { required, minLength: minLength(6) },
            password_confirmation: { required, sameAs: sameAs(this.password)},
        }
    },
    methods: {
        resetPassword() {
            this.errors = null;
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.busy = true ;
                this.processing = true;
                axios.post('/api/reset-password' , {
                    'email': this.email,
                    'token': this.token,
                    'password': this.password,
                    'password_confirmation': this.password_confirmation
                })
                    .then((res) =>{
                        this.success = res.data.msg + ' перенаправление ...'
                        setTimeout(()=>{
                            this.$router.push({name:'login'})
                        },5000)
                    })
                    .catch((err) =>{
                        this.errors = err.response.data
                    })
                    .finally(() => {
                        this.busy = false ;
                        this.processing = false;
                    });
            } else {
                window.scrollTo(0,0);
            }
        }
    }
}
</script>

<style scoped>

</style>
