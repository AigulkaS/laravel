<template>
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Авторизация</h1>
                        <hr/>
                        <form action="javascript:void(0)" class="row" method="post">
                            <div class="col-12" v-if="validationErrors && Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <template v-if="typeof validationErrors == 'object'">
                                        <ul class="mb-0">
                                            <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <div>{{validationErrors}}</div>
                                    </template>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="email" class="font-weight-bold" :class="v$.auth.email.$error ? 'text-danger' : ''">
                                    Email<span class="text-danger">*</span>
                                </label>
                                <input type="text" v-model.lazy="v$.auth.email.$model" name="email" id="email"
                                       class="form-control" :class="v$.auth.email.$error ? 'border-danger' : ''">
                                <span v-if="v$.auth.email.$error" :class="v$.auth.email.$error ? 'text-danger' : ''">
<!--                                    {{ v$.auth.email.$errors[0].$message }}-->
                                    <template v-if="!v$.auth.email.email.$response">
                                      Неккоректный Email
                                    </template>
                                    <template v-else>
                                      Email обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password" class="font-weight-bold" :class="v$.auth.password.$error ? 'text-danger' : ''">
                                    Пароль<span class="text-danger">*</span>
                                </label>
                                <input type="password" v-model="v$.auth.password.$model" name="password" id="password"
                                       class="form-control" :class="v$.auth.password.$error ? 'border-danger' : ''">
                                <span v-if="v$.auth.password.$error" :class="v$.auth.password.$error ? 'text-danger' : ''">
<!--                                    {{ v$.auth.password.$errors[0].$message }}-->
                                      Пароль обязательное поле для заполнения
                                </span>
                            </div>
                            <div class="col-12 mb-2">
                                <button type="submit" :disabled="processing" @click="login" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Авторизация" }}
                                </button>
                            </div>
                            <div class="col-12 mb-2 text-center">
                                <router-link :to="{name: 'forgot-password'}">Забыли свой пароль</router-link>
                            </div>
                            <div class="col-12 text-center">
<!--                                <label>Don't have an account? <router-link :to="{name:'register'}">Register Now!</router-link></label>-->
                                <label>У вас нет аккаунта? <router-link :to="{name:'register'}">Зарегистрируйтесь сейчас!</router-link></label>
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
    name: "Login",
    data(){
        return {
            v$: useValidate(),
            auth:{
                email:"",
                password:""
            },
            validationErrors:{},
            processing:false
        }
    },
    computed: {
        auth_user() {
            console.log(7777)
            return localStorage.getItem('auth_user');
            // return this.$foo.value;
        },
    },
    validations() {
        return {
            auth: {
                email: { required, email },
                password: { required},
            }
        }
    },
    methods:{
        // ...mapActions({
        //     signIn:'auth/login'
        // }),
        async login(){
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                axios.get('/sanctum/csrf-cookie').then(response => {
                    console.log(789456);
                    axios.post('/api/login', {
                        email: this.auth.email,
                        password: this.auth.password,
                    })
                        .then(res => {
                            this.validationErrors = {};
                            console.log(res);
                            localStorage.setItem('access_token', `${res.data.token_type} ${res.data.access_token}`);
                            localStorage.setItem('auth_user', JSON.stringify(res.data.auth_user));
                            // console.log(this.$parent);
                            this.$router.push({name: "home"})
                        })
                        .catch(err => {
                            console.log(err.response);
                            console.log(err);
                            if(err.response.status == 422){
                                this.validationErrors = err.response.data.errors ? err.response.data.errors :err.response.data.message
                            }
                            else if (err.response.status == 500) {
                                //что то придумать с ошикой 404 и 500, записала в тетрадь
                            }
                            else{
                                this.validationErrors = {}
                                this.validationErrors = err.response.data.errors
                                alert(err.response.data.message)
                            }
                        })
                        .finally(() => {
                            this.processing = false
                        });
                });
            } else {
                window.scrollTo(0,0);
                // this.$refs.registr_form.scrollTop = 0;
            }
            // this.processing = true
            // await axios.get('/sanctum/csrf-cookie')
            // await axios.post('/login',this.auth).then(({data})=>{
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
        },
    }
}
</script>

<style scoped>

</style>
