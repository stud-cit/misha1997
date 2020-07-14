<template>
    <div>
        <p class="step-subtitle">
            Крок 1 з 4. Назва та база для публікації
        </p>
        <div class="step-content">

            <div class="search-block">
                <div class="categories">
                    <div class="categories-elem">
                        <input id="scopus" type="checkbox" v-model="stepData.scopus">
                        <label for="scopus" class="scopus">scopus</label>
                    </div>

                    <div class="categories-elem">
                        <input id="wos" type="checkbox" v-model="stepData.wos">
                        <label for="wos" class="scopus">wos</label>
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
        <div class="categories" v-if="stepData.publicationTitle">
            <p class="step-subtitle">
                Оберіть тип публікації
            </p>
            <template v-if="!stepData.scopus && !stepData.wos">

                <div class="categories-elem"  v-for="(item, i) in publicationView" :key="i" >
                    <input :id="'type' + i" type="radio" v-model="stepData.publicationType" :value="item">
                    <label :for="'type' + i">{{ item }}</label>
                </div>

            </template>
            <template v-else>

                <div class="categories-elem"  v-for="(item, i) in scopusFilter" :key="i" >
                    <input :id="'type' + i" type="radio" v-model="stepData.publicationType" :value="item">
                    <label :for="'type' + i">{{ item }}</label>
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
                    'Стаття-доповідь у матеріалах наукових конференціях',
                    'Розділ монографії',
                    'Монографія',
                    'Книга',
                    'Розділ книги',
                    'Тези доповіді',
                    'Стаття у фахових виданнях України',
                    'Інші статті',
                    'Методичні вказівки',
                    'Свідоцтво про реєстрації авторських прав на твір/рішення',
                    'Електронні видання',
                    'Патент',
                    'Посібник'
                ],
                stepData:{
                    scopus: false,
                    wos: false,
                    publicationTitle: '',
                    publicationType: ''
                }

            }
        },
        computed: {

        //   первые 8 по скопус, воз

          scopusFilter() {
              return this.publicationView.slice(0,8);
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
