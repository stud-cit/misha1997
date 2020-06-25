<template>
    <div>
        <div class="step-content">

            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.electronic.year">
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                </select>
            </div>
            <div class="step-item">
                <label class="item-title">Кількість томів</label>
                <input class="item-value" type="number" v-model="stepData.electronic.count_tomes">

            </div>
            <div class="step-item">
                <label class="item-title">Том</label>
                <input class="item-value" type="text" v-model="stepData.electronic.tome">

            </div>
            <div class="step-item">
                <label class="item-title">За редакцією</label>
                <input class="item-value" type="text" v-model="stepData.electronic.by_editing">

            </div>


            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.electronic.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" v-model="stepData.electronic.city">

            </div>
            <div class="step-item">
                <label class="item-title">Назва редакції</label>
                <input class="item-value" type="text" v-model="stepData.electronic.editing_name">

            </div>
            <div class="step-item">
                <label class="item-title">Кількість сторінок</label>
                <input class="item-value" type="text" v-model="stepData.electronic.count_pages">

            </div>
            <div class="step-item">
                <p class="item-title">Режим доступу</p>
                <div class="categories-elem">
                    <input id="access1" type="radio" v-model="stepData.electronic.access" value="1">
                    <label class="small-box" for="access1">відкритий</label>
                    <input id="access0" type="radio" v-model="stepData.electronic.access" value="0">
                    <label class="small-box" for="access0">закритий</label>
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

        data() {
            return {
                stepData:{
                    electronic:{
                        year: '',
                        count_tomes: '',
                        tome: '',
                        by_editing: '',
                        country: '',
                        city: '',
                        editing_name: '',
                        count_pages: '',
                        access: 0,
                        doi: ''

                    }
                }

            }
        },
        methods:{
            nextStep() {
                this.$parent.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            }
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>

    .categories-elem{
        display: flex;


        .small-box{
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
            &:first-of-type{
                margin-left: 0;
            }

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
    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
        text-align-last: center;
    }

</style>
