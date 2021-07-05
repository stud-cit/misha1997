<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Список користувачів
            <span v-if="authUser.roles_id == 3 && divisions.find(item => item.ID_DIV == authUser.faculty_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.faculty_code).NAME_DIV }}</span>
            <span v-if="authUser.roles_id == 2 && divisions.find(item => item.ID_DIV == authUser.department_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.department_code).NAME_DIV }}</span>
        </h1>

        <!-- exports-->
        <div class="exports">
            <export-users :filters="filters" :countUsers="countUsers"></export-users>
        </div>
        <!---->

        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Прізвище, ім’я та по батькові користувача</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.name">
                    </div>
                </div>
                <div class="form-row" v-if="authUser.roles_id != 2">
                    <div class="form-group col-lg" v-if="authUser.roles_id != 3">
                        <label >Інститут / факультет</label>
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
                        <label >Кафедра</label>
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
                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label>Користувачі</label>
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
                    <div class="form-group col-lg-6">
                        <label>Індекс Гірша</label>
                        <div class="input-container">
                            <select v-model="filters.h_index">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                                <option value="10">В тому числі мають індекс Гірша за Scopus та WoS не нижче 10</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6" v-if="authUser.roles_id == 4">
                        <label>Роль</label>
                        <div class="input-container">
                            <select v-model="filters.role">
                                <option
                                    v-for="(item, index) in roles"
                                    :key="index"
                                    :value="item.id"
                                >{{item.name}}</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group col-lg-6" v-if="authUser.roles_id == 4">
                          <label>Наявність Scopus ID</label>
                          <div class="input-container">
                              <select v-model="filters.scopus_id">
                                  <option value=""></option>
                                  <option value="1">Так</option>
                                  <option value="0">Ні</option>
                              </select>
                          </div>
                    </div>
                </div>
                <button type="button" class="export-button" style="display: inline-block" @click="getData(1); loadingSearch = true">
                    <span
                        class="spinner-border spinner-border-sm"
                        style="width: 19px; height: 19px"
                        role="status"
                        aria-hidden="true"
                        v-if="loadingSearch"
                    ></span>
                    <span class="sr-only" v-if="loading">Loading...</span>
                    Пошук
                </button>
                <button type="button" class="export-button" style="display: inline-block" @click="clearFilter">
                    <span
                        class="spinner-border spinner-border-sm"
                        style="width: 19px; height: 19px"
                        role="status"
                        aria-hidden="true"
                        v-if="loadingClear"
                    ></span>
                    <span class="sr-only" v-if="loading">Loading...</span>
                    Очистити фільтр
                </button>
            </form>

            <div class="row my-4" id="header-table">
                <div class="col">
                    <select class="form-control w-50 ml-2" id="sizeTable" v-model="pagination.perPage" @change="getData(1)">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="250">250</option>
                        <option :value="500">500</option>
                        <option :value="countUsers">Всі</option>
                    </select>
                </div>
                <div class="col text-right">
                    <paginate
                        class="paginate-top"
                        v-model="pagination.currentPage"
                        :page-count="Math.ceil(countUsers / pagination.perPage)"
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
                            <td colspan="8" class="bg-white text-left pr-0 pb-3 pt-0">Всього користувачів: {{ countUsers }}</td>
                            <td class="bg-white px-0 pb-3 pt-0" v-if="authUser.roles_id == 4"></td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">ПІБ користувача</th>
                            <th scope="col">Посада/місце роботи</th>
                            <th scope="col">Кафедра/підрозділ</th>
                            <th scope="col">Інститут/факультет</th>
                            <th scope="col">Індекс Гірша Scopus</th>
                            <th scope="col">Індекс Гірша WoS</th>
                            <th scope="col">5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</th>
                            <th scope="col" v-if="authUser.roles_id == 4">Обрати</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="item.id">
                            <td scope="row">{{ pagination.firstItem + index }}</td>
                            <td><a :href="'/user/'+item.id">{{ item.name }}</a></td>
                            <td>{{ item.position }}</td>
                            <td>{{ item.department }}</td>
                            <td>{{ item.faculty }}</td>
                            <td>{{ item.scopus_autor_id }}</td>
                            <td>{{ item.h_index }}</td>
                            <td>{{item.five_publications ? "Так" : "Ні"}}</td>
                            <td class="icons" v-if="authUser.roles_id == 4">
                                <input
                                    type="checkbox"
                                    :checked="selectUsers.indexOf(item) != -1 ? true : false"
                                    @click="selectItem(item)"
                                >
                            </td>
                        </tr>
                        <tr v-if="countUsers != 0">
                            <td colspan="2">Всього користувачів: {{ countUsers }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Всього: {{ scopusAutorId }}</td>
                            <td>Всього: {{ hIndex }}</td>
                            <td>Кількість: {{ fivePublications }}</td>
                            <td v-if="authUser.roles_id == 4"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4 text-center" v-if="countUsers == 0 && !loading">
                    Користувачі відсутні
                </div>
            </div>
            <paginate
                class="mt-4"
                v-model="pagination.currentPage"
                :page-count="Math.ceil(countUsers / pagination.perPage)"
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
                <delete-button @click.native="deleteItem" :disabled="selectUsers.length == 0"></delete-button>
            </div>
        </div>
    </div>
</template>

<script>
    import ExportUsers from "./Publications/Exports/ExportUsers";
    import Multiselect from 'vue-multiselect';
    import BackButton from "./Buttons/Back";
    import DeleteButton from "./Buttons/Delete";
    import divisions from './mixins/divisions';
    export default {
        mixins: [divisions],
        data() {
            return {
                countUsers: 0,
                scopusAutorId: 0,
                hIndex: 0,
                fivePublications: 0,
                loading: true,
                loadingSearch: false,
                loadingClear: false,
                data: [],
                selectUsers: [],
                roles: [],
                filters: {
                    name: '',
                    faculty_code: '',
                    department_code: '',
                    h_index: '',
                    categ_users: [],
                    role: '',
                    position: '',
                    scopus_id: ''
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
                }
            };
        },
        components: {
            BackButton,
            DeleteButton,
            Multiselect,
            ExportUsers
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            }
        },
        created() {
            if(this.$store.getters.getFilterUsers) {
                this.filters = this.$store.getters.getFilterUsers;
            }
            this.getData(this.pagination.currentPage);
            this.getRoles();
        },
        methods: {
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            getData(page) {
                this.loading = true;
                this.$store.dispatch('saveFilterUser', this.filters);
                var params = Object.assign(this.filters, {
                  size: this.pagination.perPage,
                  page
                });
                axios.get('/api/authors', { params }).then(response => {
                    this.countUsers = response.data.count;
                    this.scopusAutorId = response.data.scopusAutorId;
                    this.hIndex = response.data.hIndex;
                    this.fivePublications = response.data.fivePublications;
                    this.pagination.currentPage = response.data.currentPage;
                    this.pagination.firstItem = response.data.firstItem;
                    this.data = response.data.users.data;
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                }).catch(() => {
                    this.loading = false;
                    this.loadingSearch = false;
                    this.loadingClear = false;
                })
            },
            selectItem(item) {
              if(this.selectUsers.indexOf(item) == -1) {
                this.selectUsers.push(item);
              } else {
                this.selectUsers.splice(this.selectUsers.indexOf(item), 1);
              }
            },
            deleteItem() {
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
                        axios.post('/api/delete-users', {
                          users: this.selectUsers
                        })
                        .then(() => {
                            this.selectUsers = [];
                            this.getData(this.pagination.currentPage);
                            swal.fire("Користувачі успішно видалені");
                        });
                    }
                })
            },
            getRoles() {
                axios.get('/api/roles').then(response => {
                    this.roles = response.data;
                })
            },
            clearFilter() {
                this.loadingClear = true;
                this.$store.dispatch('clearFilterUser');
                this.filters.name = '';
                this.filters.faculty_code = '';
                this.filters.department_code = '';
                this.filters.h_index = '';
                this.filters.categ_users = '';
                this.filters.role = '';
                this.filters.position = '';
                this.filters.scopus_id = '';
                this.getData(1);
            }
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
    input[type=checkbox] {
        -ms-transform: scale(1.5); /* IE */
        -moz-transform: scale(1.5); /* FF */
        -webkit-transform: scale(1.5); /* Safari and Chrome */
        -o-transform: scale(1.5); /* Opera */
        transform: scale(1.5);
        padding: 5px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .search-block{
        margin-top: 20px;
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
    .table-list{
        margin-top: 0px;
    }
    @media(max-width: 575px){
        .search-block{
            margin-top: 30px;
        }
        .exports{
            margin-top: 25px;
        }
    }
</style>
