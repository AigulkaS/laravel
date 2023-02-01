<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="$route.name == 'permissions'">
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

                    <errors-validation :validationErrors="errs"/>

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
            <div>
                <router-view @add_data="onAddChild"
                             @change_data = "onChangeChild"
                >
                </router-view>
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
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteItem(permission_id) {
            this.errs = null;
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
                this.errorsMessage(err);
            })
        },
        onAddChild (value) {
            this.permissions.push(value);
        },
        onChangeChild (value) {
            console.log(value);
            let permissionIndex = this.permissions.findIndex(el => el.id == value.id);
            if (permissionIndex != -1) {
                this.permissions[permissionIndex].name = value.name;
                this.permissions[permissionIndex].label = value.label;
                this.permissions[permissionIndex].roles = value.roles;
            }
        },
    }
}
</script>

<style scoped>
.list-style-none {
    list-style-type: none;
}
</style>
