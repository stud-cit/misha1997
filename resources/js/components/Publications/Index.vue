<template>

    <div class="container page-content">
        <h1 class="page-title">Перегляд публікацій</h1>
<!--        <h2 class="subtitle">Мої публікації</h2>-->
        <!--        exports-->
        <div class="exports">

            <export-rating class="export-block"></export-rating>

            <export-publications class="export-block" :exportList="filteredList"></export-publications>
        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Назва публікації</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.title" >
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>
                <div class="form-group">
                    <label >Прізвище та ініціали автора</label>
                    <div class="input-container">
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
                                <option value="2">Wos</option>
                                <option value="3">Scopus та Wos</option>
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

                </div>

                <div class="form-row">
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
                    <div class="form-group col-lg-4">
                        <label >Місто видання</label>
                        <div class="input-container">
                            <input type="text"  v-model="filters.city">
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
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="index">
                        <td scope="row">{{ index+1 }}</td>
                        <td>{{ item.publication_type.title }}</td>
                        <td>{{ item.initials }}</td>
                        <td><router-link :to="{path: `/publications/${item.id}`}"> {{ item.title }} </router-link> </td>
                        <td>{{ item.year}}</td>
                        <td>{{ item.science_type ? item.science_type.type : '' }}</td>
                        <td>{{ item.supervisor ? item.supervisor.name : '' }}</td>
                        <td class="icons">
                            <input type="checkbox">
<!--                            <i class="fas fa-pen-square"></i>-->
<!--                            <i class="fas fa-trash-alt"></i>-->
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="edit-block">
                    <button class="mr-2 edit">Редагувати</button>
                    <button class="delete">Видалити</button>
                </div>
            </div>
            <router-view></router-view>
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
                fullSearch: false,
                data: [],
                countries: [],
                publicationTypes: [],
                exportData: {},


                filters: {
                    title: '',
                    initials: '',
                    science_type_id: '',
                    year: '',
                    country: '',
                    city: '',
                    publication_type_id: '',


                }

            };
        },
        components: {
            ExportRating,
            ExportPublications
        },
        mounted () {
            this.getData();
            this.getCountry();
            this.getTypePublications();
        },
        methods: {
            getData() {
                axios.get('/api/publications').then(response => {
                    this.data = response.data;


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




        },
        computed: {
            filteredList() {

                let arr = this.data.filter(item => {
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

                    return result

                });


                return arr;
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
