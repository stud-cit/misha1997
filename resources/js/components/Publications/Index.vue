<template>

    <div class="container">
        <h1 class="page-title">Перегляд публікацій</h1>
        <!--        exports-->
        <div class="exports">

            <export-rating class="export-block"></export-rating>

            <export-publications class="export-block" :exportData="exportData"></export-publications>
        </div>
        <!---->
        <div class="main-content">
            <form class="search-block">
                <div class="form-group">
                    <label >Назва публікації</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.title" >
                        <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label >Прізвище та ініціали автора</label>
                    <div class="input-container">
                        <input type="text" v-model="filters.initials" >
                        <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
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
                            <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Рік впровадження</label>
                        <div class="input-container">
                            <select  v-model="filters.year">
                                <option value=""></option>
                                <option v-for="(item, index) in years" :key="index" :value="item">{{item}}</option>


                            </select>
                            <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
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
                            <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Місто видання</label>
                        <div class="input-container">
                            <input type="text"  v-model="filters.city">
                            <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
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
                        <span class="hint"  data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom"></span>
                    </div>
                </div>


            </form>




            <div class="table-responsive text-center table-list">

                <table class="table table-bordered " >
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Вид</th>
                        <th scope="col">ПІБ автора</th>
                        <th scope="col">Назва публікації</th>
                        <th scope="col">Рік</th>
                        <th scope="col">Індексування</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in filteredList" :key="index">
                        <td scope="row">{{ index+1 }}</td>
                        <td>{{ item.publication_type.title }}</td>
                        <td>{{ item.initials }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.year}}</td>
                        <td>{{ item.sub_db_index }}</td>
                    </tr>
                    </tbody>
                </table>
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
                exportData: {
                    articles: [],
                    books: [],
                    booksParts: [],
                    thesis: [],
                    patents: [],
                    certificates: [],
                    methodicals: [],
                    electronics: [],
                },
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
                    this.exportParser();

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

            exportRating(){

                const workbook = XLSX.utils.table_to_book(document.getElementById('exportRating'));

                XLSX.writeFile(workbook, 'filename.xlsx');
            },

            exportParser(){
                const publications = this.data;

                for (let i = 0; i <publications.length; i++){
                    if(publications[i].publication_type.type == "article"){

                        this.exportData.articles.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "book"){
                        this.exportData.books.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "book-part"){
                        this.exportData.booksParts.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "thesis"){
                        this.exportData.thesis.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "patent"){
                        this.exportData.patents.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "certificate"){
                        this.exportData.certificates.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "methodical"){
                        this.exportData.methodicals.push(publications[i]);
                    }
                    else {
                        this.exportData.electronics.push(publications[i]);
                    }
                }


            }
        },
        computed: {
            filteredList() {
                return this.data.filter(item => {
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

                })
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

</style>
