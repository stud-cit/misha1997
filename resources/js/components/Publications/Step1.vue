<template>
    <div>
        <p class="step-subtitle">
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
                stepData:{
                    scopus: false,
                    wos: false,
                    publicationTitle: '',
                }

            }
        },
        methods:{
            nextStep() {
                this.$emit('getData', this.stepData);
            },

        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>

    .search-block{
        margin: 0;
    }
    .categories{
        display: flex;
        /*margin-bottom: 25px;*/
        .categories-elem{
            margin-right: 15px;
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

</style>
