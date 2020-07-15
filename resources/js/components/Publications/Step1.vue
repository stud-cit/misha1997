<template>
    <div>
        <p class="step-subtitle">
            Крок 1 з 4. Назва та база для публікації
        </p>
        <div class="step-content">

            <div class="search-block">
                <div class="categories">
                    <div class="categories-elem">
                        <input id="scopus" type="radio" v-model="stepData.science_type_id" value="1">
                        <label for="scopus">Scopus</label>
                    </div>
                    <div class="categories-elem">
                        <input id="wos" type="radio" v-model="stepData.science_type_id" value="2">
                        <label for="wos">WoS</label>
                    </div>
                    <div class="categories-elem">
                        <input id="scopus_wos" type="radio" v-model="stepData.science_type_id" value="3">
                        <label for="scopus_wos">Scopus та WoS</label>
                    </div>
                </div>
                <label class="main-search-container">
                    <input type="text" class="main-search" placeholder="Введіть назву публікації" v-model="stepData.title">
                    <div class="search-load">
                        <p class="load-big"></p>
                        <p class="load-little"></p>
                    </div>
<!--                    <div class="search-btn"><img src="/img/search.svg" alt=""></div>-->
                </label>
            </div>

        </div>
        <div class="categories" v-if="stepData.title">
            <p class="step-subtitle">
                Оберіть тип публікації
            </p>
            <template v-if="stepData.science_type_id">
                <div class="categories-elem" v-for="(item, i) in publicationView" :key="i">
                    <input v-if="item.scopus_wos" :id="'type' + i" type="radio" v-model="stepData.type" :value="item">
                    <label v-if="item.scopus_wos" :for="'type' + i">{{ item.title }}</label>
                </div>
            </template>
            <template v-else>
                <div class="categories-elem" v-for="(item, i) in publicationView" :key="i">
                    <input :id="'type' + i" type="radio" v-model="stepData.type" :value="item">
                    <label :for="'type' + i">{{ item.title }}</label>
                </div>
            </template>
        </div>
        <div class="step-button-group">
            <button class="next active" @click="nextStep">Продовжити <span>&gt;</span></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step1",
        data() {
            return {
                publicationView: [
                    {
                        scopus_wos: true,
                        key: "articles",
                        title: 'Стаття у фахових виданнях України'
                    },
                    {
                        scopus_wos: true,
                        key: "articles",
                        title: 'Стаття-доповідь у матеріалах наукових конференціях'
                    },
                    {
                        scopus_wos: true,
                        key: "articles",
                        title: 'Інші статті'
                    },
                    {
                        scopus_wos: true,
                        key: "monographs",
                        title: 'Монографія'
                    },
                    {
                        scopus_wos: true,
                        key: "textbooks",
                        title: 'Книга'
                    },
                    {
                        scopus_wos: true,
                        key: "monographs",
                        title: 'Розділ монографії'
                    },
                    {
                        scopus_wos: true,
                        key: "textbooks",
                        title: 'Розділ книги'
                    },
                    {
                        scopus_wos: true,
                        key: "abstracts",
                        title: 'Тези доповіді'
                    },
                    {
                        scopus_wos: false,
                        key: "manuals",
                        title: 'Посібник'
                    },
                    {
                        scopus_wos: false,
                        key: "electronic_publications",
                        title: 'Електронні видання'
                    },
                    {
                        scopus_wos: false,
                        key: "methodical_instructions",
                        title: 'Методичні вказівки'
                    },
                    {
                        scopus_wos: false,
                        key: "certificates",
                        title: 'Свідоцтво про реєстрації авторських прав на твір/рішення'
                    }, 
                    {
                        scopus_wos: false,
                        key: "patents",
                        title: 'Патент'
                    }
                ],
                stepData: {
                    science_type_id: null,
                    title: '',
                    type: ''
                }
            }
        },
        methods: {
            nextStep() {
                this.$emit('getData', this.stepData);
            }
        }
    }
</script>

<style lang="scss" scoped>

    .search-block{
        margin: 0;
    }
    .categories{
        display: flex;
        flex-wrap: wrap;
        .step-subtitle{
            width: 100%;
            margin-bottom: 25px;
            margin-top: 75px;
        }

        .categories-elem{
            margin-right: 15px;
            margin-bottom: 20px;
            label{
                padding: 15px 35px;
                background: #FFFFFF;
                border: 1px solid #18A0FB;
                border-radius: 44.5px;
                /*min-width: 260px;*/
                cursor: pointer;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 22px;
                text-align: center;
                color: #18A0FB;
                line-height: 1;
            }
            .scopus{
                min-width: 260px;
            }
            input[type="radio"]{
                display: none;
                &:checked + label{
                    background: #18A0FB;
                    border: 1px solid #18A0FB;
                    color: #fff;
                }
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
</style>
