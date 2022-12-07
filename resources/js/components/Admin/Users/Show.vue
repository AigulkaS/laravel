<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" v-if="user">
                <h5>Пользователь {{user.first_name}} {{user.last_name}} {{user.patronymic}}</h5>

                <div class="row">
                    <div class="col-sm-2 fw-bold ">Email</div>
                    <div class="col-sm-10">{{user.email}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Фамилия</div>
                    <div class="col-sm-10">{{user.last_name}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Имя</div>
                    <div class="col-sm-10">{{user.first_name}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Отчество</div>
                    <div class="col-sm-10">{{user.patronymic}}</div>
                </div>

                <div class="form-group row my-1">
                    <label for="verify_email" class="col-sm-2 col-form-label fw-bold">Потверждение почты</label>
                    <div class="col-sm-10 form-check form-switch">
                        <div class='form-check form-switch mt-3'>
                            <input type="checkbox" v-model="verify_email" disabled class="form-check-input" id="verify_email">
                            <label class="form-check-label" for="verify_email">
                                {{ verify_email ? 'Email подтвержден' : 'Email НЕ подтвержден'}}
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row my-1">
                    <label for="push" class="col-sm-2 col-form-label fw-bold">PUSH уведомления</label>
                    <div class="col-sm-10 form-check form-switch">
                        <div class='form-check form-switch mt-3'>
                            <input type="checkbox" v-model="user.push" disabled class="form-check-input" id="push">
                        </div>
                    </div>
                </div>
                <div class="form-group row my-1">
                    <label for="sms" class="col-sm-2 col-form-label fw-bold">SMS уведомления</label>
                    <div class="col-sm-10 form-check form-switch">
                        <div class='form-check form-switch mt-3'>
                            <input type="checkbox" v-model="user.sms" disabled class="form-check-input" id="sms">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Роль</div>
                    <div class="col-sm-10">{{user.role_id}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Больница</div>
                    <div class="col-sm-10">{{user.hospital_id}}</div>
                </div>

                <div class="row">
                    <div class="col-sm-2 fw-bold">Телефон</div>
                    <div class="col-sm-10">{{user.phone}}</div>
                </div>

                <div class="col-12 my-3">
                    <button @click.prevent="$router.go(-1)"  class="btn btn-primary btn-block">
                        Назад
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useValidate from '@vuelidate/core'
import { required, email, minLength, sameAs } from '@vuelidate/validators'
export default {
    name: "Edit",
    props: ['id'],
    data() {
        return {
            v$: useValidate(),
            user: null,
            roles: null,
            hospitals: null,
            processing: false,
            verify_email: false,
            errors : {},
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            user: {
                last_name: {required, minLength: minLength(5)},
                first_name: {required, minLength: minLength(3)},
                patronymic: {required, minLength: minLength(5)},
            }
        }
    },
    methods: {
        getData() {
            axios.get(`/api/users/${this.id}/edit`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.user = res.data.user;
                this.roles = res.data.roles;
                this.hospitals = res.data.hospitals;
            }).catch(err => {
                console.log(err.response);
            });
        },
        update() {
            this.errors = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.patch(`/api/users/${this.id}`, this.user, {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.user = res.data.data;
                    this.success = 'Данные успешно изменены. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'users'})
                    },3000)
                }).catch(err => {
                    console.log(err.response);
                    if(err.response.status == 422){
                        this.errors = err.response.data.errors
                    }
                    else if (err.response.status == 500) {
                        //что то придумать с ошикой 404 и 500, записала в тетрадь
                        this.errors = {}
                        this.errors = err.response.data.message
                    }
                    else{
                        this.errors = {}
                        this.errors = err.response.data.errors
                    }

                }).finally(() => {
                    this.processing = false;
                })
            } else {
                window.scrollTo(0,0);
            }

        }
    }
}
</script>

<style scoped>

</style>
