<template>
    <div class="container general-block">
        <h1 class="blue-page-title">Профіль - {{ data.name }}</h1>
        <div class="page-content">
            <ul class=" list-view">
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Прізвище, ім’я, по-батькові:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                            <input class="item-value" type="text" v-model="data.name">
                        </div>
                        <div v-else>
                            {{data.name}} <img src="/img/update.png" @click="updateCabinetInfo" class="update-cabinet-info">
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Роль:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && data.guid">
                            <select v-model="data.roles_id">
                                <option
                                    v-for="(item, index) in roles"
                                    :key="index"
                                    :value="item.id"
                                >{{item.name}}</option>
                            </select>
                        </div>
                        <template v-else>
                            {{data.role.name}}
                        </template>
                    </div>
                </li>
                <li class="row" v-if="data.job">
                    <div class="col-lg-3 list-item list-title">Місце роботи:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                                <input class="item-value" type="text" v-model="data.job">
                        </div>
                        <div v-else>
                            {{data.job}}
                        </div>
                    </div>
                </li>
                <li class="row" v-if="data.position && data.job != 'Не працює'">
                    <div class="col-lg-3 list-item list-title">Посада:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                            <input class="item-value" type="text" v-model="data.position">
                        </div>
                        <div v-else>
                            {{data.position}}
                        </div>
                    </div>
                </li>
                <li class="row" v-if="data.faculty">
                    <div class="col-lg-3 list-item list-title">Інститут/факультет:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                                <input class="item-value" type="text" v-model="data.faculty">
                        </div>
                        <div v-else>
                            {{data.faculty}}
                        </div>
                    </div>
                </li>
                <li class="row" v-if="data.department && (data.faculty != data.department)">
                    <div class="col-lg-3 list-item list-title">Кафедра:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                                <input class="item-value" type="text" v-model="data.department">
                        </div>
                        <div v-else>
                            {{data.department}}
                        </div>
                    </div>
                </li>
                <li class="row" v-if="data.academic_code">
                    <div class="col-lg-3 list-item list-title">Академічна група:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                                <input class="item-value" type="text" v-model="data.academic_code">
                        </div>
                        <div v-else>
                            {{data.academic_code}}
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Країна:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4 && !data.guid">
                               <Country :data="data"></Country>
                        </div>
                        <div v-else>
                            {{data.country}}
                        </div>
                    </div>
                    
                </li>
                <li class="row" v-if="!data.guid">
                    <div class="col-lg-3 list-item list-title">Входить до списків Forbes та Fortune:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4">
                            <select v-model="data.forbes_fortune">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                        <div class="col-lg-9 list-item list-text" v-else>
                            {{data.forbes_fortune ? "Так" : "Ні"}}
                        </div>
                    </div>
                </li>
                <li class="row" v-if="data.guid">
                    <div class="col-lg-3 list-item list-title">5 або більше публікацій у періодичних виданнях Scopus та/або WoS:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4">
                            <select v-model="data.five_publications">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                        <div class="col-lg-9 list-item list-text" v-else>
                            {{data.five_publications ? "Так" : "Ні"}}
                        </div>
                    </div>
                </li>
                <li class="row" style="border-bottom: 0">
                    <div class="col-lg-3 list-item list-title">Індекс Гірша:</div>
                    <div class="col-lg-9  list-item list-text d-flex">
                        <div class="col-lg-6 two-col pr-2">
                            <label>БД Scopus</label>
                            <div class="input-container" v-if="authUser.roles_id == 4">
                                <input class="item-value" type="text" v-model="data.scopus_autor_id">
                            </div>
                            <div v-else>
                                {{data.scopus_autor_id}}
                            </div>
                        </div>
                        <div class="col-lg-6 two-col">
                            <label>БД WoS</label>
                            <div class="input-container" v-if="authUser.roles_id == 4">
                                <input class="item-value" type="text" v-model="data.h_index">
                            </div>
                            <div v-else>
                                {{data.h_index}}
                            </div>
                        </div>
                    </div>
                </li>
                <li class="row" style="border-bottom: 0">
                    <div class="col-lg-3 list-item list-title">Індекс Гірша без самоцитувань:</div>
                    <div class="col-lg-9  list-item list-text d-flex">
                        <div class="col-lg-6 two-col pr-2">
                            <label>БД Scopus</label>
                            <div class="input-container" v-if="authUser.roles_id == 4">
                                <input class="item-value" type="text" v-model="data.without_self_citations_scopus">
                            </div>
                            <div v-else>
                                {{data.without_self_citations_scopus}}
                            </div>
                        </div>
                        <div class="col-lg-6 two-col">
                            <label>БД WoS</label>
                            <div class="input-container" v-if="authUser.roles_id == 4">
                                <input class="item-value" type="text" v-model="data.without_self_citations_wos">
                            </div>
                            <div v-else>
                                {{data.without_self_citations_wos}}
                            </div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Research ID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4">
                            <input class="item-value" type="text" v-model="data.scopus_researcher_id">
                        </div>
                        <div v-else>
                            {{data.scopus_researcher_id}}
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">ORCID:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4">
                            <input class="item-value" type="text" v-model="data.orcid">
                        </div>
                        <div v-else>
                            {{data.scopus_researcher_id}}
                        </div>
                    </div>
                </li>
            </ul>

            <div class="text-right">
                <button type="button" class="export-button my-3" @click="showFilter = !showFilter">Фільтр</button>
            </div>

            <form class="search-block mb-3" v-if="showFilter">
                <div class="form-group">
                    <label>Назва публікації</label>
                    <div class="input-container">
                        <input v-model="filters.title" type="text">
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
                <button type="button" class="export-button" style="display: inline-block" @click="getPublications(); loadingSearch = true" :disabled="data.length == 0 || loadingSearch">
                    <span
                        class="spinner-border spinner-border-sm"
                        style="width: 19px; height: 19px;"
                        role="status"
                        aria-hidden="true"
                        v-if="loadingSearch"
                    ></span>
                    <span class="sr-only" v-if="loading">Loading...</span>
                    Пошук
                </button>
            </form>

            <div class="table-responsive text-center table-list">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="8" class="bg-white text-left pb-3 pt-0">Всього публікацій: {{publications.length}}</td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Вид публікації</th>
                            <th scope="col">Прізвище та ініціали автора/співавторів</th>
                            <th scope="col">Назва публікації</th>
                            <th scope="col">Рік видання</th>
                            <th scope="col">БД Scopus/WoS</th>
                            <th scope="col">Науковий керівник</th>
                            <th scope="col">Дата занесення</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filterList" :key="index">
                            <td scope="row">{{ index + 1 + (pagination.currentPage - 1) * pagination.perPage }}</td>
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
                                    <a v-if="author.supervisor" :href="'/user/'+author.author.id">{{author.author.name}} </a>
                                </span>
                            </td>
                            <td>{{ item.created_at }}</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-left">Всього публікацій: {{ publications.length }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="publications.length == 0">
                    Публікації відсутні
                </div>
            </div>
            <paginate
                v-model="pagination.currentPage"
                :page-count="pagination.numPage"

                :prev-text="'<'"
                :next-text="'>'"

                :container-class="'pagination'"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>
            <div class="step-button-group">
                <back-button></back-button>
                <save-button @click.native="save()" v-if="authUser.roles_id == 4"></save-button>
            </div>
        </div>
    </div>
