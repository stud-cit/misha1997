<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Перегляд публікацій 
            <span v-if="authUser.roles_id == 3 && divisions.find(item => item.ID_DIV == authUser.faculty_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.faculty_code).NAME_DIV }}</span>
            <span v-if="authUser.roles_id == 2 && divisions.find(item => item.ID_DIV == authUser.department_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.department_code).NAME_DIV }}</span>
        </h1>

        <!-- exports-->
        <div class="exports">
            <export-rating v-if="authUser.roles_id != 1" :publicationTypes="publicationTypes" :years="years" :countries="country" class="export-block"></export-rating>
            <export-publications class="export-block" :exportList="exportPublication"></export-publications>
        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-row" v-if="authUser.roles_id != 2">
                    <div class="form-group col" v-if="authUser.roles_id != 3">
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
                        <div class="input-container">
                            <select  v-model="filters.country">
                                <option value=""></option>
                                <option v-for="(item, index) in country" :value="item.name" :key="index">{{item.name}}</option>
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
                <button type="button" class="export-button" @click="clearFilter">Очистити фільтр</button>
            </form>
            <Table 
                @select="selectItem"
                :publications="filteredList" 
                :authUser="authUser" 
                :loading="loading"
                :selectPublications="selectPublications"
            ></Table>
            <div class="step-button-group">
                <back-button></back-button>
                <delete-button v-if="authUser.roles_id == 4" @click.native="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import divisions from '../mixins/divisions';
    import years from '../mixins/years';
    import country from '../mixins/country';

    import ExportRating from "./Exports/ExportRating";
    import ExportPublications from "./Exports/ExportPublications";
    import Table from "../Tables/Publications";
    import BackButton from "../Buttons/Back";
    import DeleteButton from "../Buttons/Delete";
    import XLSX from 'xlsx';
    export default {
        mixins: [years, country, divisions],
        data() {
            return {
                names: [],
                publicationNames: [],
                data: [],
                publicationTypes: [],
                exportData: {},
                exportPublication: [],
                loading: true,
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
            DeleteButton
        },
        mounted() {
            this.getData();
            this.getTypePublications();
            this.getNamesPublications();
            if(this.$store.getters.getFilterPublications) {
                this.filters = this.$store.getters.getFilterPublications;
            }
        },
        methods: {
            // getters
            // всі публікації
            getData() {
                axios.get('/api/publications').then(response => {
                    this.data = response.data.map(element => {
                        element.faculty_code = element.authors.map(item => item.author.faculty_code).join();
                        element.department_code = element.authors.map(item => item.author.department_code).join();
                        element.authors_f = element.authors.map(item => item.author.name).join();
                        return element;
                    });
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
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
            // видалити обрані публікації
            deletePublications() {
                swal({
                    title: "Бажаєте видалити?",
                    text: "Після видалення ви не зможете відновити дані!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.post('/api/delete-publications', {
                            publications: this.selectPublications,
                            user: this.authUser
                        })
                        .then(() => {
                            this.selectPublications = [];
                            this.getData();
                            swal("Публікації успішно видалені", {
                                icon: "success",
                            });
                        });
                    }
                });
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
                this.names = this.publicationNames.filter(item => {
                    return item.indexOf(this.filters.title) + 1
                })
            },
            // форматування назви пулікації для фільтру
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            },
            clearFilter() {
                this.$store.dispatch('filterPublications');
                this.filters.title = '';
                this.filters.authors_f = '';
                this.filters.science_type_id = '';
                this.filters.year = '';
                this.filters.country = '';
                this.filters.publication_type_id = '';
                this.filters.faculty_code = '';
                this.filters.department_code = '';
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
                this.$store.dispatch('saveFilterPublications', this.filters);
                return this.exportPublication;
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

