<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Список авторизованих користувачів
            <span v-if="authUser.roles_id == 3 && divisions.find(item => item.ID_DIV == authUser.faculty_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.faculty_code).NAME_DIV }}</span>
            <span v-if="authUser.roles_id == 2 && divisions.find(item => item.ID_DIV == authUser.department_code)"> - {{ divisions.find(item => item.ID_DIV == authUser.department_code).NAME_DIV }}</span>
        </h1>
        <div class="exports">
            <button class="export-button" @click="exportUsers"><img src="/img/download.png" alt=""> Експорт списку користувачів</button>
        </div>
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Прізвище, ім’я та по батькові користувача</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.name">
                    </div>
                </div>
                <div class="form-row" v-if="authUser.roles_id != 2">
                    <div class="form-group col-lg-6" v-if="authUser.roles_id != 3">
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
                    <div class="form-group col-lg-6">
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
                </div>
                <div class="form-row">
                    <div class="form-group form-check col-lg-6 ml-4">
                        <input v-model="filters.five_publications" type="checkbox" class="form-check-input" id="FiveOrMore">
                        <label class="form-check-label" for="FiveOrMore">5 або більше публікацій у періодичних виданнях Scopus та/або WoS</label>
                    </div>
                    <div class="form-group col-lg-6 form-check ml-4">
                        <input v-model="filters.country" type="checkbox" class="form-check-input" id="allForeign">
                        <label class="form-check-label" for="allForeign">Іноземці</label>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group form-check ml-2">
                        <input v-model="filters.all" type="checkbox" class="form-check-input" id="allUsers">
                        <label class="form-check-label" for="allUsers">Всі користувачі</label>
                    </div>
                </div>
                <button type="button" class="export-button" style="display: inline-block" @click="getData()">Пошук</button>
                <button type="button" class="export-button" style="display: inline-block" @click="clearFilter">Очистити фільтр</button>
            </form>
            <paginate
                v-model="currentPage"
                :page-count="numPage"
                @click.native = "scrollHeader()"

                prev-text="<"
                next-text=">"

                container-class="pagination"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>
            <div class="table-responsive text-center table-list">
                <table class="table table-bordered">
                    <thead id="header-table">
                        <tr>
                            <td colspan="8" class="bg-white text-left pr-0 pb-3 pt-0">Всього користувачів: {{ data.length }}</td>
                            <td class="bg-white px-0 pb-3 pt-0" v-if="authUser.roles_id == 4"></td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">ПІБ користувача</th>
                            <th scope="col">Посада</th>
                            <th scope="col">Кафедра</th>
                            <th scope="col">Інститут/факультет</th>
                            <th scope="col">Індекс Гірша Scopus</th>
                            <th scope="col">Індекс Гірша WoS</th>
                            <th scope="col">5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</th>
                            <th scope="col" v-if="authUser.roles_id == 4">Обрати</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in filteredList" :key="item.id">
                            <td scope="row">{{ index+1+(currentPage-1)*perPage}}</td>
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
                        <tr>
                            <td colspan="2">Всього користувачів: {{ data.length }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Всього: {{ count_scopus_autor_id }}</td>
                            <td>Всього: {{ count_h_index }}</td>
                            <td>Кількість: {{ count_five_publications }}</td>
                            <td v-if="authUser.roles_id == 4"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4 text-center" v-if="filteredList.length == 0">
                    Користувачі відсутні
                </div>
            </div>
            <paginate
                @click.native = "scrollHeader()"
                v-model="currentPage"
                :page-count="numPage"

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
        <table id="exportUsers" v-show="false">
            <tr>
                <th>ID</th>
                <th>ПІБ</th>
                <th>Вік</th>
                <th>Посада</th>
                <th>Академічна група</th>
                <th>Факультет/інститут</th>
                <th>Кафедра</th>
                <th>Країна</th>
                <th>Індекс Гірша БД WoS</th>
                <th>Індекс Гірша БД Scopus</th>
                <th>Research ID</th>
                <th>ORCID</th>
                <th>5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</th>
            </tr>
            <tr v-for="(item, i) in data" :key="i">
                <td>{{i+1}}</td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{item.position}}</td>
                <td>{{item.academic_code}}</td>
                <td>{{item.faculty}}</td>
                <td>{{item.department}}</td>
                <td>{{item.country}}</td>
                <td>{{item.h_index}}</td>
                <td>{{item.scopus_autor_id}}</td>
                <td>{{item.scopus_researcher_id}}</td>
                <td>{{item.orcid}}</td>
                <td>{{item.five_publications ? "Так" : "Ні"}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import BackButton from "./Buttons/Back";
    import DeleteButton from "./Buttons/Delete";
    import divisions from './mixins/divisions';
    import XLSX from 'xlsx';
    import {required, requiredIf} from "vuelidate/lib/validators";
    export default {
        mixins: [divisions],
        data() {
            return {
                loading: true,
                data: [],
                selectUsers: [],
                filters: {
                    all: false,
                    name: '',
                    faculty_code: '',
                    department_code: '',
                    country: false,
                    five_publications: false,
                    h_index: '',
                    categ_users: []

                },
                categUsers: ["Користувачі СумДУ", "Зовнішні співавтори", "Студенти"],
                currentPage: 1,
                perPage: 10,
                numPage: 1,

            };
        },
        components: {
            BackButton,
            DeleteButton,
            Multiselect
        },
        computed: {
            count_scopus_autor_id() {
                let result = 0;
                this.data.map(item => {
                    if(item.scopus_autor_id) {
                        result += +item.scopus_autor_id;
                    }
                });
                return result;
            },
            count_h_index() {
                let result = 0;
                this.data.map(item => {
                    if(item.h_index) {
                        result += +item.h_index;
                    }
                });
                return result;
            },
            count_five_publications() {
                let result = 0;
                this.data.map(item => {
                    if(item.five_publications) {
                        result ++;
                    }
                });
                return result;
            },
            authUser() {
                return this.$store.getters.authUser
            },
            filteredList() {
                this.numPage = Math.ceil(this.data.length/this.perPage);
                this.$store.dispatch('saveFilterUser', this.filters);
                return this.data.slice((this.currentPage-1)*this.perPage, this.currentPage*this.perPage);
            }
        },
        created() {
            if(this.$store.getters.getFilterUsers) {
                this.filters = this.$store.getters.getFilterUsers;
            }
            this.getData();
        },
        methods: {
            scrollHeader() {
                document.location = '#header-table';
            },
            getData() {
                axios.get('/api/authors', {
                    params: {
                        name: this.filters.name,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        all: this.filters.all,
                        country: this.filters.country,
                        five_publications: this.filters.five_publications,
                        h_index: this.filters.h_index,
                        categ_users: this.filters.categ_users
                    }
                }).then(response => {
                    this.data = response.data;
                    this.currentPage = 1;
                    this.perPage = 10;
                    this.numPage = 1;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            },
            clean() {
                this.searchAuthor = '';
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
                            this.getData();
                            swal.fire("Користувачі успішно видалені");
                        });
                    }
                })
            },
            exportUsers() {
                const authors = XLSX.utils.table_to_book(document.getElementById('exportUsers'));
                const wb = XLSX.utils.book_new();
                wb.SheetNames.push("Authors");
                wb.Sheets.Authors = authors.Sheets.Sheet1;
                wb.Sheets.Authors['!cols'] = [
                    { wch: 5 },  // id
                    { wch: 30 }, // ПІБ
                    { wch: 5 },  // Вік
                    { wch: 10 }, // Посада
                    { wch: 20 }, // Академічна група
                    { wch: 40 }, // Факультет/інститут
                    { wch: 40 }, // Кафедра
                    { wch: 15 }, // Країна
                    { wch: 20 }, // Індекс Гірша БД WoS
                    { wch: 20 }, // Індекс Гірша БД Scopus
                    { wch: 10 }, // Research ID
                    { wch: 10 }, // ORCID
                    { wch: 40 }  // 5 або більше публікацій в Scopus та/або WoS
                ];
                XLSX.writeFile(wb, 'Authors.xlsx');
            },
            clearFilter() {
                this.$store.dispatch('clearFilterPublications');
                this.filters.all = false;
                this.filters.name = '';
                this.filters.faculty_code = '';
                this.filters.department_code = '';
                this.filters.country = false;
                this.filters.five_publications = false;
                this.filters.h_index = '';
                this.filters.categ_users = '';
                this.getData();
            }
        }
    }
</script>

<style lang="scss" scoped>
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
    @media(max-width: 575px){
        .search-block{
            margin-top: 30px;
        }
        .exports{
            margin-top: 25px;
        }
    }
</style>
