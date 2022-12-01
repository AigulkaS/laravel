<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Сброс пароля</div>
                <div class="card-body">
                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>
                    <div v-if="errors" class="alert alert-danger" role="alert">
                        {{errors}}
                    </div>

                    <form class="row" @submit.prevent="forgotPassword" method="post">
                        <div class="form-group col-12 my-2">
                            <label class="font-weight-bold" :class="v$.email.$error ? 'text-danger' : ''">
                                Email<span class="text-danger">*</span>
                            </label>
                            <input type="email" v-model="v$.email.$model" name="email" id="password"
                                   class="form-control" :class="v$.email.$error ? 'border-danger' : ''">
                            <span v-if="v$.email.$error" :class="v$.email.$error ? 'text-danger' : ''">
<!--                                    {{ v$.email.$errors[0].$message }}-->
                                <template v-if="!v$.email.email.$response">
                                  Неккоректный Email
                                </template>
                                <template v-else>
                                  Email обязательное поле для заполнения
                                </template>
                            </span>
                        </div>
                        <div class="col-12 my-3 ">
<!--                            <div v-if="busy"  class="flex justify-center items-center p-2 px-6 rounded-sm text-white bg-blue-500 hover:bg-blue-600">-->
<!--                                <circle-svg class="w-6 h-6" />-->
<!--                            </div>-->
<!--                            <button v-else type="submit"-->
                            <button type="submit"
                                    :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? "Пожалуйста подождите" : "отправить ссылку для сброса пароля" }}
                            </button>
<!--                            <router-link :to="{name : 'login'}" class="text-sm text-blue-500 hover:underline"> go back ? </router-link>-->
<!--                            <button type="submit" :disabled="processing" class="btn btn-primary btn-block">-->
<!--                                {{ processing ? "Пожалуйста подождите" : "отправить ссылку для сброса пароля" }}-->
<!--                            </button>-->
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
import { required, email } from '@vuelidate/validators'
// import CircleSvg from '../CircleSvg.vue';
export default {
    name: "ForgotPassword",
    // components: {CircleSvg},
    data() {
        return {
            v$: useValidate(),
            processing:false,
            email : '',
            errors : null,
            success : null,
            busy : false,
        }
    },
    validations() {
        return {
            email: { required, email},
        }
    },
    methods: {
        forgotPassword(){
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                // this.busy = true ;
                this.errors = null
                this.success = null;
                this.processing = true;
                axios.post('/api/forgot-password' , {'email': this.email})
                    .then((res) =>{
                        console.log(res)
                        this.success = res.data.msg;
                    })
                    .catch((err) =>{
                        console.log(err.response)
                        this.errors = err.response.data
                    })
                    .finally(() => {
                        // this.busy = false ;
                        this.processing = false;
                    })

            } else {
                window.scrollTo(0,0);
                // this.$refs.registr_form.scrollTop = 0;
            }

        }
    }
}
</script>

<style scoped>

</style>
