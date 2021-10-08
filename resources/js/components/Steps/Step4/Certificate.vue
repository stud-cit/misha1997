<template>
  <div>
    <!--  -->
    <label class="mt-3">№ свідоцтва/рішення *</label>
    <input 
      type="text" 
      class="input" 
      v-model="publicationData.number_certificate"
    >
    <div class="error" v-if="$v.publicationData.number_certificate.$error">
        Допускаються лише цифри
    </div>
    <!--  -->
    <label class="mt-3">Країна, де отримане свідоцтво *</label>
    <Country :data="publicationData"></Country>
    <div class="error" v-if="$v.publicationData.country.$error">
        Поле обов'язкове для заповнення
    </div>
    <!--  -->
    <b-form-group label="Власник майнових прав *" v-slot="{ ariaDescribedby }" class="mb-0 mt-3">
      <b-form-radio-group
        id="radio-group-2"
        v-model="applicant_id" 
        @change="clearApplicant()"
        :aria-describedby="ariaDescribedby"
        name="radio-sub-component"
      >
        <b-form-radio :value="true">СумДУ</b-form-radio>
        <b-form-radio :value="false">Не СумДУ</b-form-radio>
      </b-form-radio-group>
    </b-form-group>
    <!--  -->
    <div v-if="!applicant_id">
      <label class="mt-3">Вкажіть власника майнових прав *</label>
      <input 
        type="text" 
        class="input" 
        v-model="publicationData.applicant"
      >
    </div>
    <!--  -->
    <b-row>
      <b-col cols="6">
        <label class="mt-3">Дата подачі заявки *</label>
        <b-form-datepicker 
          placeholder="Оберіть дату" 
          hide-header 
          v-model="publicationData.date_application"
        ></b-form-datepicker>
        <div class="error" v-if="$v.publicationData.date_application.$error">
            Поле обов'язкове для заповнення
        </div>
      </b-col>
      <b-col cols="6">
        <label class="mt-3">Дата публікації про видачу свідоцтва/рішення *</label>
        <b-form-datepicker 
          placeholder="Оберіть дату" 
          hide-header 
          v-model="publicationData.date_publication"
        ></b-form-datepicker>
        <div class="error" v-if="$v.publicationData.date_publication.$error">
            Поле обов'язкове для заповнення
        </div>
      </b-col>
    </b-row>
    <!--  -->
    <b-form-checkbox class="mt-3" v-model="publicationData.commerc_university">
      Комерціалізовано університетом *
    </b-form-checkbox>
    <!--  -->
    <b-form-checkbox class="mt-3" v-model="publicationData.commerc_employees">
      Комерціалізовано штатними співробітниками університету *
    </b-form-checkbox>
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
  data() {
    return {
      applicant_id: true,
    }
  },
  components: {
      Country
  },
  validations: {
      publicationData: {
          number_certificate: {
              required,
              validFormat: val => /^\d{1,}$/.test(val),
          },
          date_publication: {
              required
          },
          date_application: {
              required
          },
          country: {
              required
          },
      },
  },
  created() {
      if(this.publicationData.applicant && this.publicationData.applicant != 'СумДУ') {
          this.applicant_id = false;
      }
  },
  methods: {
    clearApplicant() {
        this.publicationData.applicant = "";
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
        this.publicationData.year = this.publicationData.date_publication.slice(0,4);
        this.publicationData.applicant = this.applicant_id ? 'СумДУ' : this.publicationData.applicant;
        this.$parent.$emit('getData', 4);
    },
    prevStep() {
        this.$parent.$emit('prevStep');
    }
  }
}
</script>