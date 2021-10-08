<template>
  <div class="step">
    <articles v-if="publicationType == 'article'" :publicationData="publicationData"></articles>

    <book v-if="publicationType == 'book'" :publicationData="publicationData"></book>

    <monograph v-if="publicationType == 'monograph'" :publicationData="publicationData"></monograph>

    <monograph-part v-if="publicationType == 'monograph-part'" :publicationData="publicationData"></monograph-part>

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
import Articles from './Step4/Articles.vue';

// монографії посібники підручники
import Book from "./Step4/Book.vue";

// частина монографії книги
import BookPart from "./Step4/BookPart.vue";

// тези доповіді (abstracts)
import Thesis from "./Step4/Thesis.vue";

// патент
import Patent from "./Step4/Patent.vue";

// Свідоцтво про реєстрації авторських прав на твір/рішення
import Certificate from "./Step4/Certificate.vue";

// Методичні вказівки
import Methodical from "./Step4/Methodical.vue";

// Електронні видання
import Electronic from "./Step4/Electronic.vue";

// Монографія
import Monograph from "./Step4/Monograph.vue";

    // Розділ монографії
import MonographPart from "./Step4/MonographPart.vue";
export default {
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
      Monograph,
      MonographPart
  }
}
</script>