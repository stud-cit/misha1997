<template>
  <div class="step">
    <b-form-group 
      label="Власник публікації *" 
      v-slot="{ ariaDescribedby }" 
      v-show="userRole != 1 && $route.name != 'edit'"
      class="mb-0"
    >
      <b-form-radio-group 
        v-model="publicationData.whose_publication" 
        :aria-describedby="ariaDescribedby" 
        @change="whosePublication"
      >
        <b-form-radio value="my">
          Власна публікація
        </b-form-radio>
        <b-form-radio value="another">
          Публікація <span v-if="userRole == 2">кафедри</span><span v-if="userRole == 3">факультету</span><span v-if="userRole == 4">університету</span>
        </b-form-radio>
      </b-form-radio-group>
    </b-form-group>
    <!--  -->
    <label class="mt-3">Назва публікації *</label>
    <input 
      type="text" 
      class="input" 
      :disabled="publicationData.scopus_id" 
      v-model="publicationData.title"
    >
    <div class="error" v-if="$v.publicationData.title.$error">
        Поле обов'язкове для заповнення
    </div>
    <div class="error" v-if="errorName">
        Публікація вже додана на сайт: {{ errorName.title }} - {{ errorName.publication_type.title }}
    </div>
    <!--  -->
    <b-row class="mx-0">
      <b-col cols="4" class="px-1">
        <label class="mt-3">БД Scopus/WoS</label>
        <select class="select" v-model="publicationData.science_type_id" @change="changeScienceType">
          <option :value="null"></option>
          <option 
            v-for="item in scienceTypes" 
            :key="item.id" 
            :value="item.id"
            :disabled="publicationData.scopus_id && item.id == 2"
          >{{ item.type }}</option>
        </select>
      </b-col>
      <!--  -->
      <b-col cols="8" class="px-1">
        <label class="mt-3">Вид публікації *</label>
        <select class="select" v-model="publicationData.publication_type" v-if="publicationData.science_type_id">
          <option 
            v-for="item in publicationTypes" 
            :key="item.id" 
            :value="item"
            :disabled="publicationData.scopus_id && item.id != 1 && item.id != 2 && item.id != 3"
            v-show="item.scopus_wos"
          >{{ item.title }}</option>
        </select>
        <select class="select" v-model="publicationData.publication_type" v-else>
          <option 
            v-for="item in publicationTypes" 
            :key="item.id" 
            :value="item"
            :disabled="publicationData.scopus_id && item.id != 1 && item.id != 2 && item.id != 3"
          >{{ item.title }}</option>
        </select>
        <div class="error" v-if="$v.publicationData.publication_type.$error">
            Поле обов'язкове для заповнення
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" class="pt-3" v-if="userRole == 4">
        <b-form-checkbox v-model="publicationData.not_previous_year">
          Публікація не враховувалась в рейтингу попереднього року
        </b-form-checkbox>
      </b-col>
      <!--  -->
      <b-col cols="12" class="pt-3" v-if="userRole == 4">
        <b-form-checkbox v-model="publicationData.not_this_year">
          Публікація не враховується в рейтингу цього року
        </b-form-checkbox>
      </b-col>
      <b-col cols="12" class="pt-3 text-right px-0 pagin">
        <button class="button transparent mb-2" @click="$router.go(-1)">Назад</button>
        <button class="button blue  ml-3  mb-2" @click="nextStep">Продовжити</button>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import scienceType from '../../mixins/scienceType.js';
import publicationTypes from '../../mixins/publicationTypes.js';

import { required } from 'vuelidate/lib/validators';

export default {
  mixins: [scienceType, publicationTypes],
  props: {
      publicationData: Object
  },
  data() {
    return {
      errorName: null,
      publicationNames: []
    }
  },
  created() {
    this.getScienceTypes();
    this.getPublicationTypes();
  },
  mounted() {
    this.checkPublicationData();
    this.getNamesPublications();
  },
  validations: {
      publicationData: {
          title: {
              required,
          },
          publication_type: {
              required,
              isRequired(value) {
                  return value.title != '' ? true : false
              }
          },
      },
  },
  computed: {
      userRole() {
          return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
      }
  },
  methods: {
    nextStep() {
        this.$v.$touch()
        if (this.$v.$invalid) {
            swal.fire({
                icon: 'error',
                title: 'Не всі поля заповнено!'
            })
            return
        }
        // check scopus
        if(this.publicationData.science_type_id) {
            this.$parent.isScopus = true;
        } else {
            this.$parent.isScopus = false;
        }
        let editTitle = false;
        if(this.$route.name == 'edit') {
            editTitle = this.parseString(this.publicationData.title) == this.parseString(this.publicationData.title);
        }

        // перевірка унікальності назви і типу публікації
        if(this.$route.name != 'edit') {
            var findPublication = this.publicationNames.find(item => this.parseString(item.title) == this.parseString(this.publicationData.title) && item.publication_type_id == this.publicationData.publication_type.id);
            if(findPublication) {
                this.errorName = findPublication;
                return;
            } else {
                this.errorName = null;
            }
        }
        this.$emit('getData',1);
    },
    getNamesPublications() {
        axios.get(`/api/publications-names`).then(response => {
            this.publicationNames = response.data;
        })
    },
    whosePublication() {
        if(this.$route.name == 'add') {
            if(this.publicationData.whose_publication == 'my') {
                this.publicationData.authors.push(this.$store.getters.authUser);
            } else {
                this.publicationData.authors.splice(this.publicationData.authors.indexOf(this.$store.getters.authUser), 1);
            }
        }
    },
    checkPublicationData() {
        if(this.publicationData && this.$route.name == 'edit'){
            const {title, science_type_id, publication_type} = this.publicationData;
            this.publicationData.title = title;
            this.publicationData.science_type_id = science_type_id;
            this.publicationData.publication_type = publication_type;
        }
    },
    changeScienceType() {
      this.publicationData.publication_type = "";
    },
    parseString(s) {
        const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
        return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
    }
  },
}
</script>