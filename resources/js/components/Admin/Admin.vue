<template>
        <div class="container" >
            <div class="row justify-content-center">
                <div v-if="getNameLink() > -1"  class="col-sm-12 col-lg-3">
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
                <div class="col-sm-12" :class="getNameLink() > -1 ? 'col-lg-9' : ''">
                    <router-view ref="zxc"></router-view>
<!--                    <router-view v-slot="{ Component }">-->
<!--                        <component ref="zxc" :is="Component" />-->
<!--                    </router-view>-->
                </div>
            </div>
        </div>

</template>

<script>
export default {
    name: "Admin",
    data() {
        return {
            links: ['users', 'roles', 'permissions', 'hospitals', 'diseases']
        }
    },
    mounted() {
        console.log(this.$route);
    },
    methods: {
        getNameLink() {
            return this.links.findIndex(el => el == this.$route.name)
        },
        // test() {
        //     console.log('test444')
        //     this.$refs.zxc.test2();
        // }
    }
}
</script>

<style scoped>

</style>
