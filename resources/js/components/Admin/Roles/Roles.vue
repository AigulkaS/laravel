<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="$route.name == 'roles'">
                <div class="row" v-if="roles">
                    <div class="d-flex my-3">
                        <div class="me-auto ">
                            <h4 class="my-3">Роли</h4>
                        </div>
                        <div class="align-self-center">
                            <router-link :to="{name: 'role_create'}" type="button" class="btn btn-primary">
                                <font-awesome-icon icon="fa-solid fa-plus" /> Добавить роль
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
                                <th scope="col">Разрешения</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(role, index) in roles">
                                <td scope="row" data-label="id">{{role.id}}</td>
                                <td data-label="Название">
                                    <router-link :to="{name: 'role_show', params: {id: role.id}}">
                                        {{role.name}}</router-link>
                                </td>
                                <td data-label="Разрешения">
                                    <ul v-for="permission in role.permissions" class="list-style-none my-1 px-1">
                                        <li>{{permission.label}}</li>
                                    </ul>
                                </td>
                                <td data-label="Редактировать">
                                    <router-link :to="{name: 'role_edit', params: {'id': role.id}}" tag="button" class="btn btn-warning" title="Редактировать">
                                        <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                                    </router-link>
                                </td>
                                <td data-label="Удалить">
                                    <button @click.prevent="deleteRole(role.id)" class="btn btn-danger" title="Удалить">
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
                ></router-view>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Roles",
    data() {
        return {
            roles: null,
            errors : null,
            success : null,
        }
    },
    mounted() {
        this.getRoles();
    },
    methods: {
        getRoles() {
            axios.get('/api/roles', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.roles = res.data.data;
            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteRole(role_id) {
            this.errs = null;
            axios.delete(`/api/roles/${role_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getRoles();
                this.success = 'Вы удалили роль';
                setTimeout(()=>{
                    this.success = null;
                },1500)
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        onAddChild (value) {
            this.roles.push(value);
        },
        onChangeChild (value) {
            let roleIndex = this.roles.findIndex(el => el.id == value.id);
            if (roleIndex != -1) {
                this.roles[roleIndex].name = value.name;
                this.roles[roleIndex].label = value.label;
                this.roles[roleIndex].permissions = value.permissions;
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
