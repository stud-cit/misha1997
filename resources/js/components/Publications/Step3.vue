<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 5. Вид публікації
        </p>
        <div class="step-content categories">
            <template v-if="!$parent.stepData.scopus && !$parent.stepData.wos">

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
            <button class="prev" @click="prevStep">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step3",
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
                    'Посібник',

                ],
                stepData:{
                    publicationType: ''
                }

            }
        },
        computed: {
          scopusFilter(){
              return this.publicationView.slice(0,8);
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
