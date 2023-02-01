<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">

                    <div v-if="auth_user">
                        <div v-if="!auth_user.email_verified_at" class="alert alert-success">
                            На ваш почтовый адрес было направлено письмо для подтверждения Email.
                            <div class="fw-bold">
                                Только после подтверждения Email будут доступны все функции.
                            </div>
                            Для продолжения подвердите свою почту.
                            <div v-if="success" class="fw-bold">
                                {{success}}
                            </div>
                            <div class="fw-bolder">
                                <span>Если не поучили письмо - </span>
                                <button type="submit" :disabled="processing" class="btn btn-link"
                                        @click.prevent="resend()">
                                    {{ processing ? wait : "отправить повторно письмо для подтверждения email" }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div v-if="auth_user && auth_user.email_verified_at
                            && [roles.cardiologist, roles.surgeon].includes(auth_user.role_name)">
                            <HospitalBooking></HospitalBooking>
                        </div>
                        <div v-else>
                            <HospitalsCard></HospitalsCard>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import HospitalsCard from "./HospitalsCard.vue";
import HospitalBooking from "./HospitalBooking.vue";
import {roles, wait} from "../consts";

export default {
    name: "Home",
    components: {
        HospitalsCard,
        HospitalBooking
    },
    data() {
      return {
          success: null,
          processing:false,
          roles,
          wait,
      }
    },
    mounted() {
        // this.getData();
        this.successPage = true
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user')
                ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        getData() {
            axios.get('/api/get', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res)
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        resend(){
            this.errors = null;
            this.success = null;
            this.processing = true
            axios.post('/api/verify-resend', {id: this.auth_user.id})
                .then((res) =>{
                    console.log(res)
                    this.success = res.data.message;
                    setTimeout(()=>{
                        this.success = null;
                    },5000)
                })
                .catch((err) =>{
                    this.errorsMessage(err);
                })
                .finally(() => {
                    this.processing = false
                });
        },

    }
}
</script>

