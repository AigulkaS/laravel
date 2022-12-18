<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h5 class="fw-bold">Добавить новую роль</h5>

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

                <form @submit.prevent="store" class="row" >
                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Роль</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.role.name.$model"
                                   :class="v$.role.name.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.role.name.$error" :class="v$.role.name.$error ? 'text-danger' : ''">
                                  Роль обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold ">Наименование</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control"
                                   v-model.lazy="v$.role.label.$model"
                                   :class="v$.role.label.$error ? 'border-danger' : ''"
                            >
                            <span v-if="v$.role.label.$error" :class="v$.role.label.$error ? 'text-danger' : ''">
                                  Наименование обязательное поле для заполнения
                            </span>
                        </div>
                    </div>

                    <div class="form-group row my-1">
                        <label class="col-sm-2 col-form-label fw-bold">
                            Разрешения
                        </label>
                        <div class="col-sm-10" v-if="permissions">
                            <Multiselect
                                v-model="value"
                                mode="multiple"
                                :close-on-select="false"
                                :hide-selected="false"
                                label="name"
                                valueProp="id"
                                :options="permissions"
                            >
                                <template v-slot:multiplelabel="{ values }">
                                    <div>
                                        <span v-for="value in values" class="badge bg-primary">
                                            {{ value.name }}545454545465456</span>
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
import { required } from '@vuelidate/validators';
// import Multiselect from '@vueform/multiselect'
export default {
    name: "Create",
    // components: {
    //     Multiselect,
    // },
    data() {
        return {
            v$: useValidate(),
            role: {name: null, label: null},
            permissions: null,
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
            role: {
                name: {required},
                label: {required},
            }
        }
    },
    methods: {
        getData() {
            axios.get('/api/roles/create', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.permissions = res.data.permissions;
            }).catch(err => {
                console.log(err.response)
            })
        },
        store() {
            // console.log(this.value)
            // this.value.map(v => {
            //     console.log(v)
            // })
            this.errors = null
            this.success = null;
            this.v$.$validate() // checks all inputs
            if (!this.v$.$error) {
                this.processing = true;
                axios.post(`/api/roles`,
                    {name: this.role.name, label: this.role.label, permissions: Array.from(this.value)},
                    {headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    this.role = res.data.data;
                    this.success = 'Роль успешно добавлена. Перенаправление...';
                    setTimeout(()=>{
                        this.$router.push({name:'roles'})
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
<style>
.multiselect-option.is-selected {
    background: #0d6efd !important;
}
</style>
<!--<style src="@vueform/multiselect/themes/default.css"></style>-->

<style scoped>

</style>
