<template>
    <div class="max-w-screen-md mx-auto text-gray-900">
        <div class="flex justify-center">
            <div class="flex-1">
                <div class="border w-auto">
                    <div  class="border p-4  font-semibold">Подтвердите Ваш электронный адрес!</div>

                    <div class="p-4 bg-white">

                        <div v-if="success" class="alert alert-success" role="alert">
                            {{success}}
                        </div>
                        <div v-if="errors" class="alert alert-danger" role="alert">
                            {{errors}}
                        </div>

                        <div class=" my-1 py-2 sm:w-8/12 md:w-10/12 md:p-4 w-full mx-auto flex justify-center items-center mt-3 sm:mt-0">
                            <div v-if="busy"  class="flex justify-center items-center p-2 px-6 rounded-sm text-white bg-blue-500 hover:bg-blue-600">
                                <circle-svg class="w-6 h-6" />
                            </div>
                            <button v-else @click="errors ? resend() : verify()"
                                    :class="'p-3 rounded-sm text-white' + (!errors ? ' btn btn-primary' : ' btn btn-danger')  " >
                                {{ errors ? 'Отправить письмо повторно?' :'Нажмите, чтобы подтвердить'}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CircleSvg from '../CircleSvg.vue';
export default {
    name: "VerifyEmail",
    components: {CircleSvg},
    props: ['id', 'hash'],
    data() {
        return {
            errors : null,
            success : null,
            busy : false ,
        }
    },

    methods : {
        verify(){
            this.busy = true ;
            this.errors = null
            this.success = null
            axios.post(`/api/verify-email/${this.id}/${this.hash}`)
                .then((res) =>{
                    // this.success = res.data.message ?  res.data.message  +  ' Redirecting ...' : ' Redirecting ...'
                    console.log(res)
                    this.success = res.data.message;
                    if (localStorage.getItem('auth_user')) {
                        let user = JSON.parse(localStorage.getItem('auth_user'))
                        axios.get(`/api/users/${user.id}`, {
                            headers: {Authorization: localStorage.getItem('access_token')}
                        }).then(res => {
                            console.log(res)
                            localStorage.setItem('auth_user', JSON.stringify(res.data.data));
                        }).catch(err => {
                            console.log(err.response)
                            this.logout();
                        })
                    }
                    setTimeout(()=>{
                        this.$router.push({name:'home'})
                    },3000)
                })
                .catch((err) =>{
                    console.log(err.response)
                    this.errors = err.response.data.message;
                })
                .finally(() => {
                    this.busy = false ;
                });

        },
        resend(){
            this.errors = null;
            this.success = null;
            axios.post('/api/verify-resend', {id: this.$route.params.id})
                .then((res) =>{
                    console.log(res)
                    this.success = res.data.message;
                    setTimeout(()=>{
                        this.$router.push({name:'home'})
                    },3000)
                })
                .catch((err) =>{
                    console.log(err.response)
                    this.errors = err.response.data.message;
                })
                .finally(() => {
                    this.busy = false ;
                })
        }
    },
}
</script>

<style scoped>

</style>
