<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 4. Назва та база для публікації
        </p>

        <div class=" step-content">
            <div class=" step-item ">
                <p class="item-title">Коефіцієнт впливовості SNIP журналу SCOPUS :</p>
                <input type="number" class="item-input" v-model="stepData.snip">
            </div>
            <div class=" step-item">
                <p class="item-title">Коефіцієнт впливовості імпакт-фактор журналу WoS :</p>
                <input type="text" class="item-input" v-model="stepData.impact_factor">
            </div>
            <div class="step-item">
                <p class="item-title">Квартиль журналу БД Scopus :</p>
                <div class="categories-elem" v-for="n in 4" :key="n">
                    <input :id="'scopus' + n" type="radio" v-model="stepData.quartil_scopus" :value="n">
                    <label class="small-box" :for="'scopus' + n">{{n}}</label>
                </div>
            </div>
            <div class="step-item">
                <p class="item-title">Квартиль журналу БД WoS :</p>
                <div class="categories-elem" v-for="n in 4" :key="n">
                    <input :id="'wos' + n" type="radio" v-model="stepData.quartil_wos" :value="n">
                    <label class="small-box" :for="'wos' + n">{{n}}</label>
                </div>
            </div>
            <div class=" step-item">
                <p class="item-title">Підбаза WoS :</p>
                <input id="subDb0" type="radio" class="d-none" v-model="stepData.sub_db_index" value="0" >
                <div class="categories-elem" >
                    <input id="subDb1" type="checkbox" v-model="subWos1"  >
                    <label class="big-box" for="subDb1" @click="changeType(1)">Science Citation Index Expanded (SCIE) </label>
                </div>
                <div class="categories-elem" >
                    <input id="subDb2" type="checkbox" v-model="subWos2"  >
                    <label class="big-box" for="subDb2" @click="changeType(2)">Social Science Citation Index</label>
                </div>
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
        name: "Step4",
        data() {
            return {
                subWos1: false,
                subWos2: false,

                stepData:{
                    snip: '',
                    impact_factor: '',
                    quartil_scopus: null,
                    quartil_wos: null,
                    sub_db_index: 0
                }

            }
        },
        methods:{
            nextStep() {
                this.stepData.sub_db_index = this.subWos1 ? 1 : this.subWos2 ? 2 : 0;
                this.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$emit('prevStep');
            },
            changeType(type){
                if(type == 1 && this.subWos1 == false && this.subWos2 == true){
                    this.subWos2 = false;
                }
                else if(type == 2 && this.subWos1 == true && this.subWos2 == false){
                    this.subWos1 = false;
                }



            },


        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>


    .step-item{
        margin-top: 50px;
        display: flex;
        align-items: center;
        &:first-of-type{
            margin-top: 0;
        }
        .item-title{
            width: auto;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: #A6A6A6;
            margin-right: 20px;
            margin-bottom: 0;
        }
        .item-input{
            padding: 12px;
            text-align: center;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.25);
            border-radius: 44.5px;
            border: none;
            outline: none;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: #0E0E0E;
            min-width: 258px;

        }
        .categories-elem{
            display: flex;

            .small-box{
                width: 31px;
                padding: 4px 0;
                margin: 0 10px;
                border: 1px solid #18A0FB;
                box-sizing: border-box;
                border-radius: 10px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #18A0FB;
                text-align: center;
                cursor: pointer;
            }
            .big-box{
                padding: 10px 40px;
                margin: 0 10px;
                border: 1px solid #18A0FB;
                box-sizing: border-box;
                border-radius: 44.5px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 20px;
                color: #18A0FB;
                cursor: pointer;

            }
            input{
                display: none;
            }
            input:checked +label{
                background: #18A0FB;
                border: 1px solid #18A0FB;
                color: #fff;
            }
        }
    }


</style>
