<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Сброс пароля</div>
                <div class="card-body">
                    <form action="javascript:void(0)" @submit="passwordReset" class="row" method="post">
                        <div class="form-group col-12 my-2">
                            <label for="password" class="font-weight-bold" :class="v$.password.$error ? 'text-danger' : ''">
                                Пароль<span class="text-danger">*</span>
                            </label>
                            <input type="password" v-model="v$.password.$model" name="password" id="password"
                                   class="form-control" :class="v$.password.$error ? 'border-danger' : ''">
                            <span v-if="v$.password.$error" :class="v$.password.$error ? 'text-danger' : ''">
<!--                                    {{ v$.auth.password.$errors[0].$message }}-->
                                Пароль обязательное поле для заполнения
                            </span>
                        </div>
                        <div class="col-12 my-3 ">
                            <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? "Please wait" : "отправить ссылку для сброса пароля" }}
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
import { required, email } from '@vuelidate/validators'
export default {
    name: "PasswordReset",
    data() {
        return {
            v$: useValidate(),
            password: '',
            processing:false
        }
    },
    validations() {
        return {
            password: { required},
        }
    },
    methods: {
        passwordReset() {
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                console.log(55555);
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
