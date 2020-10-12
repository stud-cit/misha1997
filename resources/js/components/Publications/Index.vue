<template>

    <div class="container page-content">
        <h1 class="page-title">Перегляд публікацій</h1>
<!--        <h2 class="subtitle">Мої публікації</h2>-->

        <!--        exports-->
        <div class="exports">

            <export-rating :publicationTypes="publicationTypes" :years="years" :countries="countries" class="export-block"></export-rating>

            <export-publications class="export-block" :exportList="exportPublication"></export-publications>

        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Назва публікації</label>
                    <div class="input-container">
                        <input v-model="filters.title" type="text" list="names" @input="findNames">
                        <datalist id="names">
                            <option v-for="(item, index) in names" :key="index" :value="item">{{item}}</option>
                        </datalist>
                    </div>
                </div>
                <div class="form-group">
                    <label >Прізвище та ініціали автора</label>
                    <div class="input-container hint-container">
                        <input type="text" v-model="filters.initials" >
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label >БД Scopus\WoS</label>
                        <div class="input-container">
                            <select  v-model="filters.science_type_id">
                                <option value=""></option>
                                <option value="1">Scopus</option>
                                <option value="2">WoS</option>
                                <option value="3">Scopus та WoS</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Рік
                            видання</label>
                        <div class="input-container">
                            <select  v-model="filters.year">
                                <option value=""></option>
                                <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>


                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Країна видання</label>
                        <div class="input-container">
                            <select  v-model="filters.country">
                                <option value=""></option>
                                <option v-for="(item, index) in countries" :value="item.name" :key="index">{{item.name}}</option>

                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label >Вид публікації</label>
                    <div class="input-container">
                        <select  v-model="filters.publication_type_id">
                            <option value=""></option>
                            <option v-for="(item, index) in publicationTypes" :value="item.id" :key="index">{{item.title}}</option>

                        </select>
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>

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

            </form>




            <div class="table-responsive text-center table-list">

                <table class="table table-bordered " >
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Вид
                            пуб-ції</th>
                        <th scope="col">Прізвище та ініціали автора\
                            співавторів</th>
                        <th scope="col">Назва публікації</th>
                        <th scope="col">Рік
                            видання</th>
                        <th scope="col">БД
                            Scopus\
                            WoS</th>
                        <th scope="col">Науковий
                            керівник</th>
                        <th scope="col"></th>
                        <th scope="col">Редагувати</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="index">
                        <td scope="row">{{ index+1+(currentPage-1)*perPage }}</td>
                        <td>{{ item.publication_type.title }}</td>
                        <td>{{ item.initials }}</td>
                        <td><router-link :to="{path: `/publications/${item.id}`}"> {{ item.title }} </router-link> </td>
                        <td>{{ item.year}}</td>
                        <td>{{ item.science_type ? item.science_type.type : '' }}</td>
                        <td>{{ item.supervisor ? item.supervisor.name : '' }}</td>
                        <td class="icons">
                            <input
                                v-if="access == 'open' && (item.authors.find(itemAuthor => itemAuthor.author.id == authUser.id) || authUser.roles_id == 4)"
                                type="checkbox"
                                :checked="selectPublications.indexOf(item) != -1 ? true : false"
                                @click="selectItem(item)"
                            >
<!--                            <i class="fas fa-pen-square"></i>-->
<!--                            <i class="fas fa-trash-alt"></i>-->
                        </td>
                        <td><router-link tag="i" class="fa fa-edit fa-2x" :to="{path: `/publications/edit/${item.id}`}"></router-link> </td>
                    </tr>
                    </tbody>
                </table>
                <div class="spinner-border mt-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
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
                <div class="edit-block" v-if="access == 'open'">
                    <router-link :to="'/home'" tag="button" class="mr-2">Назад</router-link>
                    <button class="mr-2 delete" @click="deletePublications">Видалити</button>
                </div>
            </div>
<!--            <router-view></router-view>-->
        </div>
    </div>
</template>

<script>
    import ExportRating from "./Exports/ExportRating";
    import ExportPublications from "./Exports/ExportPublications";

    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                departments: [],
                divisions: [],
                names: [],
                publicationNames: [],
                selectPublications: [],
                loading: true,
                currentPage: 1,
                perPage: 10,
                numPage: 1,
                fullSearch: false,
                data: [],
                countries: [],
                publicationTypes: [],
                exportData: {},
                exportPublication: [],

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

        },
        mounted () {
            this.getData();
            this.getCountry();
            this.getTypePublications();
            this.getNamesPublications();
            this.getDivisions();
        },
        methods: {
            getDepartments() {
                this.departments = this.divisions.find(item => {
                    return this.filters.faculty_code == item.ID_DIV
                }).departments;
            },

            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },

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
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.countries = response.data;
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
            editPublication() {
			    this.$router.push({path: `/publications/edit/${this.selectPublications[0].id}`});
            },
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
                            swal("Публікація успішно видалена", {
                                icon: "success",
                            });
                        });
					}
				});
            },
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            access() {
                return this.$store.getters.accessMode
            },
            filteredList() {
                let arr = this.data.filter(item => {
                    let result = 1;
                    let keys = Object.keys(this.filters);
                    console.log(keys);
                    let values = Object.values(this.filters);
                    console.log(values)
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

                    return result

                });
                this.exportPublication = arr;
                this.numPage = Math.ceil(arr.length/this.perPage);


                return arr.slice((this.currentPage-1)*this.perPage, this.currentPage*this.perPage);
            },
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: 10}, (value, index) => year - index);
            },

        }

    }
</script>

<style lang="scss" scoped>
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

