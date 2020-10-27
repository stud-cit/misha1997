<template>
    <div>
        <p class="step-subtitle">
            Крок 4 з 4. Вихідні дані
        </p>
        <articles v-if="publicationType == 'article'" :publicationData="publicationData"></articles>

        <book v-if="publicationType == 'book'" :publicationData="publicationData"></book>

        <monograph v-if="publicationType == 'monograph'" :publicationData="publicationData"></monograph>

        <book-part v-if="publicationType == 'book-part'" :publicationData="publicationData"></book-part>

        <thesis v-if="publicationType == 'thesis'" :publicationData="publicationData"></thesis>

        <patent v-if="publicationType == 'patent'" :publicationData="publicationData"></patent>

        <certificate v-if="publicationType == 'certificate'" :publicationData="publicationData"></certificate>

        <methodical v-if="publicationType == 'methodical'" :publicationData="publicationData"></methodical>

        <electronic v-if="publicationType == 'electronic'" :publicationData="publicationData"></electronic>
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

    // Монографія
    import Monograph from "./Step4_fields/Monograph";

    export default {
        name: "Step4",
        data() {
            return {}
        },
        props:{
            publicationType: String,
            publicationData: Object
        },

        components: {
            Articles,
            Book,
            BookPart,
            Thesis,
            Patent,
            Certificate,
            Methodical,
            Electronic,
            Monograph
        }
    }
</script>
