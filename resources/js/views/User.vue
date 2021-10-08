<template>
  <b-container class="wrapper">
    <Back :page="user.name"></Back>
    <b-tabs content-class="mt-3">
      <b-tab title="Профіль" :active="$route.name == 'profile'">
        <b-overlay :show="loading" rounded="sm">
          <b-row class="mt-5">
            <b-col md="3" lg="3" xl="3" xs="12" class="text-center">
              <b-img v-if="user.photo" :src="user.photo" fluid class="mb-4"></b-img>
            </b-col>
            <b-col md="9" lg="9" xl="9" xs="12">
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Прізвище, ім’я, по-батькові:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && !user.guid">
                      <input type="text" class="input" v-model="user.name">
                    </div>
                    <div v-else>{{ user.name }} <img src="/img/update.png" @click="updateCabinetInfo" :class="['update-cabinet-info', updateLoading ? 'spin' : '']" v-b-popover.hover.top="'Оновити інформацію з особистого кабінету'"></div>
                  </b-col>
                </b-row>
                <b-row class="mb-3 mx-0" v-if="user.date_bth">
                  <b-col cols="5" class="px-1">
                    <label>Дата народження:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div>{{ user.date_bth }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Роль:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && user.guid">
                      <select class="select" v-model="user.roles_id">
                        <option
                            v-for="(item, index) in roles"
                            :key="index"
                            :value="item.id"
                        >{{item.name}}</option>
                      </select>
                    </div>
                    <div v-else>{{ user.role.name }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.job_type || authUser.roles_id == 4">
                  <b-col cols="5" class="px-1">
                    <label>Місце роботи:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && user.job_type_id != 5">
                      <select class="select" v-model="user.job_type_id">
                        <option value=""></option>
                        <option
                            v-for="(item, index) in job"
                            :key="index"
                            :value="item.id"
                        >{{item.title}}</option>
                      </select>
                    </div>
                    <div v-else>{{ user.job_type.title }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.job_type_id == 1">
                  <b-col cols="5" class="px-1">
                    <label>Навчальний заклад:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <input type="text" class="input" v-model="user.job">
                    </div>
                    <div v-else>{{ user.job }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.job_type_id == 2">
                  <b-col cols="5" class="px-1">
                    <label>Підприємство:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <input type="text" class="input" v-model="user.job">
                    </div>
                    <div v-else>{{ user.job }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.guid">
                  <b-col cols="5" class="px-1">
                    <label>Посада:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && !user.guid">
                      <input type="text" class="input" v-model="user.position">
                    </div>
                    <div v-else>{{ user.position }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.custom_divisions || user.faculty">
                  <b-col cols="5" class="px-1">
                    <label>Інститут/факультет:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="user.custom_divisions || (authUser.roles_id == 4 && !user.guid)">
                      <select class="select" v-model="user.faculty_code">
                        <option value=""></option>
                        <option
                            v-for="(item, index) in divisions"
                            :key="index"
                            :value="item.ID_DIV"
                        >{{item.NAME_DIV}}</option>
                      </select>
                    </div>
                    <div v-else>{{user.faculty}}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.custom_divisions || (user.department && (user.faculty != user.department))">
                  <b-col cols="5" class="px-1">
                    <label>Кафедра:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="user.custom_divisions || (authUser.roles_id == 4 && !user.guid)">
                      <select class="select" v-model="user.department_code">
                        <option value=""></option>
                        <option
                            v-for="(item, index) in departmentsUser"
                            :key="index"
                            :value="item.ID_DIV"
                        >{{item.NAME_DIV}}</option>
                      </select>
                    </div>
                    <div v-else>{{user.department}}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="authUser.roles_id == 4 && user.guid && user.categ_1 != 1">
                  <b-col cols="12" class="px-1">
                    <b-form-checkbox value="1" unchecked-value="0" v-model="user.custom_divisions">
                      Назначити каферу і факультет
                    </b-form-checkbox>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.academic_code">
                  <b-col cols="5" class="px-1">
                    <label>Академічна група:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && !user.guid">
                      <input type="text" class="input" v-model="user.academic_code">
                    </div>
                    <div v-else>{{ user.academic_code }}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="(!user.guid && !user.faculty_code) && (!user.guid && !user.department_code)">
                  <b-col cols="5" class="px-1">
                    <label>Країна:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4 && !user.guid">
                      <Country :data="user"></Country>
                    </div>
                    <div v-else>{{user.country}}</div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="!user.guid">
                  <b-col cols="5" class="px-1">
                    <label>Входить до списків Forbes та Fortune:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <b-form-checkbox v-model="user.forbes_fortune">
                        {{user.forbes_fortune ? "Так" : "Ні"}}
                      </b-form-checkbox>
                    </div>
                    <div v-else>
                      {{user.forbes_fortune ? "Так" : "Ні"}}
                    </div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.guid">
                  <b-col cols="5" class="px-1">
                    <label>5 або більше публікацій у періодичних виданнях Scopus та/або WoS:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <b-form-checkbox value="1" unchecked-value="0" v-model="user.five_publications">
                        {{user.five_publications ? "Так" : "Ні"}}
                      </b-form-checkbox>
                    </div>
                    <div v-else>
                      {{user.five_publications ? "Так" : "Ні"}}
                    </div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Research ID:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <input 
                        type="text" 
                        class="input" 
                        v-model="user.scopus_researcher_id" 
                        @input="$v.$touch()" 
                        placeholder="A-0000-2021"
                      >
                    </div>
                    <div v-else>
                      {{user.scopus_researcher_id}}
                    </div>
                    <div class="error" v-if="$v.user.scopus_researcher_id.$error">
                      Не коректний формат. Приклад (A-0000-2021)
                    </div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>ORCID:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <input 
                        type="text" 
                        class="input" 
                        v-model="user.orcid" 
                        @input="$v.$touch()" 
                        placeholder="0000-0000-0000-0000"
                      >
                    </div>
                    <div v-else>
                      {{user.orcid}}
                    </div>
                    <div class="error" v-if="$v.user.orcid.$error">
                      Не коректний формат. Приклад (0000-0000-0000-0000)
                    </div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Scopus ID:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <div v-if="authUser.roles_id == 4">
                      <input 
                        type="text" 
                        class="input" 
                        v-model="user.scopus_id" 
                        @input="$v.$touch()"
                        placeholder="00000000000"
                      >
                    </div>
                    <div v-else>
                      {{user.scopus_id}}
                    </div>
                    <div class="error" v-if="$v.user.scopus_id.$error">
                      Дозволено лише цифри. Приклад (00000000000)
                    </div>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Індекс Гірша:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <b-row>
                      <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                        <label>БД Scopus:</label>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6">
                        <div v-if="authUser.roles_id == 4">
                          <input type="number" min="0" class="input" v-model="user.scopus_autor_id">
                        </div>
                        <div v-else>
                          {{user.scopus_autor_id}}
                        </div>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                        <label>БД WoS:</label>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6">
                        <div v-if="authUser.roles_id == 4">
                          <input type="number" min="0" class="input" v-model="user.h_index">
                        </div>
                        <div v-else>
                          {{user.h_index}}
                        </div>
                      </b-col>
                    </b-row>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0">
                  <b-col cols="5" class="px-1">
                    <label>Індекс Гірша без самоцитувань:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                    <b-row>
                      <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                        <label>БД Scopus:</label>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6">
                        <div v-if="authUser.roles_id == 4">
                          <input type="number" min="0" class="input" v-model="user.without_self_citations_scopus">
                        </div>
                        <div v-else>
                          {{user.without_self_citations_scopus}}
                        </div>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                        <label>БД WoS:</label>
                      </b-col>
                      <b-col md="3" lg="3" xl="3" xs="6">
                        <div v-if="authUser.roles_id == 4">
                          <input type="number" min="0" class="input" v-model="user.without_self_citations_wos">
                        </div>
                        <div v-else>
                          {{user.without_self_citations_wos}}
                        </div>
                      </b-col>
                      <b-col cols="6">
                        <div class="error" v-if="!loading && checkSelfCitationsScopus">
                          Індекс Гірша Scopus без самоцитувань не може перевищувати <b>{{ user.scopus_autor_id }}</b>
                        </div>
                      </b-col>
                      <b-col cols="6">
                        <div class="error" v-if="!loading && checkSelfCitationsWos">
                          Індекс Гірша WoS без самоцитувань не може перевищувати <b>{{ user.h_index }}</b>
                        </div>
                      </b-col>
                    </b-row>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3 mx-0" v-if="user.user">
                  <b-col cols="5" class="px-1">
                    <label>Зареєстрував:</label>
                  </b-col>
                  <b-col cols="7" class="px-1">
                      <a :href="'/user/'+user.user.id">{{user.user.name}}</a>
                  </b-col>
                </b-row>
                <!--  -->
                <b-row class="mb-3">
                  <b-col cols="12" class="text-right">
                    <b-overlay
                      :show="loadingSave"
                      rounded
                      opacity="0.6"
                      spinner-small
                      spinner-variant="primary"
                      class="d-inline-block"
                    >
                      <button class="button blue save" @click="save()" v-if="authUser.roles_id == 4">Зберегти</button>
                    </b-overlay>
                  </b-col>
                </b-row>
            </b-col>
          </b-row>
        </b-overlay>
      </b-tab>
      <b-tab title="Публікації" :active="$route.name == 'my-publications'">
        <UserPublication></UserPublication>
      </b-tab>
    </b-tabs>
  </b-container>
</template>
<script>
import Back from "../components/Back.vue";
import UserPublication from "../components/UserPublication.vue";
import Country from "../components/Country.vue";

import roles from '../mixins/roles.js';
import job from '../mixins/job.js';
import divisions from '../mixins/divisions.js';
import access from "../mixins/access";

import {numeric} from "vuelidate/lib/validators";

export default {
  mixins: [roles, job, divisions, access],
  components: {
    Back,
    UserPublication,
    Country
  },
  data() {
      return {
        loading: true,
        loadingSave: false,
        updateLoading: false,
        user: {
          photo: null,
          name: "",
          scopus_id: "",
          role: {
              name: ""
          },
          guid: null,
          date_bth: "",
          job: "",
          position: "",
          faculty: "",
          faculty_code: null,
          department: "",
          department_code: null,
          academic_code: "",
          country: "",
          scopus_autor_id: "",
          h_index: "",
          scopus_researcher_id: "",
          orcid: "",
          five_publications: "0",
          without_self_citations_wos: "",
          without_self_citations_scopus: "",
          created_at: "",
          custom_divisions: false,
          user: {
              id: null,
              name: ""
          }
        },
      }
  },
  validations: {
    user: {
      orcid: {
        validFormat: val => val ? /(\d{4})\-(\d{4})\-(\d{4})\-(\d{4})/.test(val) : true, 
      },
      scopus_id: {
        numeric
      },
      scopus_researcher_id: {
        validFormat: val => val ? /(\w+)\-(\d{4})\-(\d{4})/.test(val) : true, 
      }
    },
  },
  created() {
      this.getUser();
      this.getDivisions();
  },
  watch: {
    $route(to, from) {
      this.getUser();
    }
  },
  computed: {
    departmentsUser() {
      let departments = [];
      if(this.user.faculty_code) {
          departments = this.divisions.find(item => {
              return this.user.faculty_code == item.ID_DIV
          });
          if(departments) {
              departments = departments.departments;
          }
      } else {
          departments = [];
          this.user.department_code = "";
      }
      return departments;
    },
    checkSelfCitationsWos() {
      if(this.user.h_index) {
        return (this.user.without_self_citations_wos <= this.user.h_index) ? false : true;
      } else {
        return false;
      }
    },
    checkSelfCitationsScopus() {
      if(this.user.scopus_autor_id) {
        return (this.user.without_self_citations_scopus <= this.user.scopus_autor_id) ? false : true;
      } else {
        return false;
      }
    }
  },
  methods: {
      getUser() {
          axios.get('/api/user' + (this.$route.params.id ? '/'+this.$route.params.id : ''))
          .then(response => {
            this.user = response.data;
            this.loading = false;
          })
      },
      save() {
        this.$v.$touch();
        if (this.$v.$invalid || this.checkSelfCitationsWos || this.checkSelfCitationsScopus) {
            return
        }
        this.loadingSave = true;
        if(!this.user.job) {
            this.user.job = "Не працює";
        }
        axios.post(`/api/update-author/${this.$route.params.id}`, this.user)
          .then(() => {
              this.getUser();
              swal.fire({
                  icon: 'success',
                  title: 'Інформацію оновлено'
              });
              this.loadingSave = false;
          }).catch(() => {
              swal.fire({
                  icon: 'error',
                  title: 'Помилка'
              });
              this.loadingSave = false;
          })
      },
      updateCabinetInfo() {
        this.updateLoading = true;
        axios.post(`/api/update-cabinet-info/${this.$route.params.id}`)
        .then((response) => {
          if(response.data.status == 'ok') {
            this.getUser();
          }
          if(response.data.status == 'error') {
            swal.fire({
              icon: 'error',
              title: 'В базі даних СумДУ користувача не знайдено'
            });
          }
          this.updateLoading = false;
        })
    },
  }
}
</script>
<style lang="css" scoped>
  .wrapper {
    font-size: 16px;
    line-height: 21px;
  }
  .wrapper label {
    font-weight: normal;
  }
  button.button.save {
    padding: 10px !important;
    font-size: 16px !important;
    width: 186px;
  }
  img.update-cabinet-info {
    width: 12px;
    cursor: pointer;
  }
  .spin {
    animation: 1s linear 0s normal none infinite running spin;
    -webkit-animation: 1s linear 0s normal none infinite running spin;
  }
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  @-webkit-keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
</style>