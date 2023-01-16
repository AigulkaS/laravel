<template>
    <div class="container">
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
            <div v-if="errors" class="alert alert-danger" role="alert">
                {{errors}}
            </div>

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
</template>

<script>
export default {
    name: "Diseases",
    data() {
        return {
            diseases: null,
            errors : null,
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
                console.log(err.response)
            })
        },
        deleteItem(disease_id) {
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
                console.log(err.response);
                this.errors = err.response.data.message
            })
        }
    }
}
</script>

<style scoped>

</style>
