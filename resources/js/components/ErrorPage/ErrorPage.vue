<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8">
            <h1>
                <span v-if="err.status == 500">
                    {{err.status}} Server Error
                </span>
                <span v-else>
                    <template v-if="err.status == 404">
                        {{err.status}} File not found
                        <div v-if="!err.data" class="fs-4">{{app_url}}{{$route.path}}</div>
                    </template>
                    <template v-else>
                        Error: {{err.status}}
                    </template>
                </span>
            </h1>
            <div v-if="err.config">
                <div class="fs-4">{{app_url}}{{err.config.url}}</div>
                <div class="fs-3">{{err.data.message}}</div>
                <div>method: {{err.config.method}}</div>
            </div>

        </div>

    </div>
</div>
</template>

<script>
export default {
    name: "Error500",
    props: ['err'],
    computed: {
        app_url() {
            return import.meta.env.VITE_APP_URL;
        }
    },
    mounted() {
        this.app_url
    }
}
</script>

<style scoped>

</style>
