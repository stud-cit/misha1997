<template>
  <div class="step">
    <b-row>
      <b-col cols="6" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
        <label class="mt-3">SNIP журналу (БД Scopus)</label>
        <input type="text" class="input" v-model="publicationData.snip">
        <div class="error" v-if="$v.publicationData.snip.$error">
            Вводити лише числа не менше 0 (дроби через крапку)
        </div>
      </b-col>
      <b-col cols="6" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
        <label class="mt-3">Квартиль журналу (БД Scopus)</label>
          <select class="select" v-model="publicationData.quartil_scopus">
              <option value=""></option>
              <option v-for="n in 4" :key="n" :value="n">{{ n }}</option>
          </select>
      </b-col>
      <b-col cols="6" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
        <label class="mt-3">Імпакт-фактор (БД WoS)</label>
        <input type="text" class="input" v-model="publicationData.impact_factor">
        <div class="error" v-if="$v.publicationData.impact_factor.$error">
            Вводити лише числа не менше 0 (дроби через крапку)
        </div>
      </b-col>
      <b-col cols="6" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
        <label class="mt-3">Квартиль журналу (БД WoS)</label>
        <select class="select" v-model="publicationData.quartil_wos">
            <option value=""></option>
            <option v-for="n in 4" :key="n" :value="n">{{ n }}</option>
        </select>
      </b-col>
      <b-col cols="6">
        <label class="mt-3">У журналах (Nature / Science)</label>
        <select class="select" v-model="publicationData.nature_science" @change="setNatureScience">
            <option value=""></option>
            <option value="Nature">Nature</option>
            <option value="Science">Science</option>
        </select>
      </b-col>
      <b-col cols="12 mt-4">
        <b-form-checkbox value="1" unchecked-value="2" :disabled="userRole != 4" v-model="publicationData.nature_index">
          Обліковується рейтингом Nature Index
          <b-icon id="tooltip-target-nature-index" icon="question-circle-fill"></b-icon>
          <b-tooltip target="tooltip-target-nature-index" triggers="hover">
            Показник зазначається адміністратором
          </b-tooltip>
        </b-form-checkbox>
      </b-col>
      <b-col cols="12 mt-4" v-if="(publicationData.science_type_id == 2 || publicationData.science_type_id == 3) && (publicationData.publication_type_id == 1 || publicationData.publication_type_id == 3)">
        <b-form-checkbox v-model="publicationData.sub_db_scie">
          Підбаза SCIE (Science Citation Index Expanded)
        </b-form-checkbox>
      </b-col>
      <b-col cols="12 mt-4" v-if="(publicationData.science_type_id == 2 || publicationData.science_type_id == 3) && (publicationData.publication_type_id == 1 || publicationData.publication_type_id == 3)">
        <b-form-checkbox v-model="publicationData.sub_db_ssci">
          Підбаза SSCI (Social Science Citation Index)
        </b-form-checkbox>
      </b-col>
      <b-col cols="12 mt-4" v-if="$route.name == 'publications-edit' && userRole == 4">
        <b-form-checkbox v-model="publicationData.cited_international_patents">
          Процитована у міжнародних патентах
        </b-form-checkbox>
      </b-col>
    </b-row>
    <b-row v-if="$route.name == 'publications-edit' && userRole == 4">
      <b-col cols="12 mt-4" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
        <b-form-checkbox v-model="publicationData.db_scopus_percent">
          Увійшли до 10% за БД Scopus найбільш цитованих публікацій для своєї предметної галузі
        </b-form-checkbox>
      </b-col>
      <b-col cols="12 mt-4" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
        <b-form-checkbox v-model="publicationData.db_wos_percent">
          Увійшли до 1% за БД WoS найбільш цитованих публікацій для своєї предметної галузі
        </b-form-checkbox>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="12" class="pt-3 text-right pagin">
        <button class="button transparent mr-3" @click="prevStep">Назад</button>
        <button class="button blue" @click="nextStep">Продовжити</button>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default {
  data() {
    return {}
  },
  props: {
      publicationData: Object
  },
  computed: {
      userRole() {
          return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
      }
  },
  validations: {
      publicationData: {
          snip: {
              test(value) {
                  if(value) {
                      return value > 0 ? true : false;
                  } else {
                      return true;
                  }
              },
          },

          impact_factor: {
              test(value) {
                  if(value) {
                      return value > 0 ? true : false;
                  } else {
                      return true;
                  }
              },
          }
      }
  },
  methods: {
    setNatureScience() {
        this.publicationData.name_magazine = this.publicationData.nature_science;
        this.publicationData.default_name_magazine = this.publicationData.nature_science ? true : false;
    },
    nextStep() {
        this.$v.publicationData.$touch();
        if (this.$v.publicationData.$invalid) {
            return
        }
        this.$emit('getData', 3);
    },
    prevStep() {
        this.$emit('prevStep');
    },
  }
}
</script>