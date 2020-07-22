<template>

    <div class="container">
        <h1 class="page-title">Перегляд публікацій</h1>
        <div class="page-categories">
            <a href="" class="categories-link active">Всі публікації</a>
            <a href="" class="categories-link">Мої публікації</a>
        </div>
        <div class="search-block">
            <label class="main-search-container">
                <input type="text" class="main-search" placeholder="Пошук публікації">
                <div class="search-load">
                    <p class="load-big"></p>
                    <p class="load-little"></p>
                </div>
                <div class="search-btn"><img src="img/search.svg" alt=""></div>
            </label>
            <div class="full-search">
                <div class=" search-categories" v-if="fullSearch">
                    <select name=""  class="search-select">
                        <option hidden selected>Рік видання</option>
                        <option value="2015">2015</option>
                    </select>
                    <select name=""  class="search-select">
                        <option hidden selected>Вид публікації</option>
                        <option value="2015">стаття </option>
                    </select>
                    <select name=""  class="search-select">
                        <option hidden selected>Індексування</option>
                        <option value="2015">2015</option>
                    </select>

                </div>
                <div :class="[{active: fullSearch}, 'full-search-btn']" @click.prevent="fullSearch = !fullSearch">Розширений пошук </div>
            </div>
        </div>

<!--        exports-->
        <export-rating></export-rating>

        <export-publications></export-publications>
<!---->

        <div class="table-bordered table-responsive text-center table-list">

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
                <tr v-for="(item, index) in data" :key="index">
                    <td scope="row">{{ index+1 }}</td>
                    <td>{{ item.publication_type.title }}</td>
                    <td>{{ authorsNameParser(item.authors) }}</td>
                    <td>{{ item.title }}</td>
                    <td>{{ item.year}}</td>
                    <td>{{ item.sub_db_index }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <router-view></router-view>
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

            };
        },
        components: {
            ExportRating,
            ExportPublications
        },
        mounted () {
            this.getData();
        },
        methods: {
            getData() {
                axios.get('/api/publications').then(response => {
                    this.data = response.data
                })
            },
            exportRating(){

                const workbook = XLSX.utils.table_to_book(document.getElementById('exportRating'));

                XLSX.writeFile(workbook, 'filename.xlsx');
            },
            authorsNameParser(arr){

                return arr.map(a => a.author.name).join(', ');
            }
        },
        computed: {


        }

    }
</script>

<style lang="scss" scoped>


</style>
