<template>
    <div class="col-12" v-if="validationErrors"> <!--&& Object.keys(validationErrors).length > 0-->
        <div class="alert alert-danger">
            <template v-if="typeof validationErrors == 'object'">
                <ul class="mb-0">
                    <li v-for="(value, key) in validationErrors" :key="key">
                        {{ value[0] }}
                    </li>
                </ul>
            </template>
            <template v-else>
                <div>{{validationErrors}}</div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    name: "ErrorsValidation",
    props: ['validationErrors']
}
</script>

<style scoped>

</style>
