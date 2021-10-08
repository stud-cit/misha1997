<template>
  <div>
    <b-row>
      <b-col cols="6" class="pr-1 mb-3">
        <button class="button blue block" @click="$emit('getUsers', 1, filter)">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 mb-3">
        <button @click="clearFilter()" class="button transparent block">Очистити</button>
      </b-col>
      <b-col cols="12" class="mb-2">
        <input type="text" class="input" placeholder="ПІБ автора" v-model="filter.name" v-on:keyup.enter="$emit('getUsers', 1, filter)">
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
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-3><span>Посада</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-3" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="categUsers"
                v-model="filter.categUsers"
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
          <div class="label" v-b-toggle.collapse-4><span>Індекс Гірша</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-4" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group v-model="filter.indexH" stacked>
                <b-form-checkbox :disabled="filter.indexH.indexOf(2) > -1" :value="1" @change="checkIndexH1()">Так</b-form-checkbox>
                <b-form-checkbox :value="2" @change="checkIndexH2()">Ні</b-form-checkbox>
                <b-form-checkbox :disabled="filter.indexH.indexOf(1) == -1" :value="3">Мають індекс Гірша за Scopus та WoS не нижче 10</b-form-checkbox>
              </b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-5><span>Роль</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-5" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="roles"
                v-model="filter.roles"
                value-field="id"
                text-field="name"
                stacked
              ></b-form-checkbox-group>
            </b-form-group>
          </b-collapse>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="12">
        <div class="select-filter">
          <div class="label" v-b-toggle.collapse-6><span>Наявність Scopus ID</span> <b-icon icon="chevron-down"></b-icon></div>
          <b-collapse id="collapse-6" class="mt-2">
            <b-form-group>
              <b-form-checkbox-group
                :options="scopusId"
                v-model="filter.scopusId"
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
          <b-form-checkbox v-model="filter.five_publications">
            5 або більше публікацій у періодичних виданнях Scopus та/або WoS
          </b-form-checkbox>
        </div>
      </b-col>
      <!--  -->
      <b-col cols="6" class="pr-1 my-3">
        <button class="button blue block" @click="$emit('getUsers', 1, filter)">Пошук</button>
      </b-col>
      <b-col cols="6" class="pl-1 my-3">
        <button @click="clearFilter()" class="button transparent block">Очистити</button>
      </b-col>
    </b-row>
  </div>
</template>
<script>
import divisions from '../mixins/divisions.js';
import roles from '../mixins/roles.js';

export default {
  mixins: [divisions, roles],
  data() {
    return {
      filter: {
        name: "",
        faculty_code: [],
        department_code: [],
        scopusId: [],
        indexH: [],
        roles: [],
        categUsers: [],
        five_publications: false
      },
      filterDefault: {},
      categUsers: [
        { id: 1, title: "Аспіранти"}, 
        { id: 2, title: "Викладачі"},
        { id: 3, title: "Докторанти"}, 
        { id: 4, title: "Зовнішні співавтори"}, 
        { id: 5, title: "Іноземці"}, 
        { id: 6, title: "Менеджери"}, 
        { id: 7, title: "Співробітники"}, 
        { id: 8, title: "Студенти"}, 
        { id: 9, title: "СумДУ"}, 
        { id: 10, title: "СумДУ (не працює)"},
      ],
      scopusId: [
        { id: 1, title: "Так"},
        { id: 0, title: "Ні"}
      ],
      indexH: [
        { id: 1, title: "Так"},
        { id: 2, title: "Ні"},
        { id: 3, title: "Мають індекс Гірша за Scopus та WoS не нижче 10"}
      ]
    }
  },
  created() {
    this.filterDefault = Object.assign(this.filterDefault, this.filter);
  },
  computed: {
    authUser() {
      return this.$store.getters.authUser
    },
  },
  methods: {
    checkIndexH1() {
      if(this.filter.indexH.indexOf(1) == -1) {
        this.filter.indexH.splice(this.filter.indexH.indexOf(3), 1)
      }
    },
    checkIndexH2() {
      if(this.filter.indexH.indexOf(2) > -1) {
        this.filter.indexH = [2];
      }
    },
    clearFilter() {
      this.filter = Object.assign({}, this.filterDefault);
      this.filter.department_code = [];
      this.$emit('getUsers', 1, this.filter);
    }
  }
}
</script>