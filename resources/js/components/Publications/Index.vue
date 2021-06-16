<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Перегляд публікацій
            <span v-if="authUser.roles_id == 3"> - {{ authUser.faculty }}</span>
            <span v-if="authUser.roles_id == 2"> - {{ authUser.department }}</span>
        </h1>

        <!-- exports-->
        <div class="exports">
            <export-rating v-if="authUser.roles_id != 1" :years="years" class="export-block"></export-rating>
            <export-publications class="export-block" :filters="filters" :countPublications="countPublications"></export-publications>
        </div>
        <!---->
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
                  <div class="form-group col-lg">
                      <label>Посада</label>
                      <div class="input-container multiselect">
                        <multiselect
                            v-model="filters.categ_users"
                            placeholder=""
                            :options="categUsers"
                            :multiple="true"
                            :taggable="true"
                        ></multiselect>
                      </div>
                  </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label>БД Scopus/WoS</label>
                        <div class="input-container">
                            <select v-model="filters.science_type_id">
                                <option value=""></option>
                                <option value="1">Scopus</option>
                                <option value="2">WoS</option>
                                <option value="3">Scopus та WoS</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
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
                    <div class="form-group col-lg-4">
                        <label>Країна видання</label>
                        <Country :data="filters"></Country>
                    </div>
                </div>
                <div class="form-group">
                    <label>Вид публікації</label>
                    <PublicationTypes :data="filters"></PublicationTypes>
                </div>
                <div class="form-group checkbox col-lg">
                    <input v-model="filters.hasSupervisor" type="checkbox" class="form-check-input" id="hasSupervisor">
                    <label class="form-check-label" for="hasSupervisor">Під керівництвом</label>
                </div>
                <div class="form-group checkbox col-lg">
                    <input v-model="filters.notPreviousYear" type="checkbox" class="form-check-input" id="notPreviousYear">
                    <label class="form-check-label" for="notPreviousYear">Публікації які не враховані в рейтингу попереднього року</label>
                </div>
                <div class="form-group checkbox col-lg">
                    <input v-model="filters.notThisYear" type="checkbox" class="form-check-input" id="notThisYear">
                    <label class="form-check-label" for="notThisYear">Публікації які не враховані в рейтингу цього року</label>
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
                            <td colspan="8" class="bg-white text-left pb-3 pt-0">Всього публікацій: {{countPublications}}</td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Вид публікації</th>
                            <th scope="col">ПІБ автора/співавторів</th>
                            <th scope="col">Назва публікації</th>
                            <th scope="col">Рік видання</th>
                            <th scope="col">БД Scopus/WoS</th>
                            <th scope="col">Науковий керівник</th>
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
                                    <a v-if="!author.supervisor" :href="'/user/'+author.author.id">{{author.author.name}} </a>
                                </span>
                            </td>
                            <td><a :href="'/publications/'+item.id"> {{ item.title }} </a> </td>
                            <td>{{ item.year}}</td>
                            <td>{{ item.science_type ? item.science_type.type : '' }}</td>
                            <td>
                                <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                    <a v-if="author.supervisor" :href="'/user/'+author.author.id">{{author.author.name}}</a>
                                </span>
                            </td>
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
    import Multiselect from 'vue-multiselect';
    import divisions from '../mixins/divisions';
    import years from '../mixins/years';

    import ExportRating from "./Exports/ExportRating";
    import ExportPublications from "./Exports/ExportPublications";
    import BackButton from "../Buttons/Back";
    import SearchButton from "../Buttons/SearchButton";
    import DeleteButton from "../Buttons/Delete";
    import Country from "../Forms/Country";
    import PublicationTypes from "../Forms/PublicationTypes";
    import XLSX from 'xlsx';
    export default {
        mixins: [years, divisions],
        data() {
            return {
                countPublications: 0,
                names: [],
                publicationNames: [],
                data: [],
                departments: [],
                exportData: {},
                exportPublication: [],
                loading: true,
                loadingSearch: false,
                loadingClear: false,
                selectPublications: [],
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
                    notThisYear: false
                },
                categUsers: [
                  "Аспіранти", 
                  "Викладачі",
                  "Докторанти", 
                  "Зовнішні співавтори", 
                  "Іноземці", 
                  "Менеджери", 
                  "Співробітники", 
                  "Студенти", 
                  "СумДУ", 
                  "СумДУ (не працює)", 
                  "5 або більше публікацій у періодичних виданнях Scopus та/або WoS"
                ],
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    firstItem: 1
                },
            };
        },
        components: {
            ExportRating,
            ExportPublications,
            BackButton,
            SearchButton,
            DeleteButton,
            Country,
            PublicationTypes,
            Multiselect
        },
        created() {
            if(this.$store.getters.getFilterPublications) {
                this.filters = this.$store.getters.getFilterPublications;
            }
            this.getData(this.pagination.currentPage);
            this.getNamesPublications();
        },
        methods: {
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            // getters
            // всі публікації
            getData(page) {
                this.loading = true;
                this.$store.dispatch('saveFilterPublications', this.filters);
                axios.get('/api/publications', {
                    params: {
                        size: this.pagination.perPage,
                        page: page,
                        title: this.filters.title,
                        authors_f: this.filters.authors_f,
                        science_type_id: this.filters.science_type_id,
                        years: this.filters.years,
                        country: this.filters.country,
                        publication_type_id: this.filters.publication_type_id,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        hasSupervisor: this.filters.hasSupervisor,
                        notPreviousYear: this.filters.notPreviousYear,
                        notThisYear: this.filters.notThisYear,
                        categ_users: this.filters.categ_users
                    }
                }).then(response => {
                    this.countPublications = response.data.count;
                    this.pagination.currentPage = response.data.currentPage;
                    this.pagination.firstItem = response.data.firstItem;
                    this.data = response.data.publications.data;
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                }).catch(() => {
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                })
            },
            // всі назви пцблікацій
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));
                })
            },

            // methods
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
            // обрані публікації
            selectItem(item) {
                if(this.selectPublications.indexOf(item) == -1) {
                    this.selectPublications.push(item);
                } else {
                    this.selectPublications.splice(this.selectPublications.indexOf(item), 1);
                }
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
                this.getData(1);
            }
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
        }
    }
</script>

<style lang="scss" scoped>
    .opacityTable {
        opacity: 0.5;
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
    .fa-edit {
        cursor: pointer;
    }
    .search-block{
        margin-top: 20px;
    }
    .table-list{
        margin-top: 0px;
    }
    .exports{
        display: grid;
        justify-content: center;
        margin-top: 50px;
        .export-block{
            display: grid;
            margin-bottom: 20px;
        }
    }
    @media (max-width: 575px){
        .search-block{
            margin-top: 15px;
        }
        .table-list{
            margin-top: 35px;
        }
        .exports{
            margin-top: 25px;
        }
    }
</style>

