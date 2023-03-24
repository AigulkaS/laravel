<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage" class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <h5 class="fw-bold">Добавить нового пользователя</h5>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <form @submit.prevent="register" class="row">

                        <div class="form-group row my-2 ">
                            <label class="col-sm-3 col-form-label fw-bold" for="email"
                                   :class="v$.user.email.$error ? 'text-danger' : ''">
                                Email<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="email" id="email" placeholder="Email"
                                       class="form-control "
                                       v-model.lazy="v$.user.email.$model"
                                       :class="v$.user.email.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.email.$error"
                                      :class="v$.user.email.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.email.email.$response">
                                      Неккоректный Email
                                    </template>
                                    <template v-else>
                                      Email обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label  class="col-sm-3 col-form-label fw-bold" for="password"
                                   :class="v$.user.password.$error ? 'text-danger' : ''">
                                Пароль<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input :type="inputType" name="password"
                                           v-model.lazy="v$.user.password.$model"
                                           id="password" placeholder="Пароль" class="form-control"
                                           :class="v$.user.password.$error ? 'border-danger' : ''"
                                           aria-describedby="basic-addon2">
                                    <span @click.prevent="updateTypeInput()"
                                          class="input-group-text cursor" id="basic-addon2" >
                                            <font-awesome-icon icon="fa-solid fa-eye"/>
                                    </span>
                                </div>
                                <span v-if="v$.user.password.$error"
                                  :class="v$.user.password.$error ? 'text-danger' : ''">
                                    <div v-if="!v$.user.password.pass_regex.$response">
                                      Пароль должен содержать не менее 1 латинской буквы алфавита и не менее 1 цифры.
                                    </div>
                                    <div v-if="!v$.user.password.minLength.$response">
                                      Пароль должен содержать не менее 6 символов.
                                    </div>
                                    <div v-else>
                                      Пароль обязательное поле для заполнения
                                    </div>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold" for="password_confirmation"
                                   :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
                                Повторите пароль<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input :type="inputType" name="password_confirmation"
                                           v-model.lazy="v$.user.password_confirmation.$model"
                                           id="password_confirmation" placeholder="Пароль"
                                           class="form-control"
                                           :class="v$.user.password_confirmation.$error
                                               ? 'border-danger' : ''">
                                    <span @click.prevent="updateTypeInput()" class="input-group-text cursor">
                                        <font-awesome-icon icon="fa-solid fa-eye"/>
                                    </span>
                                </div>

                                <span v-if="v$.user.password_confirmation.$error"
                                      :class="v$.user.password_confirmation.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.password_confirmation.sameAs.$response">
                                      Данное значение не совпадает с паролем
                                    </template>
                                    <template v-else>
                                      Подтверждение пароля обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>

                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold">Укажите место работы
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <div class="form-check" v-if="[role.admin, role.moderator].includes(auth_user.role_name)">
                                    <input class="form-check-input" :value="hospital_type.hospital"
                                           v-model="type" type="radio" name="radioHospital"
                                           id="radioHospital">
                                    <label class="form-check-label" for="radioHospital">
                                        Больница
                                    </label>
                                </div>
                                <div class="form-check" v-if="[role.admin, role.moderator_smp].includes(auth_user.role_name)">
                                    <input class="form-check-input" :value="hospital_type.smp"
                                           v-model="type" type="radio" name="radioSMP" id="radioSMP">
                                    <label class="form-check-label" for="radioSMP">
                                        Скорая медицинская помощь
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold"
                                   :class="v$.user.hospital_id.$error ? 'text-danger' : ''">
                                {{type == hospital_type.hospital ? 'Больница' : 'Скорая медицинская помощь'}}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <select class="form-control form-select" v-model="user.hospital_id"
                                        :disabled="auth_user.role_name == role.admin ? false : true" >
                                    <option v-for="hospital in type == hospital_type.hospital ? hospitals : smps" :key="hospital.id" :value="hospital.id">
                                        {{ hospital.short_name }}
                                    </option>
                                </select>
