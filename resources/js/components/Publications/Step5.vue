<template>
    <div>
        <p class="step-subtitle">
            Крок 5 з 5. Вихідні дані
        </p>

        <article-ukr v-if="articles.includes($parent.stepData.publicationType)"></article-ukr>

        <monograph v-if="monographs.includes($parent.stepData.publicationType)"></monograph>

        <thesis v-if="$parent.stepData.publicationType == 'Тези доповіді'"></thesis>

        <patent v-if="$parent.stepData.publicationType == 'Патент'"></patent>

        <certificate v-if="$parent.stepData.publicationType == 'Свідоцтво про реєстрації авторських прав на твір/рішення'"></certificate>

        <methodical v-if="$parent.stepData.publicationType == 'Методичні вказівки'"></methodical>

        <electronic v-if="$parent.stepData.publicationType == 'Електронні видання'"></electronic>






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
                articles: ['Стаття-доповідь у матеріалах наукових конференціях', 'Стаття у фахових виданнях України', 'Інші статті'],
                monographs: ['Монографія', 'Посібник', 'Книга'],
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
