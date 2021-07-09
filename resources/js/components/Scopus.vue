<template>
    <div class="container page-content general-block">

        <div class="exports mb-3">
          <button class="export-button" @click="getDataScopus()">
              <span
                  class="spinner-border spinner-border-sm"
                  style="width: 19px; height: 19px; margin-right: 10px"
                  role="status"
                  aria-hidden="true"
                  v-if="loadingImport"
              ></span>
              <img v-else src="/img/download.png"> Отримати публікації Scopus
          </button>
          <div class="text-center mt-2" v-if="last_date != null">
            Дата останнього оновлення: {{ last_date.created_at }}
          </div>
        </div>

        <div class="main-content">

            <form class="search-block">
                <div class="form-row" v-show="authUser.roles_id != 2">
                    <div class="form-group col-lg" v-show="authUser.roles_id != 3">
                        <label>Інститут / факультет</label>
                        <div class="input-container">
                            <select v-model="filters.faculty_code" @change="getDepartments">
                                <option value=""></option>
                                <option
                                    v-for="(item, index) in divisions"
                                    :key="index"
                                    :value="item.ID_DIV"
                                >{{item.NAME_DIV}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg">
                        <label>Кафедра</label>
                        <div class="input-container">
                            <select v-model="filters.department_code">
                                <option value=""></option>
                                <option
                                    v-for="(item, index) in departments"
                                    :key="index"
                                    :value="item.ID_DIV"
                                >{{item.NAME_DIV}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Назва публікації</label>
                    <div class="input-container">
                        <input v-model="filters.title" type="text" list="names" @input="findNames">
                        <datalist id="names">
                            <option v-for="(item, index) in names" :key="index" :value="item">{{item}}</option>
                        </datalist>
                    </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-lg">
                      <label>ПІБ автора</label>
                      <div class="input-container">
                          <input type="text" v-model="filters.authors_f">
                      </div>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-lg-6">
                      <label>Вид публікації</label>
                      <PublicationTypes :data="filters"></PublicationTypes>
                  </div>
                  <div class="form-group col-lg-6">
                    <label>Рік видання</label>
                      <div class="input-container multiselect">
                        <multiselect
                            v-model="filters.years"
                            placeholder=""
                            selectLabel="Натисніть для вибору"
                            selectedLabel="Вибрано"
                            deselectLabel="Натисніть для видалення"
                            :options="years"
                            :multiple="true"
                            :taggable="true"
                        ></multiselect>
                      </div>
                  </div>
                  <div class="form-group col-lg-6">
                      <label>Верифікація</label>
                      <div class="input-container">
                          <select v-model="filters.verification">
                              <option value=""></option>
                              <option value="1">Так</option>
                              <option value="2">Ні</option>
                          </select>
                      </div>
                  </div>
                </div>
                <SearchButton
                    @click.native="getData(1); loadingSearch = true;"
                    :disabled="loading || loadingSearch || loadingClear"
                    :loading="loadingSearch"
                    title="Пошук"
                ></SearchButton>
                <SearchButton
                    @click.native="clearFilter"
                    :disabled="loading || loadingSearch || loadingClear"
                    :loading="loadingClear"
                    title="Очистити фільтр"
                ></SearchButton>
            </form>

            <div class="row my-4" id="header-table">
                <div class="col">
                    <select class="form-control w-50 ml-2" id="sizeTable" v-model="pagination.perPage" @change="getData(1)">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="250">250</option>
                        <option :value="500">500</option>
                        <option :value="countPublications">Всі</option>
                    </select>
                </div>
                <div class="col text-right">
                    <paginate
                        class="paginate-top"
                        v-model="pagination.currentPage"
                        :page-count="Math.ceil(countPublications / pagination.perPage)"
                        @click.native="scrollHeader()"

                        prev-text="<"
                        next-text=">"

                        container-class="pagination"
                        page-class="page-item"
                        page-link-class="page-link"
                        prev-class="page-link"
                        next-class="page-link">
                    </paginate>
                </div>
            </div>
            <div class="table-responsive text-center table-list">
                <table :class="['table', 'table-bordered', loading ? 'opacityTable' : '']">
                    <thead>
                        <tr>
                            <td colspan="7" class="bg-white text-left pb-3 pt-0">Всього публікацій: {{countPublications}}</td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Вид публікації</th>
                            <th scope="col">ПІБ автора/співавторів</th>
                            <th scope="col">Назва публікації</th>
                            <th scope="col">Рік видання</th>
                            <th scope="col">Верифікація</th>
                            <th scope="col">Дата занесення</th>
                            <th scope="col" v-if="checkAccess">Редагувати</th>
                            <th scope="col" v-if="checkAccess">Обрати</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td scope="row">{{ pagination.firstItem + index }}</td>
                            <td>{{ item.publication_type.title }}</td>
                            <td>
                                <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                    <a :href="'/user/'+author.author.id">{{author.author.name}} </a>
                                </span>
                            </td>
                            <td><a :href="'/publications/'+item.id"> {{ item.title }} </a> </td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.verification ? 'Так' : 'Ні' }}</td>
                            <td>{{ item.created_at }}</td>
                            <td v-if="checkAccess">
                                <a :href="'/publications/edit/'+item.id"><i class="fa fa-edit fa-2x"></i></a>
                            </td>
                            <td class="icons" v-if="checkAccess">
                                <input
                                    type="checkbox"
                                    :checked="selectPublications.indexOf(item) != -1 ? true : false"
                                    @click="selectItem(item)"
                                >
                            </td>
                        </tr>
                        <tr v-if="countPublications != 0">
                            <td colspan="12" class="text-left">Всього публікацій: {{ countPublications }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="countPublications == 0 && !loading">
                    Публікації відсутні
                </div>
            </div>
            <paginate
                class="mt-4"
                v-model="pagination.currentPage"
                :page-count="Math.ceil(countPublications / pagination.perPage)"
                @click.native="scrollHeader()"

                prev-text="<"
                next-text=">"

                container-class="pagination"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>

            <div class="step-button-group">
                <back-button></back-button>
                <delete-button v-if="checkAccess" @click.native="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import BackButton from "./Buttons/Back";
    import DeleteButton from "./Buttons/Delete";
    import SearchButton from "./Buttons/SearchButton";

    import Multiselect from 'vue-multiselect';
    import divisions from './mixins/divisions';
    import years from './mixins/years';
    import PublicationTypes from "./Forms/PublicationTypes";
    export default {
        mixins: [years, divisions],
        components: {
            BackButton,
            DeleteButton,
            PublicationTypes,
            Multiselect,
            SearchButton
        },
        data() {
            return {
              countPublications: 0,
              loading: true,
              loadingSearch: false,
              loadingClear: false,
              loadingImport: false,
              selectPublications: [],
              names: [],
              data: [],
              last_date: {
                created_at: ""
              },
              filters: {
                  title: '',
                  authors_f: '',
                  science_type_id: '',
                  years: [],
                  country: [],
                  publication_type_id: '',
                  faculty_code: '',
                  department_code: '',
                  hasSupervisor: false,
                  notPreviousYear: false,
                  categ_users: [],
                  notThisYear: false,
                  scopus_add_status: "",
                  verification: ""
              },
              pagination: {
                  currentPage: 1,
                  perPage: 10,
                  firstItem: 1
              },
            };
        },
        created() {
            this.getData(this.pagination.currentPage);
            this.getNamesPublications();
        },
        computed: {
            // отримуємо авторизованого користувача із store
            authUser() {
                return this.$store.getters.authUser
            },
            checkAccess() {
                if(this.authUser.roles_id == 4) {
                    return true;
                } else if((this.$store.getters.accessMode == 'open' && this.authUser.roles_id == 1) || this.authUser.roles_id != 5) {
                    return true;
                } else {
                    return false;
                }
            },
        },
        methods: {
            // всі назви пцблікацій
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));
                })
            },
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            getDataScopus() {
              this.loadingImport = true;
                axios.get('/api/publications-scopus').then(response => {
                  this.loadingImport = false;
                  this.getData(1);
                }).catch(() => {
                    this.loadingImport = false;
                })
            },
            getData(page) {
                this.loading = true;
                var params = Object.assign(this.filters, {
                  status_id: 1,
                  size: this.pagination.perPage,
                  page,
                  scopus: true
                });
                axios.get('/api/publications', { params }).then(response => {
                    this.countPublications = response.data.count;
                    this.pagination.currentPage = response.data.currentPage;
                    this.pagination.firstItem = response.data.firstItem;
                    this.data = response.data.publications.data;
                    this.last_date = response.data.last_date_export
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                }).catch(() => {
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                })
            },
            // видалити обрані публікації
            deletePublications() {
                swal.fire({
                    title: 'Бажаєте видалити?',
                    text: "Після видалення ви не зможете відновити дані!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Видалити',
                    cancelButtonText: 'Відміна',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post('/api/delete-publications', {
                            publications: this.selectPublications,
                            user: this.authUser
                        })
                        .then(() => {
                            this.selectPublications = [];
                            this.getData(this.pagination.currentPage);
                            swal.fire("Публікації успішно видалено");
                        });
                    }
                })
            },
            // пошук публікації по назві
            findNames() {
                if(this.filters.title.length > 3) {
                    this.names = this.publicationNames.filter(item => {
                        return item.indexOf(this.filters.title) + 1
                    })
                } else {
                    this.names = [];
                }
            },
            // обрані публікації
            selectItem(item) {
                if(this.selectPublications.indexOf(item) == -1) {
                    this.selectPublications.push(item);
                } else {
                    this.selectPublications.splice(this.selectPublications.indexOf(item), 1);
                }
            },
            // форматування назви пулікації для фільтру
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            },
            clearFilter() {
                this.loadingClear = true;
                this.$store.dispatch('clearFilterPublications');
                this.filters.title = '';
                this.filters.authors_f = '';
                this.filters.science_type_id = '';
                this.filters.years = [];
                this.filters.country = [];
                this.filters.publication_type_id = '';
                this.filters.faculty_code = '';
                this.filters.department_code = '';
                this.filters.hasSupervisor = false;
                this.filters.notPreviousYear = false;
                this.filters.notThisYear = false;
                this.filters.categ_users = [];
                this.filters.verification = "";
                this.getData(1);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .opacityTable {
        opacity: 0.5;
    }
    .table-list{
        margin-top: 0px;
    }
    .paginate-top.pagination {
        margin: 0;
        float: right;
    }
    .checkbox {
        padding: 0;
    }
    .checkbox input[type=checkbox] {
        width: 20px;
        height: 20px;
        margin: 0;
    }
    .checkbox label {
        margin-left: 40px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .exports{
        display: grid;
        justify-content: center;
    }
</style>

