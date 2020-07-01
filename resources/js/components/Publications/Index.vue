<template>

    <div class="container">
        <h1 class="page-title">Перегляд публікацій</h1>
        <div class="page-categories">
            <a href="" class="categories-link active">Всі публікації</a>>
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

        <button @click="exportRating">export</button>
<!--        <table id="exportRating" v-show="false">-->
<!--            <tr>-->
<!--                <th rowspan="2">-->
<!--                    Кількість статей-->
<!--                    за авторством та-->
<!--                    співавторством студентів-->
<!--                </th>-->
<!--                <th colspan="2" width="500px">-->
<!--                    Кількість публікацій у співавторстві-->
<!--                    з іноземними партнерами-->
<!--                </th>-->
<!--&lt;!&ndash;                <th>&ndash;&gt;-->
<!--&lt;!&ndash;                    Кількість статей за авторством та співавторством студентів&ndash;&gt;-->
<!--&lt;!&ndash;                </th>&ndash;&gt;-->
<!--&lt;!&ndash;                <th>&ndash;&gt;-->
<!--&lt;!&ndash;                    Кількість статей за авторством та співавторством студентів&ndash;&gt;-->
<!--&lt;!&ndash;                </th>&ndash;&gt;-->
<!--&lt;!&ndash;                <th>&ndash;&gt;-->
<!--&lt;!&ndash;                    Кількість статей за авторством та співавторством студентів&ndash;&gt;-->
<!--&lt;!&ndash;                </th>&ndash;&gt;-->
<!--&lt;!&ndash;                <th>&ndash;&gt;-->
<!--&lt;!&ndash;                    Кількість статей за авторством та співавторством студентів&ndash;&gt;-->
<!--&lt;!&ndash;                </th>&ndash;&gt;-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>-->
<!--                    Всього-->
<!--                </td>-->
<!--                <td>-->
<!--                    Мають індекс Гірша за БД Scopus або WoS не нижче 10-->
<!--                </td>-->
<!--            </tr>-->
<!--        </table>-->
        <table id="exportRating" v-show="false" ref="exportR">
            <tr>
                <td colspan="2">
                    Кількість статей
                    за авторством та
                    співавторством студентів
                </td>

                <td>value</td>

            </tr>

            <tr>
                <td rowspan="2" >
                    Кількість публікацій у співавторстві
                    з іноземними партнерами
                </td>
                <td>
                    Всього
                </td>

                <td>value</td>

            </tr>
            <tr>
                <td>
                    Мають індекс Гірша за БД Scopus або WoS не нижче 10
                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="2">
                    Кількість публікацій всього
                    у тому числі:

                </td>


            </tr>

            <tr>
                <td colspan="2">
                    - підручників

                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="2">
                    - посібників

                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="2">
                    - монографій



                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="2">
                    - опублікованих за кордоном мовами ОЕСР та ЕС
                    проіндексовані БД Scopus або WoS
                    статей у фахових за статусом виданнях


                </td>

                <td>value</td>
            </tr>

            <tr>
                <td colspan="2">
                    - статей у фахових за статусом виданнях


                </td>

                <td>value</td>
            </tr>

        </table>
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
                    <td>{{ item.publications.type }}</td>
                    <td>{{ item.autors.name }}</td>
                    <td>{{ item.publications.title }}</td>
                    <td>{{ item.publications.date.slice(0,4) }}</td>
                    <td>{{ item.publications.sub_db_index }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    import XLSX from 'xlsx';
    export default {
        data() {
            return {
                fullSearch: false,
                data: [],

            };
        },
        components: {

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
            }
        },

    }
</script>

<style lang="scss" scoped>


</style>
