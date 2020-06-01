<template>
    <div>
        <p class="subtitle">
            Крок 1 з 5. Назва та база для публікації
        </p>
        <div class="step-content">

            <div class="search-block">
                <div class="categories">
                    <div class="categories-elem">
                        <input id="scopus" type="checkbox" v-model="stepData.scopus">
                        <label for="scopus">scopus</label>
                    </div>

                    <div class="categories-elem">
                        <input id="wos" type="checkbox" v-model="stepData.wos">
                        <label for="wos">wos</label>
                    </div>
                </div>
                <label class="main-search-container">
                    <input type="text" class="main-search" placeholder="Введіть назву публікації" v-model="stepData.publicationTitle">
                    <div class="search-load">
                        <p class="load-big"></p>
                        <p class="load-little"></p>
                    </div>
<!--                    <div class="search-btn"><img src="/img/search.svg" alt=""></div>-->
                </label>
            </div>

        </div>
        <div class="button-group">
            <button class="next active">Продовжити <span>&gt;</span></button>
            <button class="back">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step1",
        data(){
            return {
                stepData:{
                    scopus: false,
                    wos: false,
                    publicationTitle: ''
                }

            }
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>
    .subtitle{
        margin-top: 8px;
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 25px;
        line-height: 30px;
        text-align: center;
        color: #A6A6A6;
    }
    .categories{
        display: flex;
        /*margin-bottom: 25px;*/
        .categories-elem{
            margin-right: 16px;
            margin-bottom: 25px;
            label{
                padding: 10px;
                background: #FFFFFF;
                border: 1px solid #18A0FB;
                border-radius: 44.5px;
                min-width: 260px;
                cursor: pointer;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 25px;
                text-align: center;
                color: #18A0FB;
                line-height: 1;
            }
            input[type="checkbox"]{
                display: none;
                &:checked + label{
                    background: #18A0FB;
                    border: 1px solid #18A0FB;
                    color: #fff;
                }
            }

        }
    }
    .button-group{
        margin-top: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        button{
            min-width: 292px;
            padding: 28px 10px;
            background: transparent;
            border: none;
            outline: none;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            line-height: 1;

        }
        .next{
            background: #C4C4C4;
            color: #fff;
            &.active{
                background: #7EF583;
            }
            span{
                vertical-align: middle;
            }
        }
        .back{
            font-family: Montserrat;
            font-style: normal;
            font-weight: 300;
            font-size: 25px;
            text-align: center;
            color: #A6A6A6;
        }
    }
</style>
