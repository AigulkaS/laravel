<template>
    <div class="container">
        <div class="row" v-if="users">
            <div class="col-12">
                <h4>Пользователи</h4>
            </div>
            <div class="col-12">
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
                        <td data-label="Роль">{{user.role_id}}</td>
                        <td data-label="Фамилия">{{user.last_name}}</td>
                        <td data-label="Имя">{{user.first_name}}</td>
                        <td data-label="Отчество">{{user.patronymic}}</td>
                        <td data-label="Больница">{{user.hospital_id}}</td>
                        <td data-label=" ">
                            <router-link :to="{name: 'user_edit', params: {'id': user.id}}" tag="button" class="btn btn-warning" title="Изменить">
                                <font-awesome-icon icon="fa-solid fa-pencil" :style="{ color: 'black' }" />
                            </router-link>
                        </td>
                        <td data-label=" ">
                            <button @click.prevent="deleteUser(user.id)" class="btn btn-danger" title="Удалить">
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
    name: "Users",
    data() {
        return {
            users: null
        }
    },
    mounted() {
        this.getUsers();
    },
    methods: {
        getUsers() {
            axios.get('/api/users', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.users = res.data.data;

            }).catch(err => {
                console.log(err.response);
            })
        },
        deleteUser(user_id) {
            axios.delete(`/api/users/${user_id}`, {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                this.getUsers();
            }).catch(err => {
                console.log(err.response)
            })
        }
    }
}
</script>

<style scoped>
/*table {*/
/*    border: 1px solid #ccc;*/
/*    border-collapse: collapse;*/
/*    margin: 0;*/
/*    padding: 0;*/
/*    width: 100%;*/
/*    table-layout: fixed;*/
/*}*/

/*table caption {*/
/*    font-size: 1.5em;*/
/*    margin: .5em 0 .75em;*/
/*}*/

/*table tr {*/
/*    background-color: #f8f8f8;*/
/*    border: 1px solid #ddd;*/
/*    padding: .35em;*/
/*}*/

/*table th,*/
/*table td {*/
/*    padding: .625em;*/
/*    text-align: center;*/
/*}*/

/*table th {*/
/*    font-size: .85em;*/
/*    letter-spacing: .1em;*/
/*    text-transform: uppercase;*/
/*}*/

@media screen and (max-width: 767.98px) {
    table {
        /*border: 0;*/
    }

    table caption {
        /*font-size: 1.3em;*/
    }

    table thead {
        border: none;
        clip: rect(0 0 0 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px;
    }

    table tr {
        border-bottom: 3px solid #ddd;
        display: block;
        margin-bottom: .625em;
    }

    table td {
        border-bottom: 1px solid #ddd;
        display: block;
        /*font-size: .8em;*/
        text-align: right;
    }

    table td::before {
        content: attr(data-label);
        float: left;
        font-weight: bold;
        /*text-transform: uppercase;*/
    }

    table td:last-child {
        border-bottom: 0;
    }
}

</style>
