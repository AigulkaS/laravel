<template>
<div>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link"
                   @click.prevent="$parent.getData(current_page-1)" aria-label="Previous"
                   :class="current_page == 1 ? 'page-item disabled' : ''">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <template v-if="last_page <= 6" v-for="page in last_page">
                <li class="page-item">
                    <a class="page-link" :class="current_page == page ? 'page-item disabled' : ''"
                       @click.prevent="$parent.getData(page)">{{page}}</a>
                </li>
            </template>
            <template v-else>
                <template v-if="current_page<3">
                    <li class="page-item">
                        <a class="page-link" :class="current_page == 1 ? 'page-item disabled' : ''"
                           @click.prevent="$parent.getData(1)">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :class="current_page == 2 ? 'page-item disabled' : ''"
                           @click.prevent="$parent.getData(2)">2</a>
                    </li>
                    <li v-if="current_page==2" class="page-item"><a class="page-link" @click.prevent="$parent.getData(3)">3</a></li>
                    <li class="page-item"><a class="page-link page-item disabled" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(last_page-1)">{{last_page-1}}</a></li>
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(last_page)">{{last_page}}</a></li>
                </template>
                <template v-else-if="current_page>=3 && current_page <= last_page-2 ">
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(1)">1</a></li>
                    <li class="page-item"><a class="page-link page-item disabled" href="#">...</a></li>
                    <li class="page-item active"><a class="page-link page-item disabled" @click.prevent="$parent.getData(current_page)">{{current_page}}</a></li>
                    <li class="page-item"><a class="page-link page-item disabled" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(last_page)">{{last_page}}</a></li>
                </template>
                <template v-else-if="current_page>last_page-2">
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(1)">1</a></li>
                    <li class="page-item"><a class="page-link" @click.prevent="$parent.getData(2)">2</a></li>
                    <li class="page-item"><a class="page-link page-item disabled" href="#">...</a></li>
                    <li v-if="current_page==last_page-1" class="page-item">
                        <a class="page-link" @click.prevent="$parent.getData(last_page-2)">{{last_page-2}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :class="current_page == last_page-1 ? 'page-item disabled' : ''"
                           @click.prevent="$parent.getData(last_page-1)">{{last_page-1}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :class="current_page == last_page ? 'page-item disabled' : ''"
                           @click.prevent="$parent.getData(last_page)">{{last_page}}</a>
                    </li>
                </template>

            </template>
            <li class="page-item">
                <a class="page-link" :class="current_page == last_page ? 'page-item disabled' : ''"
                   @click.prevent="$parent.getData(current_page+1)" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</template>

<script>
export default {
    name: "Paginate",
    props: ['current_page', 'last_page'],
    mounted() {
        console.log(this.$parent);
    }
}
</script>

<style scoped>

</style>
