<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Мої публікації</h1>
        <div class="exports">
            <export-publications class="export-block" :exportList="exportPublication"></export-publications>
        </div>
        <div class="main-content">
            <form class="search-block">
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
                    <label >ПІБ автора</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.authors_f">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label>БД Scopus/WoS</label>
                        <div class="input-container">
                            <select  v-model="filters.science_type_id">
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
                            <select  v-model="filters.year">
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
                    <div class="input-container">
                        <select  v-model="filters.publication_type_id">
                            <option value=""></option>
                            <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="export-button" style="display: inline-block" @click="getData()">Пошук</button>
                <button type="button" class="export-button" style="display: inline-block" @click="clearFilter">Очистити фільтр</button>
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
                <delete-button v-if="access == 'open' || authUser.roles_id == 4" @click.native="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import years from '../mixins/years';
    import country from '../mixins/country';

    import ExportPublications from "./Exports/ExportPublications";
    import Table from "../Tables/Publications";
    import BackButton from "../Buttons/Back";
    import DeleteButton from "../Buttons/Delete";
    import Country from "../Forms/Country";
    export default {
        mixins: [years, country],
        data() {
            return {
                names: [],
                publicationNames: [],
                selectPublications: [],
                exportPublication: [],
                loading: true,
                data: [],
                publicationTypes: [],
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
            ExportPublications,
            Table,
            BackButton,
            DeleteButton,
            Country
        },
        created() {
            if(this.$store.getters.getFilterPublications) {
                this.filters = this.$store.getters.getFilterPublications;
            }
            this.getData();
            this.getTypePublications();
            this.getNamesPublications();
        },
        methods: {
            findNames() {
                this.names = this.publicationNames.filter(item => {
                    return item.indexOf(this.filters.title) + 1
                })
            },
            selectItem(item) {
                if(this.selectPublications.indexOf(item) == -1) {
                    this.selectPublications.push(item);
                } else {
                    this.selectPublications.splice(this.selectPublications.indexOf(item), 1);
                }
            },
            getData() {
                this.$store.dispatch('saveFilterPublications', this.filters);
                axios.get('/api/my-publications', {
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
                }).catch(() => {
                    this.loading = false;
                })
            },
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));

                })
            },
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
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            },
            clearFilter() {
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
            authUser() {
                return this.$store.getters.authUser
            },
            access() {
                return this.$store.getters.accessMode
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

