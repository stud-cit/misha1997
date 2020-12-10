<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Перегляд публікацій
            <span v-if="authUser.roles_id == 3 && divisions.find(item => item.ID_DIV == authUser.faculty_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.faculty_code).NAME_DIV }}</span>
            <span v-if="authUser.roles_id == 2 && divisions.find(item => item.ID_DIV == authUser.department_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.department_code).NAME_DIV }}</span>
        </h1>

        <!-- exports-->
        <div class="exports">
            <export-rating v-if="authUser.roles_id != 1" :years="years" class="export-block"></export-rating>
            <export-publications class="export-block" :exportList="data" :loading="loading"></export-publications>
        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-row" v-show="authUser.roles_id != 2">
                    <div class="form-group col-lg-6" v-show="authUser.roles_id != 3">
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
                    <div class="form-group col-lg-6">
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
                <div class="form-group">
                    <label>ПІБ автора</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.authors_f">
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
                        <div class="input-container">
                            <select v-model="filters.year">
                                <option value=""></option>
                                <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>
                            </select>
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
                <SearchButton 
                    @click.native="getData(); loadingSearch = true" 
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
            <Table
                @select="selectItem"
                :publications="data"
                :authUser="authUser"
                :loading="loading"
                :selectPublications="selectPublications"
            ></Table>
            <div class="step-button-group">
                <back-button></back-button>
                <delete-button v-if="checkAccess" @click.native="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import divisions from '../mixins/divisions';
    import years from '../mixins/years';

    import ExportRating from "./Exports/ExportRating";
    import ExportPublications from "./Exports/ExportPublications";
    import Table from "../Tables/Publications";
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
                    year: '',
                    country: '',
                    publication_type_id: '',
                    faculty_code: '',
                    department_code: ''
                }
            };
        },
        components: {
            ExportRating,
            ExportPublications,
            Table,
            BackButton,
            SearchButton,
            DeleteButton,
            Country,
            PublicationTypes
        },
        created() {
            if(this.$store.getters.getFilterPublications) {
                this.filters = this.$store.getters.getFilterPublications;
            }
            this.getData();
            this.getNamesPublications();
        },
        methods: {
            // getters
            // всі публікації
            getData() {
                this.loading = true;
                this.$store.dispatch('saveFilterPublications', this.filters);
                axios.get('/api/publications', {
                    params: {
                        title: this.filters.title,
                        authors_f: this.filters.authors_f,
                        science_type_id: this.filters.science_type_id,
                        year: this.filters.year,
                        country: this.filters.country,
                        publication_type_id: this.filters.publication_type_id,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code
                    }
                }).then(response => {
                    this.data = response.data;
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
                            this.getData();
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
                this.filters.year = '';
                this.filters.country = '';
                this.filters.publication_type_id = '';
                this.filters.faculty_code = '';
                this.filters.department_code = '';
                this.getData();
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
                } else if(this.$store.getters.accessMode == 'open' && this.authUser.roles_id != 1) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
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
        margin-top: 70px;
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

