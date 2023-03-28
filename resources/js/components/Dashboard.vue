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
                        <li class="nav-item" v-if="auth_user && auth_user.role_name == roles.admin">
                            <router-link :to="{name:'users'}" class="nav-link">
                                Админка
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="auth_user && [roles.admin, roles.surgeon, roles.cardiologist, roles.moderator].includes(auth_user.role_name)">
                            <router-link :to="{name:'hospitals_booking'}" class="nav-link">
                                Больницы
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="auth_user && [roles.admin, roles.moderator, roles.moderator_smp].includes(auth_user.role_name)">
                            <router-link :to="{name:'users_for_moderator'}" class="nav-link">
                                Пользователи
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="auth_user && [roles.admin, roles.moderator].includes(auth_user.role_name)">
                            <router-link :to="{name:'hospital_show_user'}" class="nav-link">
                                Больница
                            </router-link>
                        </li>
                        <li class="nav-item" v-if="auth_user && [roles.admin, roles.moderator_smp].includes(auth_user.role_name)">
                            <router-link :to="{name:'ambulance_station_show_user'}" class="nav-link">
                                СМП
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
                                    <router-link :to="{name:'profile'}" class="nav-link" aria-haspopup="true" aria-expanded="false">
                                        Профиль
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

        <div class="container my-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" v-for="(breadcrumb, i) in breadcrumbs" :key="i"
                     :class="breadcrumb.active || $route.name == 'home' ? 'active' : ''"
                    >
                        <template v-if="breadcrumb.active || $route.name == 'home'">
                            {{breadcrumb.label}}
                        </template>
                        <template v-else>
                            <router-link :to="{name: breadcrumb.name, params: breadcrumb.params}">
                                {{breadcrumb.label}}
                            </router-link>
                        </template>

                    </li>
                </ol>
            </nav>
        </div>

        <main class="mt-3">
            <router-view></router-view>
<!--            <router-view v-slot="{ Component }">-->
<!--                <component ref="qwe" :is="Component" />-->
<!--            </router-view>-->
        </main>
    </div>
</template>

<script>
import {roles} from "../consts";

export default {
    name: "Dashboard",
    data() {
        return {
            auth_user: false,
            roles: roles,
        }
    },
    mounted() {
        console.log(this.$route)
        // console.log(this.$refs.qwe.test())
        // console.log(this.$refs.qwe)
        this.auth_user = localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null;
        this.getData();
    },
    computed:{
      breadcrumbs() {
          let arr = [];
          this.$route.matched.forEach((el, index) => {
              if (el.meta.breadcrumb) {
                  arr.push({
                      label: el.meta.breadcrumb,
                      active: el.name == this.$route.name ? true : false,
                      name: el.name,
                      params: el.params
                  })
              }
          });
          return arr
      }
    },
    watch: {
        $route (to, from) {
            console.log(this.$route)
            this.auth_user = localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null;
        }
    },
    methods: {
        getData() {
            if (this.auth_user) {
                axios.get(`/api/users/${this.auth_user.id}`, {
                    headers: {Authorization: localStorage.getItem('access_token')}
                }).then(res => {
                    console.log(res);
                    localStorage.setItem('auth_user', JSON.stringify(res.data.data));
                    this.auth_user = localStorage.getItem('auth_user') ? JSON.parse(localStorage.getItem('auth_user')) : null;
                }).catch(err => {
                    this.errorsMessage(err);
                    // console.log(err.response);
                    // if (err.response.status == 401) {
                    //     localStorage.removeItem('access_token');
                    //     localStorage.removeItem('auth_user');
                    //     this.$router.push({name: "login"});
                    // }
                })
            }

        },
        logout() {
            axios.post('/api/logout', {},{
                headers: {Authorization: localStorage.getItem('access_token')}
            }).then(res => {
                console.log(res);
                localStorage.removeItem('access_token');
                localStorage.removeItem('auth_user');
                this.$router.push({name: 'login'});
            }).catch(err => {
                console.log(err.response);
                localStorage.removeItem('access_token');
                localStorage.removeItem('auth_user');
                this.$router.push({name: 'login'});
            })
        }
    }
}
</script>

<style scoped>

</style>
