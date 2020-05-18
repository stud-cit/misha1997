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
        <div class="table-bordered table-responsive text-center table-list">

            <table class="table table-bordered ">
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
                <tr>
                    <td scope="row">1</td>
                    <td>тези</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>Нові технології</td>
                    <td>2016</td>
                    <td>2222</td>
                </tr>
                <tr>
                    <td scope="row">2</td>
                    <td>тези</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>Нові технології</td>
                    <td>2016</td>
                    <td>2222</td>
                </tr>
                <tr>
                    <td scope="row">3</td>
                    <td>тези</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>Нові технології</td>
                    <td>2016</td>
                    <td>2222</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                fullSearch: false,

            };
        },
        components: {

        },
        created () {

        },
        methods: {



        },

    }
</script>

<style lang="scss" scoped>


</style>
