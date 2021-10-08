<template>
  <div>
    <b-row>
      <b-col cols="6" class="pr-1 mb-3">
        <button disabled class="button blue block">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 mb-3">
        <button disabled class="button transparent block">Очистити</button>
      </b-col>
      <!--  -->
      <b-col cols="12">
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
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-2><span>Кафедра</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-2" class="mt-2">
            <div class="text-center" v-if="filter.faculty_code.length == 0">Оберіть факультет</div>
            <b-form-group>
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
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-3><span>Звітний рік</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-3" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                v-model="filter.years"
                :options="years"
                :class="[showAllYears1 ? 'all' : 'limit']"
              ></b-form-checkbox-group>
            </b-form-group>
            <div class="text-center text-primary cursor" @click="showAllYears1 = !showAllYears1">{{ showAllYears1 ? 'Приховати' : 'Показати всі' }}</div>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-4><span>Рік видання</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-4" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                v-model="filter.years"
                :options="years"
                :class="[showAllYears2 ? 'all' : 'limit']"
              ></b-form-checkbox-group>
            </b-form-group>
            <div class="text-center text-primary cursor" @click="showAllYears2 = !showAllYears2">{{ showAllYears2 ? 'Приховати' : 'Показати всі' }}</div>
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
          <div class="label" v-b-toggle.collapse-5 @click="getPublicationTypes()"><span>Вид публікації</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-5" class="mt-2">
            <div v-if="loadingPublicationTypes" class="d-flex justify-content-center">
              <b-spinner label="Loading..."></b-spinner>
            </div>
            <b-form-group>
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
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-17><span>Індексування БД Scopus/WoS</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-17" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Scopus'},{value: 2, text: 'WoS'},{value: 3, text: 'Scopus та WoS'}]"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-7><span>Квартиль журналу Scopus</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-7" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[1, 2, 3, 4]"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-8><span>Квартиль журналу WoS</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-8" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[1, 2, 3, 4]"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-9><span>Публікації з цифровим ідентифікатором DOI</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-9" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-10><span>Публікації опубліковані у виданнях з показником SNIP більше ніж 1.0</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-10" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-11><span>Статті у виданнях, які входять до підбази WoS - SCIE</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-11" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-18><span>Статті у виданнях, які входять до підбази WoS - SSCI</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-18" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-12><span>Охоронні документи</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-12" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 'СумДУ', text: 'Отримані на ім\'я СумДУ'}, {value: 'Не СумДУ', text: 'Отримані не на ім\'я СумДУ'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-13><span>Комерціалізовані охоронні документи</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-13" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Університетом'}, {value: 2, text: 'Штатними співробітниками'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-14><span>Публікації у співавторстві з іноземними партнерами</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-14" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}, {value: 10, text: 'В тому числі мають індекс Гірша за Scopus та WoS не нижче 10'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-15><span>Публікації опубліковані за кордоном</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-15" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="[{value: 1, text: 'Так'}, {value: 0, text: 'Ні'}]"
                value-field="value"
                text-field="text"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-16><span>Рік занесення до бази даних</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-16" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                v-model="filter.years"
                :options="yearsDb"
                style="column-count: 3;"
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <b-form-checkbox>
            Публікації за авторством та співавторством студентів
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <b-form-checkbox>
            Публікації за співавторством з представниками інших організацій
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <b-form-checkbox>
            Публікації які не враховані в рейтингу попереднього року
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <b-form-checkbox>
            Публікації які не враховані в рейтингу цього року
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="6" class="pr-1 my-3">
        <button disabled class="button blue block">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 my-3">
        <button disabled class="button transparent block">Очистити</button>
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
      showAllYears1: false,
      showAllYears2: false,
      showMultiselect: false,
      filter: {
        faculty_code: [],
        department_code: [],
        science_type_id: [],
        years: [],
        country: ""
      },
    }
  },
  created() {
    this.getCountry();
  },
  computed: {
    multiselectSearch() {
      return this.country.filter(item => {
        return item.toLowerCase().includes(this.filter.country.toLowerCase());
      })
    },
    yearsDb() {
      const year = new Date().getFullYear();
      return Array.from({length: year - 2019}, (value, index) => 2020 + index).reverse();
    },
  },
  methods: {
    closeMultiselect() {
      setTimeout(() => {
        this.showMultiselect = false;
      }, 200);
    },
  }
}
</script>