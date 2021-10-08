<template>
  <b-container class="wrapper">
    <Back :page="$route.name == 'profile' ? 'Кабінет' : 'Мої публікації'"></Back>
    <b-tabs content-class="mt-3">
      <b-tab title="Профіль" :active="$route.name == 'profile'">
        <b-overlay :show="loading" rounded="sm">
          <b-row class="mt-5">
            <b-col md="3" lg="3" xl="3" xs="12" class="text-center">
              <b-img v-if="user.photo" :src="user.photo" fluid class="mb-4"></b-img>
            </b-col>
            <b-col md="9" lg="9" xl="9" xs="12">
              <!--  -->
              <b-row class="mb-3 mx-0">
                <b-col cols="5" class="px-1">
                  <label>Прізвище, ім’я, по-батькові:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{ user.name }}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0">
                <b-col cols="5" class="px-1">
                  <label>Роль:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{ user.role.name }}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.job && user.job != 'СумДУ'">
                <b-col cols="5" class="px-1">
                  <label>Місце роботи:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{ user.job }}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0"  v-if="user.faculty">
                <b-col cols="5" class="px-1">
                  <label>Інститут/факультет:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{user.faculty}}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.department && (user.faculty != user.department)">
                <b-col cols="5" class="px-1">
                  <label>Кафедра:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{user.department}}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.academic_code">
                <b-col cols="5" class="px-1">
                  <label>Академічна група:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{ user.academic_code }}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.job != 'СумДУ'">
                <b-col cols="5" class="px-1">
                  <label>Країна:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{ user.country }}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.position && user.job != 'СумДУ'">
                <b-col cols="5" class="px-1">
                  <label>Входить до списків Forbes та Fortune:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  {{user.forbes_fortune ? "Так" : "Ні"}}
                </b-col>
              </b-row>
              <!--  -->
              <b-row class="mb-3 mx-0" v-if="user.guid">
                <b-col cols="5" class="px-1">
                  <label>5 або більше публікацій у періодичних виданнях Scopus та/або WoS:</label>
                </b-col>
                <b-col cols="7" class="px-1">
                  <div v-if="authUser.roles_id == 4">
                    <b-form-checkbox v-model="user.five_publications">
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
                  <input 
                    type="text" 
                    class="input" 
                    v-model="user.scopus_researcher_id" 
                    @input="$v.$touch()"
                    placeholder="A-0000-2021"
                  >
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
                  <input 
                    type="text" 
                    class="input" 
                    v-model="user.orcid" 
                    @input="$v.$touch()"
                    placeholder="0000-0000-0000-0000"
                  >
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
                  <input 
                    type="text" 
                    class="input" 
                    v-model="user.scopus_id" 
                    @input="$v.$touch()"
                    placeholder="00000000000"
                  >
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
                      <input type="number" min="0" class="input" v-model="user.scopus_autor_id">
                    </b-col>
                    <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                      <label>БД WoS:</label>
                    </b-col>
                    <b-col md="3" lg="3" xl="3" xs="6">
                      <input type="number" min="0" class="input" v-model="user.h_index">
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
                      <input type="number" min="0" class="input" v-model="user.without_self_citations_scopus">
                    </b-col>
                    <b-col md="3" lg="3" xl="3" xs="6" class="pt-1">
                      <label>БД WoS:</label>
                    </b-col>
                    <b-col md="3" lg="3" xl="3" xs="6">
                      <input type="number" min="0" class="input" v-model="user.without_self_citations_wos">
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
                    <button class="button blue save" @click="save()">Зберегти</button>
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
import access from "../mixins/access";

import {numeric} from "vuelidate/lib/validators";

export default {
  mixins: [access],
  components: {
    Back,
    UserPublication
  },
  data() {
    return {
      loading: true,
      loadingSave: false,
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
    }
  },
  created() {
    this.getUser();
  },
  computed: {
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
      axios.get('/api/user')
      .then(response => {
        this.user = Object.assign(this.user, response.data);
        this.loading = false;
      })
    },
    save() {
      this.$v.$touch();
      if (this.$v.$invalid || this.checkSelfCitationsWos || this.checkSelfCitationsScopus) {
        return
      }
      this.loadingSave = true;
      axios.post(`/api/profile`, this.user)
        .then((response) => {
          this.$store.dispatch('setUser', response.data)
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
    }
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
</style>