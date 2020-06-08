<template>
    <div>
        <p class="step-subtitle">
            Крок 5 з 5. Вихідні дані
        </p>

        <article-ukr v-show="false"></article-ukr>

        <monograph></monograph>




    </div>
</template>

<script>
    import ArticleUkr from './Step5_fields/ArticleUkr';
    import Monograph from "./Step5_fields/Monograph";

    export default {
        name: "Step5",
        data() {
            return {
                stepData:{

                }

            }
        },
        components:{
            ArticleUkr,
            Monograph,
        },
        methods:{

        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>



</style>
