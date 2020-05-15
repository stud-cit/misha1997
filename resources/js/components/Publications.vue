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
        <div class="table-bordered table-responsive text-center table-publications">

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
    .page-categories{
        display: flex;
        justify-content: center;
        .categories-link{
            margin: 30px 45px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: #C4C4C4;

            &.active{
                border-bottom: 1px solid #18A0FB;
                color: #18A0FB;
            }
            &:active, &:hover{
                text-decoration: none;
                color: #18A0FB;
            }
        }
    }
    .search-block{
        margin-top: 100px;
        text-align: center;
        .main-search-container{
            position:relative;
            width: 100%;
            .main-search{
                width: 100%;
                box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.25);
                border-radius: 44.5px;
                padding: 30px 150px;
                border: none;
                outline: none;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 25px;
                color: #0E0E0E;
                &::placeholder{
                    font-family: Montserrat;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 25px;
                    color: rgba(166, 166, 166, 0.5);
                }
            }
            .search-load{
                position: absolute;
                left: 40px;
                top: 32px;
                display: flex;
                align-items: center;
                flex-direction: column;
                .load-big{
                    width: 75px;
                    height: 10px;
                    background: #C4C4C4;
                }
                .load-little{
                    margin-top: 5px;
                    width: 49px;
                    height: 10px;
                    background: rgba(196, 196, 196, 0.5);
                }
            }
            .search-btn{
                position: absolute;
                padding: 5px;
                right: 45px;
                top: 22px;
                cursor: pointer;
                img{
                    width: 35px;
                    height: 35px;

                }
            }
        }
        .full-search{

            width: 100%;
            text-align: center;
            display: inline-block;
            .search-categories{
                display: flex;
                justify-content: space-between;
                margin: 75px 0 25px 0;
                .search-select{
                    width: 30%;
                    margin-right: 10px;
                    padding: 15px 20px;
                    padding-right: 50px;
                    text-align: center;
                    text-align-last: center;
                    background: #FFFFFF;
                    border: 1px solid #A6A6A6;
                    box-sizing: border-box;
                    border-radius: 44.5px;
                    outline: none;
                    -moz-appearance:none; /* Firefox */
                    -webkit-appearance:none; /* Safari and Chrome */
                    appearance:none;
                    background: url("/img/arrow-down.png") no-repeat 92% 50%;
                    font-family: Montserrat;
                    font-style: normal;
                    font-weight: normal;
                    font-size: 25px;
                    color: #A6A6A6;
                    option{
                        color: #0E0E0E;
                    }
                    &:last-child{
                        margin-right: 0;
                    }

                }
            }
            .full-search-btn{
                margin-top: 50px;
                padding-right: 26px;
                display: inline-block;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #A6A6A6;
                background: none;
                border: none;
                outline: none;
                position: relative;
                &::before{
                    content: "";
                    position: absolute;
                    right: 0;
                    bottom: 2px;
                    width: 0;
                    height: 0;
                    border-top: 12px solid #C4C4C4;
                    border-left: 7px solid transparent;
                    border-right: 7px solid transparent;
                    transition: all 0.2s;
                }
                &.active::before{
                    transform: rotate(180deg);
                }

            }
        }
    }
    .table-publications{
        margin-top: 100px;
        table{
            margin: 0;
            th{
                font-family: Montserrat;
                font-style: normal;
                font-weight: bold;
                font-size: 20px;
                color: #A6A6A6;
            }
            td{
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #A6A6A6;
            }
        }
    }

</style>
