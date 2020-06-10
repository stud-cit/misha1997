<template>
    <div>
        <p class="step-subtitle">
            Крок 5 з 5. Вихідні дані
        </p>

        <article-ukr v-show="false"></article-ukr>

        <monograph v-show="false"></monograph>

        <thesis></thesis>






    </div>
</template>

<script>
    // Стаття у фахових виданнях України, інші статті
    import ArticleUkr from './Step5_fields/ArticleUkr';

    // монографії посібники підручники
    import Monograph from "./Step5_fields/Monograph";

    import Thesis from "./Step5_fields/Thesis";

    import Patent from "./Step5_fields/Patent";
    import Certificate from "./Step5_fields/Certificate";
    import Methodical from "./Step5_fields/Methodical";
    import Electronic from "./Step5_fields/Electronic";

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
            Thesis,
            Patent,
            Certificate,
            Methodical,
            Electronic

        },
        methods:{

        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>



</style>
