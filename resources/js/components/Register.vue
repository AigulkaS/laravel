<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Регистрация</h1>
                        <hr/>
                        <form action="javascript:void(0)" @submit="register" class="row" method="post" ref="registr_form">
<!--                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">-->
<!--                                <div class="alert alert-danger">-->
<!--                                    <ul class="mb-0">-->
<!--                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="form-group col-12 my-2 ">
                                <label for="email" class="font-weight-bold" :class="v$.user.email.$error ? 'text-danger' : ''">
                                    Email<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="email"
                                        id="email"
                                       placeholder="Email" class="form-control "
                                       v-model.lazy="v$.user.email.$model"
                                       :class="v$.user.email.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.user.email.$error" :class="v$.user.email.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.email.$errors[0].$message }}-->
                                    <template v-if="!v$.user.email.email.$response">
                                      Неккоректный Email
                                    </template>
                                    <template v-else>
                                      Email обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12">
                                <label for="password" class="font-weight-bold" :class="v$.user.password.$error ? 'text-danger' : ''">
                                    Пароль<span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" v-model.lazy="v$.user.password.$model"
                                       id="password" placeholder="Пароль" class="form-control"
                                       :class="v$.user.password.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.user.password.$error" :class="v$.user.password.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.password.$errors[0].$message }}-->
                                    <template v-if="!v$.user.password.minLength.$response">
                                      Пароль должен содержать не менее 6 символов.
                                    </template>
                                    <template v-else>
                                      Пароль обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password_confirmation" class="font-weight-bold" :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
                                    Повторите пароль<span class="text-danger">*</span>
                                </label>
                                <input type="password_confirmation" name="password_confirmation"
                                       v-model.lazy="v$.user.password_confirmation.$model"
                                       id="password_confirmation" placeholder="Пароль" class="form-control"
                                       :class="v$.user.password_confirmation.$error ? 'border-danger' : ''"
                                >
                                <span v-if="v$.user.password_confirmation.$error" :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.password_confirmation.$errors[0].$message }}-->
                                    <template v-if="!v$.user.password_confirmation.sameAs.$response">
                                      Данное значение не совпадает с паролем
                                    </template>
                                    <template v-else>
                                      Подтверждение пароля обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12">
                                <label for="firstname" class="font-weight-bold" :class="v$.user.firstname.$error ? 'text-danger' : ''">
                                    Фамилия<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="firstname" v-model.lazy="v$.user.firstname.$model" id="firstname"
                                       placeholder="Фамилия" class="form-control"
                                       :class="v$.user.firstname.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.firstname.$error" :class="v$.user.firstname.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.firstname.$errors[0].$message }}-->
                                    <template v-if="!v$.user.firstname.minLength.$response">
                                      Поле фамилия должно содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Фамилия обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12">
                                <label for="name" class="font-weight-bold" :class="v$.user.name.$error ? 'text-danger' : ''">
                                    Имя<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" v-model="v$.user.name.$model"
                                       id="name" placeholder="Имя" class="form-control"
                                       :class="v$.user.name.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.name.$error" :class="v$.user.name.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.name.$errors[0].$message }}-->
                                    <template v-if="!v$.user.name.minLength.$response">
                                      Поле имя должен содержать не менее 3 символов.
                                    </template>
                                    <template v-else>
                                      Поле имя обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12">
                                <label for="patronymic" class="font-weight-bold" :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                    Отчество<span class="text-danger">*</span>
                                </label>
                                <input type="text" name="patronymic" v-model="v$.user.patronymic.$model" id="patronymic"
                                       placeholder="Отчество" class="form-control"
                                       :class="v$.user.patronymic.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.patronymic.$error" :class="v$.user.patronymic.$error ? 'text-danger' : ''">
<!--                                    {{ v$.user.name.$errors[0].$message }}-->
                                    <template v-if="!v$.user.patronymic.minLength.$response">
                                      Поле отчество должен содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Поле отчество обязательное поле для заполнения
                                    </template>
                                </span>

                            </div>
                            <div class="form-group col-12">
                                <label for="job_title" class="font-weight-bold">Должность</label>
                                <input type="text" name="job_title" v-model="user.job_title" id="job_title"
                                       placeholder="Должность" class="form-control">
                            </div>

                            <div class="col-12 my-3 text-center">
                                <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Зарегистрироваться" }}
                                </button>
                            </div>
                            <div class="col-12 text-center">
                                <label>Already have an account? <router-link :to="{name:'login'}">Login Now!</router-link></label>
<!--                                <label>У вас уже есть аккаунт? <router-link :to="{name:'login'}">Войти сейчас!</router-link></label>-->
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
import { required, email, minLength, sameAs } from '@vuelidate/validators'
export default {

    name: "Register",
    data(){
        return {
            v$: useValidate(),
            user:{
                email:"",
                password:"",
                password_confirmation:"",
                firstname:"",
                name: "",
                patronymic: "",
                job_title: ""
            },
            validationErrors:{},
            processing:false
        }
    },
    validations() {
        return {
            user: {
                email: { required, email },
                password: { required, minLength: minLength(6) },
                password_confirmation: { required, sameAs: sameAs(this.user.password)},
                firstname: {required, minLength: minLength(5)},
                name: {required, minLength: minLength(3)},
                patronymic: {required, minLength: minLength(5)},
            }
        }
    },
    methods:{
        // ...mapActions({
        //     signIn:'auth/login'
        // }),
        async register(){
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                console.log(55555);
            } else {
                window.scrollTo(0,0);
                // this.$refs.registr_form.scrollTop = 0;
            }
            // this.processing = true
            // await axios.get('/sanctum/csrf-cookie')
            // await axios.post('/register',this.user).then(response=>{
            //     this.validationErrors = {}
            //     this.signIn()
            // }).catch(({response})=>{
            //     if(response.status===422){
            //         this.validationErrors = response.data.errors
            //     }else{
            //         this.validationErrors = {}
            //         alert(response.data.message)
            //     }
            // }).finally(()=>{
            //     this.processing = false
            // })
        }
    }
}
</script>

<style scoped>

</style>
