<template>
  <div class="step">

    <b-modal id="modal-1" centered title="Додати автора з СумДУ" hide-footer hide-header-close no-close-on-backdrop no-close-on-esc>
      <div class="form-group">
          <label>Оберіть категорію</label>
          <select class="select" v-model="selectCateg" @change="getPersonAPI">
              <option
                  v-for="item in categ"
                  :key="'item-'+item.value"
                  :value="item.value"
              >
                  {{item.name}}
              </option>
          </select>
      </div>
      <!--  -->
      <div v-if="loadingPersons" class="text-center">Завантаження</div>
      <!--  -->
      <div class="form-group" v-if="persons.length > 0">
          <label class="item-title">Прізвище, ім’я, по-батькові</label>
          <div class="input-container authors">
              <multiselect
                  v-model="newAuthorSSU"
                  label="name"
                  :options="persons"
                  :preserve-search="true"
                  placeholder="Пошук в базі даних СумДУ"
                  selectLabel="Натисніть щоб обрати"
                  selectedLabel="Обрано"
                  deselectLabel="Натисніть щоб видалити"
                  :custom-label="nameWithInfoSSU"
              >
                  <span slot="noResult">По даному запиту результатів не знайдено</span>
              </multiselect>
          </div>
      </div>
      <div class="text-right mt-4">
        <button class="button transparent" @click="$bvModal.hide('modal-1'); $v.$reset();">Назад</button>
        <b-overlay
          :show="loading"
          rounded
          opacity="0.6"
          spinner-small
          spinner-variant="primary"
          class="d-inline-block"
        >
          <button class="button blue" @click="addNewAuthorSSU" :disabled="loading">Додати</button>
        </b-overlay>
      </div>
    </b-modal>

    <b-modal id="modal-2" size="lg" centered title="Реєстрація нового автора" hide-footer hide-header-close no-close-on-backdrop no-close-on-esc>
      <b-row class="mb-3 mx-0">
        <b-col cols="4" class="px-1">
          <label>Прізвище, ім’я, по-батькові *</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <input type="text" class="input" v-model="newAuthor.name" @input="findNames">
          <datalist id="names">
              <option v-for="(item, index) in names" :key="index" :value="item.name">{{ item.job }}</option>
          </datalist>
          <div class="error" v-if="$v.newAuthor.name.$error">
              Поле обов'язкове для заповнення
          </div>
            <div class="error" v-if="errorName">
              Автор вже зареєстрований в системи: {{ errorName.name }} - {{ errorName.job }}
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0">
        <b-col cols="4" class="px-1">
          <label>Місце роботи *</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <select class="select" v-model="newAuthor.job_type_id" @change="setJobType">
            <option v-show="jobItem.id != 5" v-for="jobItem in job" :key="jobItem.id" :value="jobItem.id">{{ jobItem.title }}</option>
          </select>
          <div class="error" v-if="$v.newAuthor.job_type_id.$error">
              Поле обов'язкове для заповнення
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-if="newAuthor.job_type_id == 1 || newAuthor.job_type_id == 2">
        <b-col cols="4" class="px-1">
          <label>{{ newAuthor.job_type_id == 1 ? 'Назва учбового закладу' : 'Підприємство' }} *</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <input type="text" class="input" v-model="newAuthor.job">
          <div class="error" v-if="$v.newAuthor.job.$error">
              Поле обов'язкове для заповнення
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-if="newAuthor.job_type_id == 6">
        <b-col cols="4" class="px-1">
          <label>Інститут/факультет *</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <select class="select" v-model="newAuthor.faculty_code">
              <option value=""></option>
              <option
                  v-for="(item, index) in divisions"
                  :key="index"
                  :value="item.ID_DIV"
              >{{item.NAME_DIV}}</option>
          </select>
          <div class="error" v-if="$v.newAuthor.job.$error">
              Поле обов'язкове для заповнення
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-if="newAuthor.job_type_id == 6">
        <b-col cols="4" class="px-1">
          <label>Кафедра *</label>
        </b-col>
        <b-col cols="8" class="px-1">
            <select class="select" v-model="newAuthor.department_code">
                <option value=""></option>
                <option
                    v-for="(item, index) in departmentsUser"
                    :key="index"
                    :value="item.ID_DIV"
                >{{item.NAME_DIV}}</option>
            </select>
          <div class="error" v-if="$v.newAuthor.job.$error">
              Поле обов'язкове для заповнення
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-if="newAuthor.job_type_id == 2">
        <b-col cols="4" class="px-1">
          <label>Входить до списків Forbes та Fortune *</label>
        </b-col>
        <b-col cols="8" class="px-1">
            <b-form-checkbox v-model="newAuthor.forbes_fortune">
              {{newAuthor.forbes_fortune ? "Так" : "Ні"}}
            </b-form-checkbox>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-show="newAuthor.job_type_id != 3 && newAuthor.job_type_id != 6">
        <b-col cols="4" class="px-1">
          <label>Країна автора *</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <Country :data="newAuthor"></Country>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-show="newAuthor.job_type_id != 3 && newAuthor.job_type_id != 6">
        <b-col cols="4" class="px-1">
          <label>Індекс Гірша</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <b-row>
            <b-col cols="3" class="pt-1">
              <label>БД Scopus:</label>
            </b-col>
            <b-col cols="3">
              <input type="number" min="0" class="input" v-model="newAuthor.scopus_autor_id">
            </b-col>
            <b-col cols="3" class="pt-1">
              <label>БД WoS:</label>
            </b-col>
            <b-col cols="3">
              <input type="number" min="0" class="input" v-model="newAuthor.h_index">
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-show="newAuthor.job_type_id != 3 && newAuthor.job_type_id != 6">
        <b-col cols="4" class="px-1">
          <label>Індекс Гірша без самоцитувань</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <b-row>
            <b-col cols="3" class="pt-1">
              <label>БД Scopus:</label>
            </b-col>
            <b-col cols="3">
              <input type="number" min="0" class="input" v-model="newAuthor.without_self_citations_scopus">
            </b-col>
            <b-col cols="3" class="pt-1">
              <label>БД WoS:</label>
            </b-col>
            <b-col cols="3">
              <input type="number" min="0" class="input" v-model="newAuthor.without_self_citations_wos">
            </b-col>
            <b-col cols="6">
              <div class="error" v-if="!loading && checkSelfCitationsScopus">
                Індекс Гірша Scopus без самоцитувань не може перевищувати <b>{{ newAuthor.scopus_autor_id }}</b>
              </div>
            </b-col>
            <b-col cols="6">
              <div class="error" v-if="!loading && checkSelfCitationsWos">
                Індекс Гірша WoS без самоцитувань не може перевищувати <b>{{ newAuthor.h_index }}</b>
              </div>
            </b-col>
          </b-row>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-show="newAuthor.job_type_id != 3">
        <b-col cols="4" class="px-1">
          <label>Research ID</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <input 
            type="text" 
            class="input" 
            v-model="newAuthor.scopus_researcher_id"
            placeholder="A-0000-2021"
            @input="$v.$touch()"
          >
          <div class="error" v-if="$v.newAuthor.scopus_researcher_id.$error">
            Не коректний формат. Приклад (A-0000-2021)
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <b-row class="mb-3 mx-0" v-show="newAuthor.job_type_id != 3">
        <b-col cols="4" class="px-1">
          <label>ORCID</label>
        </b-col>
        <b-col cols="8" class="px-1">
          <input 
            type="text" 
            class="input" 
            v-model="newAuthor.orcid"
            @input="$v.$touch()"
            placeholder="0000-0000-0000-0000"
          >
          <div class="error" v-if="$v.newAuthor.orcid.$error">
            Не коректний формат. Приклад (0000-0000-0000-0000)
          </div>
        </b-col>
      </b-row>
      <!--  -->
      <div class="text-right mt-4">
        <button class="button transparent" @click="$bvModal.hide('modal-2'); $v.$reset();">Назад</button>
        <b-overlay
          :show="loading"
          rounded
          opacity="0.6"
          spinner-small
          spinner-variant="primary"
          class="d-inline-block"
        >
          <button class="button blue" @click="addNewAuthor" :disabled="loading">Додати</button>
        </b-overlay>
      </div>
    </b-modal>

    <b-form-checkbox class="mb-3" :value="true" :unchecked-value="false" v-model="useSupervisor" @change="changeSupervisor">
      Під керівництвом (зазначити у випадку одноосібної публікації студента)
    </b-form-checkbox>
    <!--  -->
    <div class="form-group" v-if="useSupervisor">
      <label class="item-title">Керівник *</label>
      <div class="input-container authors">
        <multiselect
          v-model="publicationData.supervisor"
          @input="checkStudent"
          :options="authors"
          label="name"
          :searchable="true"
          placeholder="Пошук в базі даних сервісу"
          selectLabel="Натисніть щоб обрати"
          selectedLabel="Обрано"
          deselectLabel="Натисніть щоб видалити"
          :custom-label="nameWithInfo"
          :loading="loadingAuthors"
        >
          <span slot="noResult">По даному запиту результатів не знайдено</span>
          </multiselect>
      </div>
      <div class="error" v-if="$v.publicationData.supervisor.$error">
        Поле обов'язкове для заповнення
      </div>
    </div>
    <!--  -->
    <label>Автори:</label>
    <div v-for="(item, i) in publicationData.authors" :key="'author-'+i" class="form-group">
        <label>{{ i+1 }}. Прізвище, ім’я, по-батькові автора * 
          <b-icon :id="'tooltip-target-authors-'+i" icon="question-circle-fill" v-if="publicationData.authors[i].categ_1 == 1"></b-icon>
          <b-tooltip v-if="publicationData.authors[i].categ_1 == 1" :target="'tooltip-target-authors-'+i" triggers="hover">
            У випадку якщо студент являється співавтором, потрібно вказати наукового керівника студента
          </b-tooltip>
        </label>
        <b-row>
          <b-col>
            <multiselect
                :disabled="publicationData.whose_publication == 'my' && publicationData.authors[i].id == authUser.id"
                @input="checkStudent"
                v-model="publicationData.authors[i]"
                :searchable="true"
                :options="authors"
                selectLabel="Натисніть щоб обрати"
                selectedLabel="Обрано"
                deselectLabel='Натисніть щоб видалити'
                placeholder="Пошук в базі даних сервісу"
                :custom-label="nameWithInfo"
                :loading="loadingAuthors"
            >
                <span slot="noResult">Не знайшли автора? Зареєструйте нового <a href="#" v-b-modal.modal-1>автора з СумДУ</a> або <a href="#" v-b-modal.modal-2>іншої організації</a></span>
            </multiselect>
            <div class="error" v-if="$v.publicationData.authors.$each.$iter[i].name.$error">
                Поле обов'язкове для заповнення
            </div>
          </b-col>
          <b-col v-if="publicationData.authors[i].categ_1 == 1">
            <multiselect
                v-model="publicationData.authors[i].supervisor"
                :searchable="true"
                :options="studentSupervisors"
                selectLabel="Натисніть щоб обрати"
                selectedLabel="Обрано"
                deselectLabel='Натисніть щоб видалити'
                placeholder="Оберіть наукового керівника студента"
                :custom-label="nameWithInfo"
                :loading="loadingAuthors"
            >
                <span slot="noResult">Автора не знайдено</span>
            </multiselect>
            <div class="error" v-if="$v.publicationData.authors.$each.$iter[i].supervisor.$error">
                Поле обов'язкове для заповнення
            </div>
          </b-col>
          <b-col cols="1" v-if="(publicationData.whose_publication == 'my' && item.id != authUser.id) || publicationData.whose_publication != 'my'">
            <button class="button red block" @click="removeAuthor(i)">
              <b-icon icon="x"></b-icon>
            </button>
          </b-col>
        </b-row>
    </div>
    <div style="display: flex">
      <button class="button blue" @click="addAuthor">
          Додати автора <b-icon icon="person-plus-fill"></b-icon>
      </button>
      <div class="filler"></div>
      <button class="button transparent custom" v-b-modal.modal-1>
          <b-icon id="tooltip-target-author-1" icon="question-circle-fill"></b-icon> Зареєструвати автора з СумДУ
          <b-tooltip target="tooltip-target-author-1" triggers="hover">
            У випадку якщо автор працює, або навчається в СумДУ
          </b-tooltip>
      </button>
      <button class="button transparent ml-3 custom" v-b-modal.modal-2>
          <b-icon id="tooltip-target-author-2" icon="question-circle-fill"></b-icon> Зареєструвати автора іншої організації
          <b-tooltip target="tooltip-target-author-2" triggers="hover">
            У випадку якщо автор працює, або навчається в іншій організації
          </b-tooltip>
      </button>
    </div>
    <!--  -->
    <div class="form-group" v-show="publicationData.authors.length > 0">
      <label class="mt-3">Прізвища та ініціали авторів мовою оригіналу *</label>
      <input type="text" class="input" v-model="publicationData.initials">
      <div class="error" v-if="$v.publicationData.initials.$error">
          Поле обов'язкове для заповнення
      </div>
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
import Multiselect from 'vue-multiselect';
import {required, requiredIf} from "vuelidate/lib/validators";
import Country from "../Country.vue";
import divisions from '../../mixins/divisions.js';
import job from '../../mixins/job.js';

