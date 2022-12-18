<template>
    <div class="container">
        <div class="row" v-if="permissions">
            <div class="d-flex my-3">
                <div class="me-auto ">
                    <h4 class="my-3">Разрешения</h4>
                </div>
                <div class="align-self-center">
                    <router-link :to="{name: 'permission_create'}" type="button" class="btn btn-primary">
                        <font-awesome-icon icon="fa-solid fa-plus" /> Добавить разрешение
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
                        <th scope="col">Роли</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(permission, index) in permissions">
                        <td scope="row" data-label="id">{{permission.id}}</td>
                        <td data-label="Наименование">
                            <router-link :to="{name: 'permission_show', params: {id: permission.id}}">
                                {{permission.label}}</router-link>
                        </td>
                        <td data-label="Разрешения">
                            <ul class="list-style-none my-1 px-1" v-for="role in permission.roles">
                                <li class="pl-1">{{ role.name }}</li>
                            </ul>
                        </td>
                        <td data-label="Редактировать">
                            <router-link :to="{name: 'permission_edit', params: {'id': permission.id}}" tag="button" class="btn btn-warning" title="Редактировать">
                                <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                            </router-link>
                        </td>
                        <td data-label="Удалить">
                            <button @click.prevent="deleteItem(permission.id)" class="btn btn-danger" title="Удалить">
                                <font-awesome-icon icon="fa-solid fa-trash-can" :style="{ color: 'white' }" />
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
    name: "Permissions",
    data() {
        return {
            permissions: null,
            errors : null,
            success : null,
        }
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            axios.get('/api/permissions', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.permissions = res.data.data;
            }).catch(err => {
                console.log(err.response)
            })
        },
        deleteItem(permission_id) {
            axios.delete(`/api/permissions/${permission_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData();
                this.success = 'Вы удалили разрешение';
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
.list-style-none {
    list-style-type: none;
}
</style>
