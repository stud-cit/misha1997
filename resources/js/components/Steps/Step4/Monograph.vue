<template>
  <div>
    <!--  -->
    <label class="mt-3">Рік видання *</label>
    <select :disabled="publicationData.scopus_id" class="select" v-model="publicationData.year">
        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
    </select>
    <div class="error" v-if="$v.publicationData.year.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <label class="mt-3">За редакцією (у родовому відмінку)</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.by_editing"
    >
    <!--  -->
    <label class="mt-3">Країна видання *</label>
    <Country :data="publicationData"></Country>
    <div class="error" v-if="$v.publicationData.country.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <label class="mt-3">Місто видання</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.city"
    >
    <!--  -->
    <label class="mt-3">Назва видавництва</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.editor_name"
    >
    <!--  -->
    <label class="mt-3">Кількість сторінок *</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.pages"
    > 
    <div class="error" v-if="$v.publicationData.pages.$error">
        Неправильно введені дані
    </div>
    <!--  -->
    <label class="mt-3">Кількість томів</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id"
      v-model="publicationData.number_volumes"
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
          pages: {
              required,
              validFormat: val => /^([^a-za-zа-яіїєё]+)$/.test(val), 
          },
          year: {
              required
          },
          country: {
              required
          },
      },
  },
  methods:{
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
      prevStep() {
          this.$parent.$emit('prevStep');
      }
  }
}
</script>