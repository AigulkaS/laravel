<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Сбросить пароль</div>
                        <div class="card-body">
                            <div v-if="success" class="alert alert-success" role="alert">
                                {{success}}
                            </div>

                            <errors-validation :validationErrors="errs"/>

                            <form class="row" @submit.prevent="resetPassword" method="post">

                                <div class="form-group col-12 my-2">
                                    <label class="font-weight-bold" for="password" :class="v$.password.$error ? 'text-danger' : ''">
                                        Пароль<span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input :type="inputType" name="password"
                                               v-model.lazy="v$.password.$model"
                                               id="password" placeholder="Пароль" class="form-control"
                                               :class="v$.password.$error ? 'border-danger' : ''"
                                        >
                                        <span @click.prevent="updateTypeInput()"
                                              class="input-group-text cursor">
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                        </span>
                                    </div>
                                    <span v-if="v$.password.$error"
                                          :class="v$.password.$error ? 'text-danger' : ''">
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
                                    <div class="input-group">
                                        <input :type="inputType" name="password_confirmation"
                                               v-model.lazy="v$.password_confirmation.$model"
                                               id="password_confirmation" placeholder="Пароль" class="form-control"
                                               :class="v$.password_confirmation.$error ? 'border-danger' : ''"
                                        >
                                        <span @click.prevent="updateTypeInput()"
                                              class="input-group-text cursor">
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                        </span>
                                    </div>
                                    <span v-if="v$.password_confirmation.$error" :class="v$.password_confirmation.$error ? 'text-danger' : ''">
                                        <template v-if="!v$.password_confirmation.sameAs.$response">
                                          Данное значение не совпадает с паролем
                                        </template>
                                        <template v-else>
                                          Подтверждение пароля обязательное поле для заполнения
                                        </template>
                                    </span>
                                </div>

                                <div class="col-12 my-3 ">
                                    <button type="submit"
                                            :disabled="processing" class="btn btn-primary btn-block">
                                        {{ processing ? wait : "Сохранить" }}
                                    </button>
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
            success : null,
            processing:false,
            inputType: 'password',
            wait,
        }
    },
    validations() {
        return {
            password: { required, minLength: minLength(6) },
            password_confirmation: { required,},
            // password_confirmation: { required, sameAs: sameAs(this.password)},
        }
    },
    mounted() {
        this.successPage = true;
    },
    methods: {
        resetPassword() {
            this.errs = null;
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
                }).then((res) =>{
                    console.log(res)
                    this.success = res.data.message + ' перенаправление ...';
                    setTimeout(()=>{
                        this.$router.push({name:'login'})
                    },3000)
                }).catch((err) =>{
                    this.errorsMessage(err);
                }).finally(() => {this.processing = false;});
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

