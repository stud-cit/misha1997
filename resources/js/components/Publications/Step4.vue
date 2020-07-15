<template>
    <div>
        <p class="step-subtitle">
            Крок 4 з 4. Вихідні дані
        </p>

        <article-ukr v-if="$parent.stepData.type.key == 'articles'"></article-ukr>

        <book v-if="$parent.stepData.type.key == 'monographs' || $parent.stepData.type.key == 'textbooks' || $parent.stepData.type.key == 'manuals'"></book>

        <book-part v-if="$parent.stepData.type.key == 'monographs' || $parent.stepData.type.key == 'textbooks'"></book-part>

        <thesis v-if="$parent.stepData.type.key == 'abstracts'"></thesis>

        <patent v-if="$parent.stepData.type.key == 'patents'"></patent>

        <certificate v-if="$parent.stepData.type.key == 'certificates'"></certificate>

        <methodical v-if="$parent.stepData.type.key == 'methodical_instructions'"></methodical>

        <electronic v-if="$parent.stepData.type.key == 'electronic_publications'"></electronic>
    </div>
</template>

<script>
    // Стаття у фахових виданнях України, стаття-доповідь у матеріалах наукових конференціях, інші статті
    import ArticleUkr from './Step5_fields/ArticleUkr';

    // монографії посібники підручники
    import Book from "./Step5_fields/Book";

    // частина монографії книги
    import BookPart from "./Step5_fields/BookPart";

    import Thesis from "./Step5_fields/Thesis";
    import Patent from "./Step5_fields/Patent";
    import Certificate from "./Step5_fields/Certificate";
    import Methodical from "./Step5_fields/Methodical";
    import Electronic from "./Step5_fields/Electronic";

    export default {
        name: "Step5",
        data() {
            return {
                stepData: {

                }

            }
        },
        components:{
            ArticleUkr,
            Book,
            BookPart,
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
