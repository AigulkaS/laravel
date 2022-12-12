<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                Главная страница
                <template v-if="auth_user">
                    <div>{{auth_user}}</div>
                </template>
                <template v-else>
                    <div>Гость</div>
                </template>
            </div>
            <div>
                <HospitalsCard></HospitalsCard>
            </div>
        </div>
    </div>
</template>

<script>
import HospitalsCard from "./HospitalsCard.vue";
export default {
    name: "Home",
    components: {
        HospitalsCard
    },
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
