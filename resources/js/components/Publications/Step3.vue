<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 5. Вид публікації
        </p>
        <div class="step-content categories">
            <div class="categories-elem">
                <input id="type2" type="radio" v-model="stepData.publicationType" value="Стаття-доповідь у матеріалах наукових конференціях">
                <label for="type2">Стаття-доповідь у матеріалах наукових конференціях</label>
            </div>
            <div class="categories-elem">
                <input id="type4" type="radio" v-model="stepData.publicationType" value="Розділ монографії">
                <label for="type4">Розділ монографії</label>
            </div>
            <div class="categories-elem">
                <input id="type3" type="radio" v-model="stepData.publicationType" value="Монографії">
                <label for="type3">Монографії</label>
            </div>
            <div class="categories-elem">
                <input id="type5" type="radio" v-model="stepData.publicationType" value="Книга">
                <label for="type5">Книга</label>
            </div>
            <div class="categories-elem">
                <input id="type6" type="radio" v-model="stepData.publicationType" value="Розділ книги">
                <label for="type6">Розділ книги</label>
            </div>
            <div class="categories-elem">
                <input id="type7" type="radio" v-model="stepData.publicationType" value="Тези доповіді">
                <label for="type7">Тези доповіді</label>
            </div>
            <div class="categories-elem">
                <input id="type1" type="radio" v-model="stepData.publicationType" value="Стаття у фахових виданнях України">
                <label for="type1">Стаття у фахових виданнях України</label>
            </div>




            <div class="categories-elem">
                <input id="type8" type="radio" v-model="stepData.publicationType" value="Інші статті">
                <label for="type8">Інші статті</label>
            </div>
        </div>
        <div class="step-button-group">
            <button class="next active" @click="nextStep">Продовжити <span>&gt;</span></button>
            <button class="prev" @click="prevStep">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step3",
        data() {
            return {
                stepData:{
                    publicationType: ''
                }

            }
        },
        methods:{
            nextStep() {
                this.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$emit('prevStep');
            }
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>

    .categories{
        display: flex;
        flex-wrap: wrap;

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
            input[type="radio"]{
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
