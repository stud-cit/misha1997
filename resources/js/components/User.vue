<template>
    <div class="container general-block">
        <h1 class="blue-page-title">Профіль - {{ data.name }}</h1>
        <div class="page-content">
            <ul class=" list-view">
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Прізвище, ім’я, по-батькові:</div>
                    <div class="col-lg-9 list-item list-text">{{data.name}}</div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Роль:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container" v-if="authUser.roles_id == 4">
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
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Місце роботи:</div>
                    <div class="col-lg-9 list-item list-text">{{data.job}}</div>
                </li>
                <li class="row" v-if="data.position">
                    <div class="col-lg-3 list-item list-title">Посада:</div>
                    <div class="col-lg-9 list-item list-text">{{data.position}}</div>
                </li>
                <li class="row" v-if="data.faculty">
                    <div class="col-lg-3 list-item list-title">Інститут/факультет:</div>
                    <div class="col-lg-9 list-item list-text">{{data.faculty}}</div>
                </li>
                <li class="row" v-if="data.department">
                    <div class="col-lg-3 list-item list-title">Кафедра:</div>
                    <div class="col-lg-9 list-item list-text">{{data.department}}</div>
                </li>
                <li class="row" v-if="data.academic_code">
                    <div class="col-lg-3 list-item list-title">Академічна група:</div>
                    <div class="col-lg-9 list-item list-text">{{data.academic_code}}</div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Країна:</div>
                    <div class="col-lg-9 list-item list-text">{{data.country}}</div>
                </li>
                <li class="row">
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
                <li class="row" v-if="authUser.roles_id == 4">
                    <div class="col-lg-3 list-item list-title">5 або більше публікацій в Scopus та/або WoS:</div>
                    <div class="col-lg-9 list-item list-text">
                        <div class="input-container">
                            <select v-model="data.five_publications">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="table-responsive text-center table-list">
                <table class="table table-bordered">
                    <thead>
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
                            <td>{{ item.publication.publication_type.title }}</td>
                            <td>
                                <span class="authors" v-for="(author, index) in item.publication.authors" :key="index">
                                    <router-link v-if="!author.supervisor" :to="'/user/'+author.author.id">{{author.author.name}} </router-link>
                                </span>
                            </td>
                            <td><router-link :to="{path: `/publications/${item.publication.id}`}"> {{ item.publication.title }} </router-link> </td>
                            <td>{{ item.publication.year}}</td>
                            <td>{{ item.publication.science_type ? item.publication.science_type.type : '' }}</td>
                            <td>
                                <span class="authors" v-for="(author, index) in item.publication.authors" :key="index">
                                    <router-link v-if="author.supervisor" :to="'/user/'+author.author.id">{{author.author.name}} </router-link>
                                </span>
                            </td>
                            <td>{{ item.publication.date }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="data.publications.length == 0">
                    Піблікації відсутні
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
    import BackButton from "./Buttons/Back";
    import SaveButton from "./Buttons/Save";
    export default {
        data() {
            return {
                data: {
                    name: "",
                    role: {
                        name: ""
                    },
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
                    publications: []
                },
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    numPage: 1
                },
                loading: false,
                roles: []
            };
        },
        components: {
            BackButton,
            SaveButton
        },
        mounted () {
            this.getData();
            this.getRoles();
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            filterList() {
                this.pagination.numPage = Math.ceil(this.data.publications.length / this.pagination.perPage);
                return this.data.publications.slice((this.pagination.currentPage - 1) * this.pagination.perPage, this.pagination.currentPage * this.pagination.perPage);
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(`/api/author/${this.$route.params.id}`).then(response => {
                    this.data = response.data;
                    this.loading = false;
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
                        swal("Інформацію оновлено", {
                            icon: "success",
                        });
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