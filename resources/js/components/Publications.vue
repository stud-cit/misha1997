<template>

    <div class="container">
        <h1 class="page-title">Перегляд публікацій</h1>
        <div class="page-categories">
            <a href="" class="categories-link">Всі публікації</a>
            <a href="" class="categories-link">Мої публікації</a>
        </div>
        <div class="search-block">
            <input type="text" class="main-search">
            <div class="search-categories" v-if="fullSearch">
                <select name=""  class="search-select">
                    <option disabled selected>Рік видання</option>
                    <option value="2015">2015</option>
                </select>
                <select name=""  class="search-select">
                    <option disabled selected>Вид публікації</option>
                    <option value="2015">2015</option>
                </select>
                <select name=""  class="search-select">
                    <option disabled selected>Індексування</option>
                    <option value="2015">2015</option>
                </select>

            </div>
            <a href="" class="full-search" @click.prevent="fullSearch = !fullSearch">Розширений пошук</a>
        </div>
        <div class="table-bordered table-responsive text-center">

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
                    <th scope="row">1</th>
                    <td>тези</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>Нові технології</td>
                    <td>2016</td>
                    <td>2222</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>тези</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>Нові технології</td>
                    <td>2016</td>
                    <td>2222</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
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

<style scoped>

</style>
