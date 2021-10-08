<template>
  <div>
    <!--  -->
    <label class="mt-3">Назва конференції *</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.name_conference"
    >
    <div class="error" v-if="$v.publicationData.name_conference.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <label class="mt-3">Рік видання *</label>
    <select :disabled="publicationData.scopus_id" class="select" v-model="publicationData.year">
        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
    </select>
    <div class="error" v-if="$v.publicationData.year.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <label class="mt-3">Видавництво</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.editor_name"
    >
    <!--  -->
    <label class="mt-3">Том</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.number"
    >
    <!--  -->
    <b-row>
      <b-col cols="6">
        <label class="mt-3">Сторінки <span v-if="!publicationData.scopus_id && !publicationData.article_number">*</span></label>
        <input 
          type="text" 
          class="input" 
          :disabled="publicationData.scopus_id || (publicationData.article_number != '' && publicationData.article_number != null)"
          v-model="publicationData.pages"
          @input="$v.$touch()"
        > 
        <div class="error" v-if="$v.publicationData.pages.$error">
            Неправильно введені дані
        </div>
      </b-col>
      <b-col cols="6">
        <label class="mt-3">Номер статті <b-icon v-b-popover.hover.top="'Заповнюється у випадку, якщо стаття видавалася тільки в електронному журналі'" icon="question-circle-fill"></b-icon></label>
        <input 
          type="text" 
          class="input" 
          :disabled="publicationData.scopus_id"
          v-model="publicationData.article_number"
          @input="checkPages"
        > 
      </b-col>
    </b-row>
    <!--  -->
    <label class="mt-3">Країна видання *</label>
    <Country :data="publicationData"></Country>
    <div class="error" v-if="$v.publicationData.country.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <label class="mt-3">DOI</label>
    <input 
      type="text" 
      class="input" 
      placeholder="10.30888/2415-7538.2021-20-01-004" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.doi"
    > 
    <b-row>
      <b-col cols="12" class="pt-3 text-right pagin">
        <button class="button transparent mr-3" @click="prevStep">Назад</button>
        <button class="button blue" @click="nextStep">Продовжити</button>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import Country from "../../Country.vue";
import years from '../../../mixins/years';
import {required, requiredIf} from "vuelidate/lib/validators";
export default {
  mixins: [years],
  props: {
    publicationData: Object
  },
  components: {
    Country
  },
  validations: {
    publicationData: {
      name_conference: {
        required
      },
      year: {
        required
      },
      country: {
        required
      },
      pages: {
        isRequired() {
          return this.publicationData.scopus_id || this.publicationData.article_number || this.publicationData.pages != '' ? true : false
        },
        validFormat: val => val ? /^([^a-za-zа-яіїєё]+)$/.test(val) : true,
      },
    }
  },
  methods: {
    checkPages() {
      if(this.publicationData.article_number) {
        this.publicationData.pages = "";
      }
    },
    nextStep() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        swal.fire({
          icon: 'error',
          title: 'Не всі поля заповнено!'
        })
        return
      }
      this.$parent.$emit('getData', 4);
    },
    prevStep(){
      this.$parent.$emit('prevStep');
    }
  }
}
</script>