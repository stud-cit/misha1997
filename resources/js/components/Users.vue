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
                    <div class="form-group col" v-if="authUser.roles_id != 3">
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
                    <div class="form-group col">
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
                <div class="form-group form-check ml-2">
                    <input v-model="filters.all" type="checkbox" class="form-check-input" id="allUsers">
                    <label class="form-check-label" for="allUsers">Всі користувачі</label>
                </div>
                <button type="button" class="export-button" style="display: inline-block" @click="getData()">Пошук</button>
                <button type="button" class="export-button" style="display: inline-block" @click="clearFilter">Очистити фільтр</button>
            </form>
            <div class="table-responsive text-center table-list">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">ПІБ користувача</th>
                            <th scope="col">Посада</th>
                            <th scope="col">Кафедра</th>
                            <th scope="col">Інститут/факультет</th>
                            <!-- <th scope="col">Вік</th> -->
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
                            <!-- <td>{{ item.age }}</td> -->
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <!-- <td></td> -->
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
                    Кристувачі відсутні
                </div>
            </div>
            <paginate
                v-model="currentPage"
                :page-count="numPage"

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
            <tr v-for="(item, i) in filteredList" :key="i">
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
    import BackButton from "./Buttons/Back";
    import DeleteButton from "./Buttons/Delete";
    import divisions from './mixins/divisions';
    import XLSX from 'xlsx';
    import {required, requiredIf} from "vuelidate/lib/validators";
    export default {
        mixins: [divisions],
        data() {
            return {
                count_five_publications: 0,
                count_h_index: 0,
                count_scopus_autor_id: 0,
                loading: true,
                data: [],
                selectUsers: [],
                filters: {
                    all: false,
                    name: '',
                    faculty_code: '',
                    department_code: ''
                },
                currentPage: 1,
                perPage: 10,
                numPage: 1,

            };
        },
        components: {
            BackButton,
            DeleteButton
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            filteredList() {
                this.numPage = Math.ceil(this.data.length/this.perPage);
                this.count_five_publications = 0;
                this.count_h_index = 0;
                this.count_scopus_autor_id = 0;
                this.data.map(item => {
                    if(item.h_index) {
                        this.count_h_index += +item.h_index;
                    }
                    if(item.h_index) {
                        this.count_scopus_autor_id += +item.scopus_autor_id;
                    }
                    if(item.five_publications) {
                        this.count_five_publications++;
                    }
                });
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
            getData() {
                axios.get('/api/authors', {
                    params: {
                        name: this.filters.name,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        all: this.filters.all
                    }
                }).then(response => {
                    this.data = response.data;
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
				swal({
					title: "Бажаєте видалити?",
					text: "Після видалення ви не зможете відновити дані!",
					icon: "warning",
					buttons: true,
					dangerMode: true,
				}).then((willDelete) => {
					if (willDelete) {
						axios.post('/api/delete-users', {
                            users: this.selectUsers
						})
                        .then(() => {
                            this.selectUsers = [];
                            this.getData();
                            swal("Користувачі успішно видалені", {
                                icon: "success",
                            });
                        });
					}
				});
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
