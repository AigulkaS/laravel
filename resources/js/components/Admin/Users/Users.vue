<template>
    <div>
        <error-page v-if="errPage" :err="errs"></error-page>

        <div v-else-if="successPage">
            <div v-if="['users', 'users_for_moderator'].includes($route.name)"
                 :class="$route.name == 'users_for_moderator' ? 'container' : ''">
                <div class="row" v-if="users">

                    <errors-validation :validationErrors="errs"/>

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
                                <th scope="col">Больница/СМП</th>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user, index) in users">
                                <td scope="row" data-label="id">{{user.id}}</td>
                                <td data-label="Email">
                                    <router-link :to="{name: $route.name == 'users' ? 'user_show' : 'user_show_for_moderator',
                                     params: {id: user.id}}">
                                        {{user.email}}</router-link>
                                </td>
                                <td data-label="Роль">{{user.role_name}}</td>
                                <td data-label="Фамилия">{{user.last_name}}</td>
                                <td data-label="Имя">{{user.first_name}}</td>
                                <td data-label="Отчество">{{user.patronymic}}</td>
                                <td data-label="Больница/СМП">{{user.hospital_name}}</td>
                                <td data-label="Изменить">
                                    <router-link :to="{name: $route.name == 'users' ? 'user_edit' : 'user_edit_for_moderator',
                                     params: {'id': user.id}}" tag="button" class="btn btn-warning" title="Изменить">
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
                <router-view ref="asd" @change_data = "onChangeChild"></router-view>
                <!--            <router-view v-slot="{ Component }">-->
                <!--                <component ref="asd" :is="Component" />-->
                <!--            </router-view>-->
            </div>
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
        // console.log(this.$refs.asd);
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user')
                ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        // test2() {
        //     console.log('test2')
        //     // console.log(this.$refs.asd);
        // },
        getData(page) {
            let params = {page: page};
            if (this.$route.name == 'users_for_moderator') {
                params.hospital_id = this.auth_user.hospital_id
            }
            axios.get(`/api/users`, {
                headers: {Authorization: localStorage.getItem('access_token')},
                params: params
            }).then(res => {
                console.log(res);
                this.users = res.data.data;
                this.current_page = res.data.meta.current_page;
                this.last_page = res.data.meta.last_page;

            }).catch(err => {
                this.errorsMessage(err);
            }).finally(() => this.successPage = true)
        },
        deleteUser(user_id) {
            this.errs = null;
            axios.delete(`/api/users/${user_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getData(this.current_page);
            }).catch(err => {
                this.errorsMessage(err);
            })
        },
        onChangeChild (value) {
            let userIndex = this.users.findIndex(el => el.id == value.id);
            if (userIndex != -1) {
                this.users[userIndex].last_name = value.last_name;
                this.users[userIndex].first_name = value.first_name;
                this.users[userIndex].patronymic = value.patronymic;
                this.users[userIndex].email_verified_at = value.email_verified_at;
                this.users[userIndex].push = value.push;
                this.users[userIndex].sms = value.sms;
                this.users[userIndex].role_id = value.role_id;
                this.users[userIndex].role_name = value.role_name;
                this.users[userIndex].hospital_name = value.hospital_name;
                this.users[userIndex].hospital_id = value.hospital_id;
                this.users[userIndex].phone = value.phone;
            }
        },
    }
}
</script>

<style scoped>

</style>
