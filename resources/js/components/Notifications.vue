<template>


    <div >
        <h1 class="page-title">Перегляд публікацій</h1>
        <ul class="container notifications-list">
            <li class="notifications-item">
                <p class="date">23.04.2020</p>
                <p class="text">
                    Автор Бабій Євгеній Андрійович редагував дані
                    в публікації ”Modeling Business Process of Labor Intensity
                    Calculating The Machine-Building Equipment’s Production”
                </p>
            </li>
            <li class="notifications-item">
                <p class="date">23.04.2020</p>
                <p class="text">
                    Автор Бабій Євгеній Андрійович редагував дані
                    в публікації ”Modeling Business Process of Labor Intensity
                    Calculating The Machine-Building Equipment’s Production”
                </p>
            </li>

        </ul>

        <h2 class="subtitle">Попередні сповіщення</h2>

        <ul class="container notifications-list before">
            <li class="notifications-item">
                <p class="date">23.04.2020</p>
                <p class="text">
                    Автор Бабій Євгеній Андрійович редагував дані
                    в публікації ”Modeling Business Process of Labor Intensity
                    Calculating The Machine-Building Equipment’s Production”
                </p>
            </li>
            <li class="notifications-item">
                <p class="date">23.04.2020</p>
                <p class="text">
                    Автор Бабій Євгеній Андрійович редагував дані
                    в публікації ”Modeling Business Process of Labor Intensity
                    Calculating The Machine-Building Equipment’s Production”
                </p>
            </li>

        </ul>
    </div>


</template>

<script>
    export default {
        data() {
            return {

            };
        },

        created () {

        },
        computed: {

        },
        methods: {

        },

    }
</script>
<style scoped lang="scss">
    .subtitle{
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 20px;
        color: #A6A6A6;
        text-align: center;
        padding-bottom: 10px;
        border-bottom: 1px solid #A6A6A6;
    }
    .notifications-list{
        margin-top: 90px;
        .notifications-item{
            margin-bottom: 90px;
            .date{
                margin-bottom: 15px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #A6A6A6;
            }
            .text{
                font-family: Montserrat;
                font-style: normal;
                font-weight: 300;
                font-size: 25px;
                color: #18A0FB;

            }
        }
        &.before{
            .notifications-item{
                .text{
                    color: #A6A6A6;
                }
            }
        }
    }


</style>
