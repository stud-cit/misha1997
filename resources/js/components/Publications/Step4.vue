<template>
    <div>
        <p class="step-subtitle">
            Крок 4 з 4. Вихідні дані
        </p>

        <articles v-if="$parent.stepData.publication_type.type == 'article'"></articles>

        <book v-if="$parent.stepData.publication_type.type == 'book'"></book>

        <book-part v-if="$parent.stepData.publication_type.type == 'book-part'"></book-part>

        <thesis v-if="$parent.stepData.publication_type.type == 'thesis'"></thesis>

        <patent v-if="$parent.stepData.publication_type.type == 'patent'"></patent>

        <certificate v-if="$parent.stepData.publication_type.type == 'certificate'"></certificate>

        <methodical v-if="$parent.stepData.publication_type.type == 'methodical'"></methodical>

        <electronic v-if="$parent.stepData.publication_type.type == 'electronic'"></electronic>
    </div>
</template>

<script>
    // Стаття у фахових виданнях України, стаття-доповідь у матеріалах наукових конференціях, інші статті
    import Articles from './Step4_fields/Articles';

    // монографії посібники підручники
    import Book from "./Step4_fields/Book";

    // частина монографії книги
    import BookPart from "./Step4_fields/BookPart";

    // тези доповіді (abstracts)
    import Thesis from "./Step4_fields/Thesis";

    // патент
    import Patent from "./Step4_fields/Patent";

    // Свідоцтво про реєстрації авторських прав на твір/рішення
    import Certificate from "./Step4_fields/Certificate";

    // Методичні вказівки
    import Methodical from "./Step4_fields/Methodical";

    // Електронні видання
    import Electronic from "./Step4_fields/Electronic";

    export default {
        name: "Step5",
        data() {
            return {}
        },
        components: {
            Articles,
            Book,
            BookPart,
            Thesis,
            Patent,
            Certificate,
            Methodical,
            Electronic
        }
    }
</script>