export default {
  mixins: [divisions, job],
  props: {
    publicationData: Object
  },
  components: {
    Multiselect,
    Country
  },
  data() {
    return {
      loadingAuthors: true,
      loading: false,
      findAuthor: false,
      categ: [
        {
            name: "Категорії",
            value: null
        },
        {
            name: "Студент",
            value: "categ1/2"
        },
        {
            name: "Аспірант",
            value: "categ1/4"
        },
        {
            name: "Співробітник",
            value: "categ2/2"
        },
        {
            name: "Викладач",
            value: "categ2/4"
        },
        {
            name: "Менеджер",
            value: "categ2/8"
        }
      ],
      selectCateg: null,
      loadingPersons: false,
      persons: [],
      authors: [],
      country: [],
      errorName: null,
      otherAuthorNames: [],
      names: [],
      defaultNewAuthorSSU: {},
      newAuthorSSU: {
        name: '',
        kod_div: '',
        name_div: '',
        categ_1: '',
        categ_2: '',
        job: '',
        country: ''
      },
      newAuthor: {
        job: '',
        name: '',
        country: null,
        h_index: '',
        scopus_autor_id: '',
        without_self_citations_scopus: '',
        without_self_citations_wos: '',
        scopus_researcher_id: '',
        orcid: '',
        forbes_fortune: 0,
        faculty_code: '',
        department_code: '',
        categ_1: '',
        job_type_id: null
      }
    }
  },
  mounted() {
      this.defaultNewAuthorSSU = Object.assign(this.defaultNewAuthorSSU, this.newAuthorSSU);
      if(this.publicationData.whose_publication == 'my') {
          this.publicationData.authors.push(this.authUser);
      }
      this.getAuthors();
      this.checkStudent();
      this.getDivisions();
  },
  computed: {
    authUser() {
      return this.$store.getters.authUser;
    },
    departmentsUser() {
      let departments = [];
      if(this.newAuthor.faculty_code) {
        departments = this.divisions.find(item => {
          return this.newAuthor.faculty_code == item.ID_DIV
        });
        if(departments) {
          departments = departments.departments;
        }
      } else {
        departments = [];
        this.newAuthor.department_code = "";
      }
      return departments;
    },
    useSupervisor: {
      get() {
        return this.publicationData.useSupervisor;
      },
      set(newValue) {
        this.publicationData.useSupervisor = newValue;
      }
    },
    checkSelfCitationsWos() {
      if(this.newAuthor.h_index) {
        return (this.newAuthor.without_self_citations_wos <= this.newAuthor.h_index) ? false : true;
      } else {
        return false;
      }
    },
    checkSelfCitationsScopus() {
      if(this.newAuthor.scopus_autor_id) {
        return (this.newAuthor.without_self_citations_scopus <= this.newAuthor.scopus_autor_id) ? false : true;
      } else {
        return false;
      }
    },
    studentSupervisors() {
      let supervisor = null;
      if(this.publicationData.supervisor) {
        supervisor = Object.assign({}, this.publicationData.supervisor);
        supervisor.$isDisabled = false;
      }
      return this.publicationData.authors.filter(author => author.categ_1 != 1).map(author => {
        author.$isDisabled = false;
        return author;
      }).concat(supervisor ? [supervisor] : [])
    }
  },
  watch: {
      useSupervisor(val) {
          if(this.publicationData.authors.length > 0 && (this.publicationData.authors.filter(item => item.categ_1 == 1).length == this.publicationData.authors.length)) {
              this.publicationData.useSupervisor = true;
          } else {
              this.publicationData.useSupervisor = val;
          }
      },
  },
  validations: {
    publicationData: {
      supervisor: {
        required: requiredIf ( function() {
          return this.useSupervisor;
        })
      },
      authors: {
        required,
        $each: {
          name: {
            required,
          },
          supervisor: {
            required: requiredIf(function(value) {
              return value.categ_1 == 1;
            })
          }
        }
      },
      initials: {
        required
      },
    },
    newAuthor: {
      country: {
        required: requiredIf(function() {
          return this.newAuthor.job_type_id != 3 && this.newAuthor.job_type_id != 6;
        })
      },
      job: {
        required: requiredIf(function() {
          return this.newAuthor.job_type_id == 1 || this.newAuthor.job_type_id == 2;
        })
      },
      job_type_id: {
        required
      },
      name: {
        required
      },
      forbes_fortune: {
        required: requiredIf(function() {
          return this.newAuthor.job_type_id == 2;
        })
      },
      orcid: {
        validFormat: val => val ? /(\d{4})\-(\d{4})\-(\d{4})\-(\d{4})/.test(val) : true, 
      },
      scopus_researcher_id: {
        validFormat: val => val ? /(\w+)\-(\d{4})\-(\d{4})/.test(val) : true, 
      }
    },
  },
  methods: {
    nextStep() {
        this.$v.publicationData.$touch();
        if (this.$v.publicationData.$invalid) {
            swal.fire({
                icon: 'error',
                title: 'Помилка',
                text: "Не всі поля заповнено!"
            });
            return;
        }
        if(this.publicationData.authors.length == 0) {
            swal.fire({
                icon: 'error',
                title: 'Помилка',
                text: "Повинен бути хоча б один автор!"
            });
            return;
        }
        if (this.publicationData.useSupervisor && this.publicationData.authors.filter(item => item && item.id == this.publicationData.supervisor.id).length) {
            swal.fire({
                icon: 'error',
                title: 'Помилка',
                text: "Керівник не може бути співавтором!"
            });
            return;
        }
        this.$emit('getData', 2);
    },
    prevStep() {
        this.$emit('prevStep');
    },
    changeSupervisor() {
        if(!this.useSupervisor) {
            this.publicationData.supervisor = null;
        }
        this.publicationData.authors = [];
        this.checkStudent();
    },
    checkStudent() {
        this.publicationData.authors = this.publicationData.authors.filter(item => {
          item != null;
          return item;
        })
        if(this.publicationData.authors.length > 0 && (this.publicationData.authors.filter(item => item && item.categ_1 == 1).length == this.publicationData.authors.length)) {
          this.publicationData.useSupervisor = true;
        }
        this.authors = this.authors.map(item => {
          if(this.publicationData.authors.find(author => author.id == item.id) || (this.publicationData.supervisor && this.publicationData.supervisor.id == item.id)) {
            item.$isDisabled = true;
          } else {
            item.$isDisabled = false;
          }
          return item;
        });
    },
    // задає місце роботи для новго автора не з СумДУ
    setJobType() {
        if(this.newAuthor.job_type_id == 4 || this.newAuthor.job_type_id == 3) {
            this.newAuthor.job = null;
            this.newAuthor.country = null;
            this.newAuthor.h_index = "";
            this.newAuthor.scopus_autor_id = "";
            this.newAuthor.scopus_researcher_id = "";
            this.newAuthor.orcid = "";
            this.newAuthor.faculty_code = "";
            this.newAuthor.department_code = "";
            this.newAuthor.categ_1 = "";
            if(this.newAuthor.job_type_id == 3) {
                this.newAuthor.country = null;
                this.newAuthor.categ_1 = 3;
            }
        } else {
            this.newAuthor.job = null;
            this.newAuthor.categ_1 = "";
        }
    },
    // пошук схожих імен авторів не з СумДУ
    findNames() {
        if(this.newAuthor.name.length > 3) {
            this.names = this.otherAuthorNames.filter(item => {
                return item.name.toLowerCase().indexOf(this.newAuthor.name.toLowerCase()) + 1
            })
        } else {
            this.names = [];
        }
    },
    // форматування відображення списку авторів зареєстрованих на сайті (новий автор, керівник)
    nameWithInfo({name, faculty, department, academic_code, job, categ_1, categ_2}) {
        if(name) {
            if(categ_1 == 1) {
                return `${name} - ${academic_code}`
            } else if(categ_1 == 2 || categ_2 == 1 || categ_2 == 2 || categ_2 == 3) {
                if(department) {
                    return `${name} - ${department}`
                } else {
                    return `${name} - ${job ? job : 'Не працює'}`
                }
            } else if(categ_1 ==  3) {
                return `${name} - випускник`
            } else {
                return `${name} - ${job ? job : 'Не працює'}`
            }
        } else {
            return "Пошук в базі даних сервісу"
        }
    },
    // форматування відображення списку авторів із СумДУ в формі додання нового автора СумДУ
    nameWithInfoSSU({name, name_div}) {
        if(name) {
            if(name_div) {
                return `${name} - ${name_div}`
            } else {
                return `${name}`
            }
        } else {
            return "Пошук в базі даних сервісу"
        }
    },
    // додання автора в список авторів публікації
    addAuthor() {
        if(!this.publicationData.authors.find(item => item.name == "")) {
            this.publicationData.authors.push({
                name: '',
                faculty: "",
                academic_code: ""
            })
        }
    },
    // видалення автора із списку авторів публікації
    removeAuthor(i) {
        if(this.publicationData.authors.length > 1) {
            this.publicationData.authors.splice(i, 1);
            this.checkStudent();
        } else {
            swal.fire({
                icon: 'error',
                title: 'Помилка',
                text: "Повинен бути хоча б один автор!"
            });
        }
    },
    //getters API
    // імена користувачів не з СумДУ
    getOtherAuthorNames() {
        axios.get(`/api/others-authors-name`).then(response => {
            this.names = [];
            this.otherAuthorNames = response.data;
        })
    },
    // всі користувачів зареєстровані на сайті
    getAuthors() {
        axios.get(`/api/authors-all`).then(response => {
            this.authors = response.data;
            this.loadingAuthors = false;
        })
    },
    // список корисувачів з API ASU по обраній категорії
    getPersonAPI() {
        if(this.selectCateg) {
            this.loadingPersons = true;
            this.persons = [];
            axios.get(`/api/persons/${this.selectCateg}`).then(response => {
                this.persons = response.data;

            }).then(result => {
                this.loadingPersons = false;
            }).catch(() => {
                this.loadingPersons = false;
            })
        }
    },
    // posts API
    // додання новго автора з СумДУ
    addNewAuthorSSU() {
        this.loading = true;
        axios.post('/api/author-ssu', this.newAuthorSSU)
        .then((response) => {
            if(response.data.status == 'ok') {
                this.$bvModal.hide('modal-1');
                this.selectCateg = null;
                this.persons = [];
                this.getAuthors();
                this.newAuthorSSU = this.defaultNewAuthorSSU;
                this.publicationData.authors.map(item => {
                    if(item.name == "") {
                        let index = this.publicationData.authors.indexOf(item);
                        this.publicationData.authors.splice(index, 1);
                    }
                })
                this.publicationData.authors.push(response.data.user);
                this.checkStudent();
                swal.fire({
                    icon: 'success',
                    title: 'Автора успішно додано!',
                });
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Помилка',
                    text: "Автор вже зареєстрований в системі"
                });
            }
            this.loading = false;
        }).catch(() => {
            this.loading = false;
        })
    },
    // додання нового автора не з СумДУ
    addNewAuthor() {
        this.$v.$touch();
        if (this.$v.newAuthor.$invalid) {
            swal.fire({
                icon: 'error',
                title: 'Не всі поля заповнено!'
            });
            return;
        }
        var findAuthor = this.otherAuthorNames.find(item => item.name.toLowerCase() == this.newAuthor.name.toLowerCase() && (item.job && item.job.toLowerCase() == this.newAuthor.job.toLowerCase()));
        if(findAuthor) {
            this.errorName = findAuthor;
            return;
        } else {
            this.errorName = null;
        }
        if(this.newAuthor.job_type_id == 3) {
            this.newAuthor.country = "Україна";
        }
        if(this.newAuthor.job_type_id == 6) {
            this.newAuthor.country = "Україна";
        }
        this.loading = true;
        axios.post('/api/author', this.newAuthor)
        .then((response) => {
            if(response.data.status == 'ok') {
                this.$bvModal.hide('modal-2');
                this.getAuthors();
                this.newAuthor = {
                    job: '',
                    name: '',
                    country: null,
                    h_index: '',
                    scopus_autor_id: '',
                    scopus_researcher_id: '',
                    orcid: '',
                    forbes_fortune: 0,
                    job_type_id: null
                };
                this.publicationData.authors.map(item => {
                    if(item.name == "") {
                        let index = this.publicationData.authors.indexOf(item);
                        this.publicationData.authors.splice(index, 1);
                    }
                })
                this.publicationData.authors.push(response.data.user);
                this.$v.$reset();
                swal.fire({
                    icon: 'success',
                    title: 'Автора успішно додано!',
                });
                this.loading = false;
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Помилка',
                    text: "Автор вже зареєстрований в системі"
                });
                this.loading = false;
            }
        }).catch((error) => {
            swal.fire({
                icon: 'error',
                title: 'Помилка',
                text: error.message
            });
            this.loading = false;
        });
    },
  }
}
</script>
<style lang="css" scoped>
  button.red {
    font-size: 20px !important;
  }
  .custom {
    font-size: 14px !important;
  }
</style>