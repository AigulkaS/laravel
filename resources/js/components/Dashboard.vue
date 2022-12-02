<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/" target="_blank">MEDIC</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link :to="{name:'home'}" class="nav-link">
                                Главная
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link :to="{name:'admin_page'}" class="nav-link">
                                Админка
                            </router-link>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <ul class="navbar-nav">
                            <template v-if="!auth_user">
                                <li class="nav-item">
                                    <router-link :to="{name:'login'}" class="nav-link" aria-haspopup="true" aria-expanded="false">
                                        Войти
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link :to="{name:'register'}" class="nav-link" aria-haspopup="true" aria-expanded="false">
                                        Регистрация
                                    </router-link>
                                </li>
                            </template>
                            <template v-else>
                                <li class="nav-item">
                                    <router-link :to="{name:'register'}" class="nav-link" aria-haspopup="true" aria-expanded="false">
                                        user.name
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" @click.prevent="logout">Выход</a>
                                </li>
                            </template>

<!--                            <li class="nav-item dropdown">-->
<!--                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                                    &lt;!&ndash;                                    {{ user.name }}&ndash;&gt;-->
<!--                                    user.name-->
<!--                                </a>-->
<!--                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">-->
<!--                                    <a class="dropdown-item" href="javascript:void(0)" @click="logout">Logout</a>-->
<!--                                </div>-->
<!--                            </li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <main class="mt-3">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    computed: {
      auth_user() {
          return localStorage.getItem('auth_user');
      }
    },
    methods: {
        logout() {
            axios.post('/api/logout', {},{
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                localStorage.removeItem('access_token');
                localStorage.removeItem('auth_user');
                this.$router.push({name: 'login'});
            }).catch(err => {
                console.log(err.reserved)
            })
        }
    }
}
</script>

<style scoped>

</style>
