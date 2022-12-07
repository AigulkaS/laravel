<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 col-lg-3">
            <ul class="list-group">
                <li class="list-group-item" :class="$route.name == 'users' ? 'active' : ''">
                    <router-link class="nav-link" :to="{name: 'users'}">Пользователи</router-link>
                </li>
                <li class="list-group-item" :class="$route.name == 'roles' ? 'active' : ''">
                    <router-link class="nav-link" :to="{name: 'roles'}">Роли</router-link>
                </li>
                <li class="list-group-item" :class="$route.name == 'permissions' ? 'active' : ''">
                    <router-link class="nav-link" :to="{name: 'permissions'}">Разрешения</router-link>
                </li>
                <li class="list-group-item" :class="$route.name == 'hospitals' ? 'active' : ''">
                    <router-link class="nav-link" :to="{name: 'hospitals'}">Больницы</router-link>
                </li>
                <li class="list-group-item" :class="$route.name == 'diseases' ? 'active' : ''">
                    <router-link class="nav-link" :to="{name: 'diseases'}">Диагнозы</router-link>
                </li>
            </ul>
        </div>
        <div class="col-lg-9 col-sm-12">
            <router-view></router-view>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "Admin",
    mounted() {
        console.log(this.$route);
    }
}
</script>

<style scoped>

</style>
