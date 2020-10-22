<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Перегляд публікацій</h1>

        <!-- exports-->
        <div class="exports">
            <export-rating v-if="authUser.roles_id == 4" :publicationTypes="publicationTypes" :years="years" :countries="countries" class="export-block"></export-rating>
            <export-publications class="export-block" :exportList="exportPublication"></export-publications>
        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-row">
                    <div class="form-group col">
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
                    <div class="form-group col">
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
                    <label>Прізвище та ініціали автора</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.initials">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label>БД Scopus\WoS</label>
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
                        <div class="input-container">
                            <select  v-model="filters.country">
                                <option value=""></option>
                                <option v-for="(item, index) in countries" :value="item.name" :key="index">{{item.name}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label >Вид публікації</label>
                    <div class="input-container">
                        <select v-model="filters.publication_type_id">
                            <option value=""></option>
                            <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>
                        </select>
                    </div>
                </div>
            </form>
            <Table 
                :publications="filteredList" 
                :authUser="authUser" 
                :loading="loading"
            ></Table>
        </div>
    </div>
</template>

<script>
    import ExportRating from "./Exports/ExportRating";
    import ExportPublications from "./Exports/ExportPublications";
    import Table from "../Tables/Publications";
    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                departments: [],
                divisions: [],
                names: [],
                publicationNames: [],
                data: [],
                countries: [],
                publicationTypes: [],
                exportData: {},
                exportPublication: [],
                loading: false,
                filters: {
                    title: '',
                    initials: '',
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
            Table
        },
        mounted() {
            this.getData();
            this.getCountry();
            this.getTypePublications();
            this.getNamesPublications();
            this.getDivisions();
        },
        methods: {
            // getters
            // всі факультети, кафедри
            getDepartments() {
                this.departments = this.divisions.find(item => {
                    return this.filters.faculty_code == item.ID_DIV
                }).departments;
            },
            // всі публікації
            getData() {
                axios.get('/api/publications').then(response => {
                    this.data = response.data.map(element => {
                        element.faculty_code = element.authors.map(item => item.author.faculty_code).join();
                        element.department_code = element.authors.map(item => item.author.department_code).join();
                        return element;
                    });
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            },
            // всі країни
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.countries = response.data;
                })
            },
            // всі типи пблікацій
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            // всі назви пцблікацій
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));
                })
            },

            // methods
            // сортування кафедр по факультету
            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },
            // пошук публікації по назві
            findNames() {
                this.names = this.publicationNames.filter(item => {
                    return item.indexOf(this.filters.title) + 1
                })
            },
            // форматування назви пулікації для фільтру
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }
        },
        computed: {
            // отримуємо авторизованого користувача із store
            authUser() {
                return this.$store.getters.authUser
            },
            // фільтр публікацій
            filteredList() {
                this.exportPublication = this.data.filter(item => {
                    let result = 1;
                    let keys = Object.keys(this.filters);
                    let values = Object.values(this.filters);
                    for(let i = 0; i < keys.length; i++){
                        if(values[i]) {
                            if (item[keys[i]] && keys[i] != 'publication_type_id') {
                                result = result && item[keys[i]].toString().toLowerCase().includes(values[i].toString().toLowerCase());
                            } else if(keys[i] == 'publication_type_id') {
                                result = result && item[keys[i]].toString() == values[i].toString();
                            } else {
                                result = 0;
                            }
                        }
                    }
                    return result;
                });
                return this.exportPublication;
            },
            // список років
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: 10}, (value, index) => year - index);
            }
        }
    }
</script>

<style lang="scss" scoped>
    .fa-edit {
        cursor: pointer;
    }
    .search-block{
        margin-top: 60px;
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

