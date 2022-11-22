<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                Главная страница
                <template v-if="auth_user">
<!--                    <div>{{auth_user.last_name}} 1111 {{auth_user.first_name}} 2222 {{auth_user.patronymic}}</div>-->
                    <div>{{auth_user}}</div>
                </template>
                <template v-else>
                    <div>Гость</div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Home",
    mounted() {
        this.getData();
    },
    computed: {
        auth_user() {
            return localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null
        }
    },
    methods: {
        getData() {
            axios.get('/api/get', {
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res)
            }).catch(err => {
                console.log(err.response)
            })
        }
    }
}
</script>

<style scoped>

</style>
