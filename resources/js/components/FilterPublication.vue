<template>
  <div>
    <b-row>
      <b-col cols="6" class="pr-1 mb-3">
        <button class="button blue block" @click="$emit('getPublications', 1, filter)">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 mb-3">
        <button @click="clearFilter()" class="button transparent block" v-on:keyup.enter="$emit('getPublications', 1, filter)">Очистити</button>
      </b-col>
      <!--  -->
      <b-col cols="12" class="mb-3">
        <input type="search" class="input" placeholder="Назва публікації" v-model="filter.title" v-on:keyup.enter="$emit('getPublications', 1, filter)">
      </b-col>
      <!--  -->
      <b-col cols="12" class="mb-2">
        <input type="search" class="input" placeholder="ПІБ автора" v-model="filter.name">
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-7 @click="getPublicationTypes()"><span>Вид публікації</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-7" class="mt-2">
            <div v-if="loadingPublicationTypes" class="d-flex justify-content-center">
              <b-spinner label="Loading..."></b-spinner>
            </div>
            <b-form-group v-model="filter.publication_type_id" stacked v-if="$route.name == 'scopus'">
              <b-form-checkbox 
                v-for="item in publicationTypes"
                :key="item.id"
                :value="item.id"
                v-show="[1, 12, 11, 13, 5].indexOf(item.id) == -1"
              >
                {{ item.title }}
              </b-form-checkbox>
            </b-form-group>
            <b-form-group v-else>
              <b-form-checkbox-group
                v-model="filter.publication_type_id"
                :options="publicationTypes"
                value-field="id"
                text-field="title"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" v-if="$route.name != 'my-publications' && $route.name != 'profile'">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-1 @click="getDivisions()"><span>Інститут / факультет</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-1" class="mt-2">
            <div v-if="loadDivisions" class="d-flex justify-content-center">
              <b-spinner label="Loading..."></b-spinner>
            </div>
            <b-form-group>
              <b-form-checkbox-group
                v-model="filter.faculty_code"
                :options="divisions"
                value-field="ID_DIV"
                text-field="NAME_DIV"
                stacked
                @change="getDepartments()"
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" v-if="$route.name != 'my-publications' && $route.name != 'profile'">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-2><span>Кафедра</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-2" class="mt-2">
            <div class="text-center" v-if="filter.faculty_code.length == 0">Оберіть факультет</div>
            <b-form-group v-else>
              <b-form-checkbox-group
                v-model="filter.department_code"
                :options="departments"
                value-field="ID_DIV"
                text-field="NAME_DIV"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" v-if="$route.name != 'my-publications' && $route.name != 'profile'">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-3><span>Посада</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-3" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="categUsers"
                v-model="filter.position"
                value-field="id"
                text-field="title"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-4 @click="getScienceTypes()"><span>БД Scopus/WoS</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-4" class="mt-2">
            <div v-if="loadScienceTypes" class="d-flex justify-content-center">
              <b-spinner label="Loading..."></b-spinner>
            </div>
            <b-form-group v-model="filter.science_type_id" stacked v-if="$route.name == 'scopus'">
              <b-form-checkbox 
                v-for="item in scienceTypes"
                :key="item.id"
                :value="item.id"
                v-show="[1, 3].indexOf(item.id) > -1"
              >
                {{ item.type }}
              </b-form-checkbox>
            </b-form-group>
            <b-form-group v-else>
              <b-form-checkbox-group
                v-model="filter.science_type_id"
                :options="scienceTypes"
                value-field="id"
                text-field="type"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-5><span>Рік видання</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-5" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                v-model="filter.years"
                :options="years"
                :class="[showAllYears ? 'all' : 'limit']"
              ></b-form-checkbox-group>
            </b-form-group>
            <div class="text-center text-primary cursor" @click="showAllYears = !showAllYears">{{ showAllYears ? 'Приховати' : 'Показати всі' }}</div>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter multiselect">
          <div class="label">
            <input 
              type="search" 
              class="input"
              v-model="filter.country" 
              @focus="showMultiselect = true" 
              @blur="closeMultiselect()" 
              placeholder="Країна видання"
            >
          </div>
          <ul class="list-multiselect" v-if="showMultiselect && multiselectSearch.length > 0">
            <li v-for="(item, index) in multiselectSearch" :key="index" @click="filter.country = item; showMultiselect = false">{{ item }}</li>
          </ul>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-8><span>Автоматично занесені публікації Scopus</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-8" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="isScopus"
                v-model="filter.isScopus"
                value-field="id"
                text-field="title"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <b-form-checkbox v-model="filter.supervisor">
            {{ ($route.name != 'my-publications' && $route.name != 'profile') ? 'Одноосібні публікації студентів під керівництвом автора' : 'Під керівництвом' }}
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" v-if="$route.name != 'my-publications' && $route.name != 'profile'">
        <div class="select-filter">
          <b-form-checkbox v-model="filter.notPreviousYear">
            Публікації, які не враховані в рейтингу попереднього року
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12" v-if="$route.name != 'my-publications' && $route.name != 'profile'">
        <div class="select-filter">
          <b-form-checkbox v-model="filter.notThisYear">
            Публікації, які не враховані в рейтингу цього року
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="6" class="pr-1 my-3">
        <button class="button blue block" @click="$emit('getPublications', 1, filter)">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 my-3">
        <button @click="clearFilter()" class="button transparent block">Очистити</button>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import divisions from '../mixins/divisions.js';
import scienceType from '../mixins/scienceType.js';
import years from '../mixins/years.js';
import country from '../mixins/country.js';
import publicationTypes from '../mixins/publicationTypes.js';

export default {
  mixins: [divisions, scienceType, years, country, publicationTypes],
  data() {
    return {
      showAllYears: false,
      showMultiselect: false,
      filter: {
        title: "",
        name: "",
        faculty_code: [],
        department_code: [],
        position: [],
        science_type_id: [],
        years: [],
        country: "",
        publication_type_id: [],
        isScopus: [],
        supervisor: false,
        notPreviousYear: false,
        notThisYear: false
      },
      filterDefault: {},
      categUsers: [
        { id: 1, title: "Аспіранти"}, 
        { id: 2, title: "Викладачі"},
        { id: 3, title: "Докторанти"}, 
        { id: 4, title: "Зовнішні співавтори"}, 
        { id: 5, title: "Співавтори іноземці"}, 
        { id: 6, title: "Менеджери"}, 
        { id: 7, title: "Співробітники"}, 
        { id: 8, title: "Студенти"}, 
        { id: 9, title: "СумДУ"}, 
        { id: 10, title: "СумДУ (не працює)"}
      ],
      isScopus: [
        { id: 1, title: "Внесено автором"},
        { id: 2, title: "Внесено автоматично"},
        { id: 3, title: "Верифіковані публікації"},
        { id: 4, title: "Не верифіковані"}
      ]
    }
  },
  computed: {
    multiselectSearch() {
      return this.country.filter(item => {
        return item.toLowerCase().includes(this.filter.country.toLowerCase());
      })
    }
  },
  created() {
    this.getCountry();
    this.filterDefault = Object.assign(this.filterDefault, this.filter);
  },
  methods: {
    closeMultiselect() {
      setTimeout(() => {
        this.showMultiselect = false;
      }, 200);
    },
    clearFilter() {
      this.filter = Object.assign({}, this.filterDefault);
      this.filter.department_code = [];
      this.$emit('getPublications', 1, this.filter);
    }
  }
}
</script>