<!--                                <Multiselect-->
<!--                                    disabled-->
<!--                                    v-model="user.hospital_id"-->
<!--                                    :close-on-select="true"-->
<!--                                    :hide-selected="false"-->
<!--                                    label="full_name"-->
<!--                                    valueProp="id"-->
<!--                                    :options="type == hospital_type.hospital ? hospitals : smps"-->
<!--                                />-->
                                <span v-if="v$.user.hospital_id.$error"
                                      :class="v$.user.hospital_id.$error ? 'text-danger' : ''">
                                      {{type == hospital_type.hospital ? 'Больница' : 'Скорая медицинская помощь'}}
                                        обязательное поле для заполнения
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold"
                                   :class="v$.user.role_id.$error ? 'text-danger' : ''">
                                Роль<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <Multiselect
                                    v-model="user.role_id"
                                    :close-on-select="true"
                                    :hide-selected="false"
                                    label="label"
                                    valueProp="id"
                                    :options="roles ? roles : []"
                                />
                                <span v-if="v$.user.role_id.$error"
                                      :class="v$.user.role_id.$error ? 'text-danger' : ''">
                                      Роль обязательное поле для заполнения
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold" for="last_name"
                                   :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                Фамилия<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="last_name" v-model.lazy="v$.user.last_name.$model"
                                       id="last_name" placeholder="Фамилия" class="form-control"
                                       :class="v$.user.last_name.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.last_name.$error"
                                      :class="v$.user.last_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.last_name.minLength.$response">
                                      Поле фамилия должно содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Фамилия обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold" for="first_name"
                                   :class="v$.user.first_name.$error ? 'text-danger' : ''">
                                Имя<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="first_name" v-model="v$.user.first_name.$model"
                                       id="first_name" placeholder="Имя" class="form-control"
                                       :class="v$.user.first_name.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.first_name.$error"
                                      :class="v$.user.first_name.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.first_name.minLength.$response">
                                      Поле имя должен содержать не менее 3 символов.
                                    </template>
                                    <template v-else>
                                      Поле имя обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold" for="patronymic"
                                   :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                Отчество<span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="patronymic" v-model="v$.user.patronymic.$model"
                                       id="patronymic" placeholder="Отчество" class="form-control"
                                       :class="v$.user.patronymic.$error ? 'border-danger' : ''">
                                <span v-if="v$.user.patronymic.$error"
                                      :class="v$.user.patronymic.$error ? 'text-danger' : ''">
                                    <template v-if="!v$.user.patronymic.minLength.$response">
                                      Поле отчество должен содержать не менее 5 символов.
                                    </template>
                                    <template v-else>
                                      Поле отчество обязательное поле для заполнения
                                    </template>
                                </span>
                            </div>
                        </div>

                        <div class="form-group row my-2">
                            <label class="col-sm-3 col-form-label fw-bold" for="phone">Телефон</label>
                            <div class="col-sm-8">
                                <input type="text" name="phone" v-model="user.phone" id="phone"
                                       placeholder="Телефон" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 my-3 text-center">
                            <button type="submit" :disabled="processing" class="btn btn-primary btn-block">
                                {{ processing ? wait : "Сохранить" }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import useValidate from '@vuelidate/core';
import {email, helpers, minLength, required, sameAs} from '@vuelidate/validators';
import {hospital_type, roles, wait} from "../../../consts";
const pass_regex = helpers.regex(/^(?=.*[a-zA-Z])(?=.*[0-9])/)

export default {
    name: "Create",
    data() {
        return {
            v$: useValidate(),
            user:{
                email:"",
                password:"",
                password_confirmation:"",
                hospital_id: "",
                last_name:"",
                first_name: "",
                patronymic: "",
                phone: "",
                role_id: 0,
            },
            inputType: 'password',
            hospitals: [],
            smps: [],
            roles: null,
            processing:false,
            type: null,
            success: null,

            wait, hospital_type,
            role: roles,
        }
    },
    mounted() {
        this.type = this.auth_user.role_name == this.role.moderator_smp
            ? this.hospital_type.smp : this.hospital_type.hospital
        this.getData();
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    validations() {
        return {
            user: {
                email: { required, email },
                password: { required, minLength: minLength(6), pass_regex },
                password_confirmation: { required, sameAs: sameAs(this.user.password)},
                hospital_id: {required},
                role_id: {required},
                last_name: {required, minLength: minLength(5)},
                first_name: {required, minLength: minLength(3)},
                patronymic: {required, minLength: minLength(5)},
            }
        }
    },
    methods: {
        getData() {
            axios.get('/api/users/create', {
                headers: {Authorization: localStorage.getItem('access_token')},
            }).then(res => {
                console.log(res);
                this.hospitals = res.data.hospitals;
                this.smps = res.data.smps;
                this.roles = res.data.roles;
                if (this.auth_user.role_name !== this.role.admin) {
                    this.user.hospital_id = this.auth_user.hospital_id
                }
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        updateTypeInput() {
            this.inputType = this.inputType == 'text' ? 'password' : 'text';
        },
        register(){
            console.log(this.v$.user.password.pass_regex.$response)
            // if (this.type == this.hospital_type.smp) {
            //     this.user.role_id = this.roles.find(el => el.name == this.role.smp).id
            // }
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                this.errs = null;

                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/register', this.user)
                        .then(res => {
                            console.log(res);
                            this.$emit('add_data', res.data.auth_user);
                            this.success = 'Пользователь создан. Перенаправление...';
                            setTimeout(()=>{
                                this.$router.go(-1);
                            },3000)
                            // localStorage.setItem('access_token', `${res.data.token_type} ${res.data.access_token}`);
                            // localStorage.setItem('auth_user', JSON.stringify(res.data.auth_user));
                            // this.$router.push({name: "home"})
                        })
                        .catch(err => {
                            this.errorsMessage(err);
                        })
                        .finally(() => {
                            this.processing = false
                        });
                });
            } else {
                window.scrollTo(0,0);
            }
        }
    }
}
</script>
