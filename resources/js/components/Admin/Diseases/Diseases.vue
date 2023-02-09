<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="$route.name == 'diseases'">
                <div class="row" v-if="diseases">
                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Диагнозы</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'disease_create'}" type="button" class="btn btn-primary">
                                <font-awesome-icon icon="fa-solid fa-plus" /> Добавить диагноз
                            </router-link>
                        </div>
                    </div>

                    <div v-if="success" class="alert alert-success" role="alert">
                        {{success}}
                    </div>

                    <errors-validation :validationErrors="errs"/>

                    <div class="col-12 table-mobile">
                        <table id="table" ref="table" class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Наименование</th>
                                <th scope="col">Код диагноза</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(disease, index) in diseases">
                                <td scope="row" data-label="id">{{disease.id}}</td>
                                <td data-label="Наименование">
                                    <router-link :to="{name: 'disease_show', params: {id: disease.id}}">
                                        {{disease.name}}</router-link>
                                </td>
                                <td data-label="Код диагноза">
                                    {{disease.code}}
                                </td>
                                <td data-label="Редактировать">
                                    <router-link :to="{name: 'disease_edit', params: {'id': disease.id}}"
                                                 tag="button" class="btn btn-warning" title="Редактировать">
                                        <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                                    </router-link>
                                </td>
                                <td data-label="Удалить">
                                    <button @click.prevent="deleteItem(disease.id)" class="btn btn-danger"
                                            title="Удалить">
                                        <font-awesome-icon icon="fa-solid fa-trash-can" :style="{ color: 'white' }"/>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div>
                <router-view @taked_data="onGetChild"
                             @change_data = "onChangeChild"
                ></router-view>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Diseases",
    data() {
        return {
            diseases: null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/diseases', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.diseases = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteItem(disease_id) {
            this.errs = null;
            axios.delete(`/api/diseases/${disease_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData();
                this.success = 'Запись успешно удалена.';
                setTimeout(()=>{
                    this.success = null;
                },1500)
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        onGetChild (value) {
            this.diseases.push(value);
        },
        onChangeChild (value) {
            console.log(value);
            let diseaseIndex = this.diseases.findIndex(el => el.id == value.id);
            if (diseaseIndex != -1) {
                this.diseases[diseaseIndex].name = value.name;
                this.diseases[diseaseIndex].code = value.code;
            }
        },
    }
}
</script>
