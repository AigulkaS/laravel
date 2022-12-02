<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-3">
            <ul class="list-group">
                <li class="list-group-item active">
                    <router-link class="nav-link" :to="{name: 'users'}">Пользователи</router-link>
                </li>
                <li class="list-group-item">
                    <router-link class="nav-link" :to="{name: 'users'}">Роли</router-link>
                </li>
                <li class="list-group-item">
                    <router-link class="nav-link" :to="{name: 'users'}">Разрешения</router-link>
                </li>
                <li class="list-group-item">
                    <router-link class="nav-link" :to="{name: 'users'}">Больницы</router-link>
                </li>
                <li class="list-group-item">
                    <router-link class="nav-link" :to="{name: 'users'}">Диагнозы</router-link>
                </li>
            </ul>
        </div>
        <div class="col-9">
            <router-view></router-view>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "Admin"
}
</script>

<style scoped>

</style>
