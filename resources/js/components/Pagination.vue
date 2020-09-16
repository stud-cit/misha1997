<template>

<!--    <b-pagination-->
<!--        v-model="currentPage"-->
<!--        :total-rows="rows"-->
<!--        :per-page="perPage"-->
<!--        first-text="<<"-->
<!--        prev-text="<"-->
<!--        next-text=">"-->
<!--        last-text=">>"-->
<!--        class="justify-content-center">-->
<!--    </b-pagination>-->


</template>
<script>
    export default {

        props:{
            perPage: {
                type: Number,
                default: 10
            },
            arr: Array

        },
        data() {
            return {
                currentPage: 1,
            }
        },
        methods: {

            changePage() {
                this.$emit('paginateArray',  );

            },


        },

    }
</script>
