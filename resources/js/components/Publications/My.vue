<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Мої публікації</h1>
        <div class="exports">
            <export-publications class="export-block" :filters="filters" :countPublications="countPublications"></export-publications>
        </div>
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label>Назва публікації</label>
                    <div class="input-container">
                        <input v-model="filters.title" type="text">
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
                    <PublicationTypes :data="filters"></PublicationTypes>
                </div>
                <div class="form-group checkbox col-lg-6">
                    <input v-model="filters.withSupervisor" type="checkbox" class="form-check-input" id="withStudents">
                    <label class="form-check-label" for="withStudents">Під керівництвом</label>
                </div>
                <SearchButton
                    @click.native="getData(1); loadingSearch = true"
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
                        <tr>
                            <td colspan="12" class="text-left">Всього публікацій: {{ countPublications }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="countPublications == 0">
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
                <delete-button v-if="access == 'open' || authUser.roles_id == 4" @click.native="deletePublications" :disabled="selectPublications.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import years from '../mixins/years';
    import country from '../mixins/country';

    import ExportPublications from "./Exports/ExportPublications";
    import BackButton from "../Buttons/Back";
    import SearchButton from "../Buttons/SearchButton";
    import DeleteButton from "../Buttons/Delete";
    import Country from "../Forms/Country";
    import PublicationTypes from "../Forms/PublicationTypes";
    export default {
        mixins: [years, country],
        data() {
            return {
                countPublications: 0,
                loading: true,
                loadingSearch: false,
                loadingClear: false,
                selectPublications: [],
                exportPublication: [],
                loading: true,
                data: [],
                filters: {
                    title: '',
                    authors_f: '',
                    science_type_id: '',
                    year: '',
                    country: '',
                    publication_type_id: '',
                    faculty_code: '',
                    department_code: '',
                    withSupervisor: false
                },
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    firstItem: 1
                },
            };
        },
        components: {
            ExportPublications,
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
            this.getData(this.pagination.currentPage);
        },
        methods: {
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            selectItem(item) {
                if(this.selectPublications.indexOf(item) == -1) {
                    this.selectPublications.push(item);
                } else {
                    this.selectPublications.splice(this.selectPublications.indexOf(item), 1);
                }
            },
            // getters
            // всі публікації
            getData(page) {
                this.loading = true;
                this.$store.dispatch('saveFilterPublications', this.filters);
                axios.get('/api/publications/'+this.authUser.id, {
                    params: {
                        size: this.pagination.perPage,
                        page: page,
                        title: this.filters.title,
                        authors_f: this.filters.authors_f,
                        science_type_id: this.filters.science_type_id,
                        year: this.filters.year,
                        country: this.filters.country,
                        publication_type_id: this.filters.publication_type_id,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        withSupervisor: this.filters.withSupervisor
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
                this.filters.withSupervisor = false;
                this.getData(1);
            }
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            access() {
                return this.$store.getters.accessMode
            },
            checkAccess() {
                if(this.access == 'open') {
                    return true;
                } else if(this.authUser.roles_id == 4 || (this.access == 'open' && this.authUser.roles_id != 1)) {
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

