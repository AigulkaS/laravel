<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="max-w-screen-md mx-auto text-gray-900">
            <div class="flex justify-center">
                <div class="flex-1">
                    <div class="border w-auto">
                        <div  class="border p-4  font-semibold">Подтвердите Ваш электронный адрес!</div>

                        <div class="p-4 bg-white">

                            <div v-if="success" class="alert alert-success" role="alert">
                                {{success}}
                            </div>

                            <errors-validation :validationErrors="errs"/>

                            <button @click="errs ? resend() : verify()"
                                    :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? wait : errs ? 'Отправить письмо повторно?' :'Нажмите, чтобы подтвердить' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {wait} from "../../consts";

export default {
    name: "VerifyEmail",
    props: ['id', 'hash'],
    data() {
        return {
            success: null,
            processing: false,
            wait
        }
    },
    mounted() {
        this.successPage = true;
    },
    methods : {
        verify(){
            this.errs = null;
            this.success = null;
            this.processing = true;
            axios.post(`/api/verify-email/${this.id}/${this.hash}`)
                .then((res) =>{
                    console.log(res)
                    this.success = res.data.message;
                    this.getUser();
                }).catch((err) =>{
                    this.errorsMessage(err);
                }).finally(() => this.processing = false);

        },
        resend(){
            this.errs = null;
            this.success = null;
            axios.post('/api/verify-resend', {id: this.$route.params.id})
                .then((res) =>{
                    console.log(res)
                    this.success = res.data.message;
                    this.getUser();
                }).catch((err) =>{
                    this.errorsMessage(err);
                }).finally(() => this.processing = false)
        },
        getUser() {
            if (localStorage.getItem('auth_user')) {
                let user = JSON.parse(localStorage.getItem('auth_user'))
                axios.get(`/api/users/${user.id}`, {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res)
                    localStorage.setItem('auth_user', JSON.stringify(res.data.data));
                    setTimeout(()=>{
                        this.$router.push({name:'home'})
                    },3000)
                }).catch(() => {
                    this.logout();
                })
            } else {
                setTimeout(()=>{
                    this.$router.push({name:'login'})
                },3000)
            }
        }
    },
}
</script>
