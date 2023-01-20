<template>
    <div>
        <div v-if="$route.name == 'users'">
            <div class="row" v-if="users">
                <div class="col-12">
                    <h4 class="mt-2">Пользователи</h4>
                </div>
                <div class="col-12 table-mobile">
                    <table id="table" ref="table" class="table table-striped"
                    >
                        <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Email</th>
                            <th scope="col">Роль</th>
                            <th scope="col">Фамилия</th>
                            <th scope="col">Имя</th>
                            <th scope="col">Отчество</th>
                            <th scope="col">Больница</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(user, index) in users">
                            <td scope="row" data-label="id">{{user.id}}</td>
                            <td data-label="Email">
                                <router-link :to="{name: 'user_show', params: {id: user.id}}">
                                    {{user.email}}</router-link>
                            </td>
                            <td data-label="Роль">{{user.role_name}}</td>
                            <td data-label="Фамилия">{{user.last_name}}</td>
                            <td data-label="Имя">{{user.first_name}}</td>
                            <td data-label="Отчество">{{user.patronymic}}</td>
                            <td data-label="Больница">{{user.hospital_name}}</td>
                            <td data-label="Изменить">
                                <router-link :to="{name: 'user_edit', params: {'id': user.id}}" tag="button" class="btn btn-warning" title="Изменить">
                                    <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                                </router-link>
                            </td>
                            <td data-label="Удалить">
                                <button @click.prevent="deleteUser(user.id)" class="btn btn-danger" title="Удалить">
                                    <font-awesome-icon icon="fa-solid fa-trash-can" :style="{ color: 'white' }" />
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <paginate :current_page="current_page" :last_page="last_page"></paginate>

                </div>
            </div>
        </div>
        <div>
            <router-view></router-view>
        </div>
    </div>


</template>

<script>
export default {
    name: "Users",
    data() {
        return {
            users: null,
            current_page: 1,
            last_page: 1,
            perPage: 2,
            total: 100,
        }
    },
    mounted() {
        this.getData(this.current_page);
    },
    methods: {
        getData(page) {
            axios.get(`/api/users?page=${page}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.users = res.data.data;
                this.current_page = res.data.meta.current_page;
                this.last_page = res.data.meta.last_page;

            }).catch(err => {
                console.log(err.response);
            })
        },
        deleteUser(user_id) {
            axios.delete(`/api/users/${user_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData(this.current_page);
            }).catch(err => {
                console.log(err.response)
            })
        },
    }
}
</script>

<style scoped>

</style>
