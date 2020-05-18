<template>

    <div class="container">
        <h1 class="page-title">Список користувачів сервісу</h1>

        <div class="search-block">
            <label class="main-search-container">
                <input type="text" class="main-search" placeholder="Пошук користувача за ПІБ">
                <div class="search-load">
                    <p class="load-big"></p>
                    <p class="load-little"></p>
                </div>
                <div class="search-btn"><img src="img/search.svg" alt=""></div>
            </label>

        </div>
        <div class="table-bordered table-responsive text-center table-list">

            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Роль</th>
                    <th scope="col">Прізвище, Ім’я, По-батькові</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Факультет</th>
                    <th scope="col">Кафедра</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td scope="row">1</td>
                    <td>автор</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>e.petrnko@mai.co</td>
                    <td>Фаультет фызичного виховання</td>
                    <td>Кафедра фізкультури</td>
                </tr>
                <tr>
                    <td scope="row">1</td>
                    <td>автор</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>e.petrnko@mai.co</td>
                    <td>Фаультет фызичного виховання</td>
                    <td>Кафедра фізкультури</td>
                </tr>
                <tr>
                    <td scope="row">1</td>
                    <td>автор</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>e.petrnko@mai.co</td>
                    <td>Фаультет фызичного виховання</td>
                    <td>Кафедра фізкультури</td>
                </tr>
                <tr>
                    <td scope="row">1</td>
                    <td>автор</td>
                    <td>Петренко Пилип Вікторович</td>
                    <td>e.petrnko@mai.co</td>
                    <td>Фаультет фызичного виховання</td>
                    <td>Кафедра фізкультури</td>
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