</template>
<script>
    import years from './mixins/years';

    import BackButton from "./Buttons/Back";
    import SaveButton from "./Buttons/Save";
    import Country from "./Forms/Country";
    import PublicationTypes from "./Forms/PublicationTypes";
    export default {
        mixins: [years],
        data() {
            return {
                showFilter: false,
                loadingSearch: false,
                data: {
                    name: "",
                    role: {
                        name: ""
                    },
                    guid: null,
                    date_bth: "",
                    job: "",
                    position: "",
                    faculty: "",
                    department: "",
                    academic_code: "",
                    country: "",
                    scopus_autor_id: "",
                    h_index: "",
                    scopus_researcher_id: "",
                    orcid: "",
                    five_publications: "",
                    without_self_citations_wos: "",
                    without_self_citations_scopus: ""                    
                },
                publications: [],
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    numPage: 1
                },
                filters: {
                    title: '',
                    authors_f: '',
                    science_type_id: '',
                    year: '',
                    country: '',
                    publication_type_id: '',
                    faculty_code: '',
                    department_code: ''
                },
                loading: false,
                roles: []
            };
        },
        components: {
            BackButton,
            SaveButton,
            Country,
            PublicationTypes
        },
        mounted () {
            this.getData();
            this.getPublications();
            this.getRoles();
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            filterList() {
                this.pagination.numPage = Math.ceil(this.publications.length / this.pagination.perPage);
                return this.publications.slice((this.pagination.currentPage - 1) * this.pagination.perPage, this.pagination.currentPage * this.pagination.perPage);
            }
        },
        methods: {
            updateCabinetInfo() {
                axios.post(`/api/update-cabinet-info/${this.$route.params.id}`)
                .then(() => {
                    this.getData();
                })
            },
            getData() {
                axios.get(`/api/author/${this.$route.params.id}`).then(response => {
                    this.data = response.data;
                    this.loading = false;
                })
            },
            getPublications() {
                this.loading = true;
                axios.get('/api/publications', {
                    params: {
                        author_id: this.$route.params.id,
                        title: this.filters.title,
                        authors_f: this.filters.authors_f,
                        science_type_id: this.filters.science_type_id,
                        year: this.filters.year,
                        country: this.filters.country,
                        publication_type_id: this.filters.publication_type_id
                    }
                }).then(response => {
                    this.publications = response.data;
                    this.loading = false;
                    this.loadingSearch = false;
                })
            },
            getRoles() {
                axios.get('/api/roles').then(response => {
                    this.roles = response.data;
                })
            },
            save() {
                axios.post(`/api/update-author/${this.$route.params.id}`, this.data)
                    .then((response) => {
                        swal.fire({
                            icon: 'success',
                            title: 'Інформацію оновлено'
                        });
                        this.$router.go(-1);
                })
            }
        },
        watch: {
            $route(to, from) {
                this.data.publications = [];
                this.getData();
            }
        }
    }
</script>
<style lang="css" scoped>
    .update-cabinet-info {
        cursor: pointer;
    }
    .table-list {
        margin-top: 10px;
    }
</style>