<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Сброс пароля</div>
                        <div class="card-body">

                            <div v-if="success" class="alert alert-success" role="alert">
                                {{success}}
                            </div>

                            <errors-validation :validationErrors="errs"/>

                            <form class="row" @submit.prevent="forgotPassword" >
                                <div class="form-group col-12 my-2">
                                    <label class="font-weight-bold" :class="v$.email.$error ? 'text-danger' : ''">
                                        Email<span class="text-danger">*</span>
                                    </label>
                                    <input type="email" v-model="v$.email.$model" name="email" id="password"
                                           class="form-control" :class="v$.email.$error ? 'border-danger' : ''">
                                    <span v-if="v$.email.$error" :class="v$.email.$error ? 'text-danger' : ''">
                                <template v-if="!v$.email.email.$response">
                                  Неккоректный Email
                                </template>
                                <template v-else>
                                  Email обязательное поле для заполнения
                                </template>
                            </span>
                                </div>
                                <div class="col-12 my-3 ">
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
    </div>
</template>

<script>
import useValidate from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import {wait} from "../../consts";

export default {
    name: "ForgotPassword",
    data() {
        return {
            v$: useValidate(),
            processing:false,
            email : '',
            errors : null,
            success : null,
            wait
        }
    },
    validations() {
        return {
            email: { required, email},
        }
    },
    mounted() {
        this.successPage = true;
    },
    methods: {
        forgotPassword(){
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.errs = null;
                this.success = null;
                this.processing = true;
                axios.post('/api/forgot-password' , {'email': this.email})
                    .then((res) =>{
                        console.log(res)
                        this.success = res.data.message;
                    })
                    .catch((err) =>{
                        this.errorsMessage(err);
                    })
                    .finally(() => {
                        this.processing = false;
                    })
            } else {
                window.scrollTo(0,0);
            }

        }
    }
}
</script>
