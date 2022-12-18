<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12" v-if="permission">
                <h5 class="fw-bold">Редактирвать разрешение - {{permission.name}} </h5>

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
                        <label class="col-sm-2 col-form-label fw-bold ">Разрешение</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.permission.name.$model"
                                   :class="v$.permission.name.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.permission.name.$error" :class="v$.permission.name.$error ? 'text-danger' : ''">
                                  Разрешение обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold">
                            Роли
                        </label>
                        <div class="col-sm-10">
                            <Multiselect
                                v-model="value"
                                mode="multiple"
                                :close-on-select="false"
                                :hide-selected="false"
                                label="name"
                                valueProp="id"
                                :options="roles"
                            >
                                <template v-slot:multiplelabel="{ values }">
                                    <div>
                                        <span v-for="value in values" class="badge bg-primary">
                                            {{ value.name }}</span>
                                    </div>
                                </template>
                            </Multiselect>
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
import useValidate from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';
export default {
    name: "Edit",
    props: ['id'],
    data() {
        return {
            v$: useValidate(),
            permission: null,
            roles: null,
            processing: false,
            errors : {},
            success : null,
            value: [],
        }
    },
    mounted() {
        this.getData();
    },
    validations() {
        return {
            permission: {
                name: {required}
            }
        }
    },
    methods: {
        getData() {
            axios.get(`/api/permissions/${this.id}/edit`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.permission = res.data.permission;
                this.roles = res.data.roles;
                if (this.permission.roles) {
                    this.value = this.permission.roles.map(function (obj) {
                        return obj.id;
                    });
                }
            }).catch(err => {
                console.log(err.response);
            });
        },
        update() {
            console.log(this.value)
            this.value.map(v => {
                console.log(v)
            })

            this.errors = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.patch(`/api/permissions/${this.id}`,
                    {name: this.permission.name, roles: Array.from(this.value)},
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.permission = res.data.data;
                    this.success = 'Данные успешно изменены. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'permissions'})
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
