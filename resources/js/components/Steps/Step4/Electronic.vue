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
    <label class="mt-3">Кількість томів</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.number_volumes"
    >
    <!--  -->
    <label class="mt-3">Том</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.number"
    >
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
    <label class="mt-3">Назва редакції</label>
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
    <label class="mt-3">Електронна адреса (url) *</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.url"
    > 
    <div class="error" v-if="$v.publicationData.url.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <b-form-group label="Режим доступу *" v-slot="{ ariaDescribedby }" class="mb-0 mt-3">
      <b-form-radio-group
        id="radio-group-2"
        v-model="publicationData.access_mode"
        :aria-describedby="ariaDescribedby"
        name="radio-sub-component"
      >
        <b-form-radio value="1">Відкритий</b-form-radio>
        <b-form-radio value="0">Закритий</b-form-radio>
      </b-form-radio-group>
    </b-form-group>
    <div class="error" v-if="$v.publicationData.access_mode.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
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
          url: {
              required
          },
          year: {
              required
          },
          country: {
              required
          },
          access_mode: {
              required
          }
      }
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