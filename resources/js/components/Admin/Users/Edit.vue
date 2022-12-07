<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" v-if="user">
                <h5>Пользователь {{user.first_name}} {{user.last_name}} {{user.patronymic}}</h5>

                <div v-if="success" class="alert alert-success" role="alert">
                    {{success}}
                </div>
                <div class="col-12" v-if="errors && Object.keys(errors).length > 0">
                    <div class="alert alert-danger">
                        <template v-if="typeof errors == 'object'">
                            <ul class="mb-0">
                                <li v-for="(value, key) in errors" :key="key">{{ value[0] }}</li>
                            </ul>
                        </template>
                        <template v-else>
                            <div>{{errors}}</div>
                        </template>
                    </div>
                </div>

                <form @submit.prevent="update" class="row" >
                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Email</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" :value="user.email">
                        </div>
                    </div>
                    <div class="form-group row my-1">
                        <label for="last_name" class="col-sm-2 col-form-label fw-bold">
                            Фамилия<span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="last_name" v-model.lazy="v$.user.last_name.$model"
                                   id="last_name" placeholder="Фамилия" class="form-control"
                                   :class="v$.user.last_name.$error ? 'border-danger' : ''">
                            <span v-if="v$.user.last_name.$error" :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                <template v-if="!v$.user.last_name.minLength.$response">
                                  Поле фамилия должно содержать не менее 5 символов.
                                </template>
                                <template v-else>
                                  Фамилия обязательное поле для заполнения
                                </template>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row my-1">
                        <label for="first_name" class="col-sm-2 col-form-label fw-bold">
                            Имя<span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="first_name" id="first_name" v-model="v$.user.first_name.$model"
                                   placeholder="Имя" class="form-control"
                                   :class="v$.user.first_name.$error ? 'border-danger' : ''">
                            <span v-if="v$.user.first_name.$error" :class="v$.user.first_name.$error ? 'text-danger' : ''">
                            <template v-if="!v$.user.first_name.minLength.$response">
                              Поле имя должен содержать не менее 3 символов.
                            </template>
                            <template v-else>
                              Поле имя обязательное поле для заполнения
                            </template>
                        </span>
                        </div>
                    </div>
                    <div class="form-group row my-1">
                        <label for="patronymic" class="col-sm-2 col-form-label fw-bold">
                            Отчество<span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" name="patronymic" v-model="v$.user.patronymic.$model" id="patronymic"
                                   placeholder="Отчество" class="form-control"
                                   :class="v$.user.patronymic.$error ? 'border-danger' : ''">
                            <span v-if="v$.user.patronymic.$error" :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                            <template v-if="!v$.user.patronymic.minLength.$response">
                              Поле отчество должен содержать не менее 5 символов.
                            </template>
                            <template v-else>
                              Поле отчество обязательное поле для заполнения
                            </template>
                        </span>
                        </div>
                    </div>
                    <div class="form-group row my-1">
                        <label for="verify_email" class="col-sm-2 col-form-label fw-bold">Потверждение почты</label>
                        <div class="col-sm-10 form-check form-switch">
                            <div class='form-check form-switch mt-3'>
                                <input type="checkbox" v-model="verify_email" class="form-check-input" id="verify_email">
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
                                <input type="checkbox" v-model="user.push" class="form-check-input" id="push">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row my-1">
                        <label for="sms" class="col-sm-2 col-form-label fw-bold">SMS уведомления</label>
                        <div class="col-sm-10 form-check form-switch">
                            <div class='form-check form-switch mt-3'>
                                <input type="checkbox" v-model="user.sms" class="form-check-input" id="sms">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold">
                            Роль
                        </label>
                        <div class="col-sm-10">
                            <select class="form-control form-select" v-model="user.role_id">
<!--                                <option value selected>122</option>-->
                                <option v-for="role in roles" :key="role.id" :value="role.id">
                                    {{ role.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold">
                            Больница
                        </label>
                        <div class="col-sm-10">
                            <select class="form-control form-select" v-model="user.hospital_id">
                                <option v-for="hospital in hospitals" :key="hospital.id" :value="hospital.id">
                                    {{ hospital.short_name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label for="phone" class="col-sm-2 col-form-label fw-bold">Телефон</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" v-model="user.phone" id="phone"
                                   placeholder="Телефон" class="form-control">
                        </div>
                    </div>


                    <div class="col-12 my-3 text-center">
                        <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                            {{ processing ? "Please wait" : "Сохранить" }}
                        </button>
                    </div>
                </form>
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
