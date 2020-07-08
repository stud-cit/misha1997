<template>
    <div>
        <div class="step-content">

            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.bookPart.year">
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                </select>
            </div>

            <div class="step-item">
                <label class="item-title">Том</label>
                <input class="item-value" type="text" v-model="stepData.bookPart.tome">

            </div>
            <div class="step-item">
                <label class="item-title">За редакцією</label>
                <input class="item-value" type="text" v-model="stepData.bookPart.by_editing">

            </div>


            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.bookPart.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" v-model="stepData.bookPart.city">

            </div>
            <div class="step-item">
                <label class="item-title">Назва редакції</label>
                <input class="item-value" type="text" v-model="stepData.bookPart.editing_name">

            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" v-model="stepData.bookPart.pages">

            </div>
            <div class="step-item">
                <p class="item-title">Опубліковано мовами ОЕСР та ЄС</p>
                <div class="categories-elem">
                    <input id="oesr_es1" type="radio" v-model="stepData.bookPart.oesr_es" value="1">
                    <label class="small-box" for="oesr_es1">Так</label>
                    <input id="oesr_es0" type="radio" v-model="stepData.bookPart.oesr_es" value="0">
                    <label class="small-box" for="oesr_es0">Ні</label>
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
                    bookPart:{
                        year: '',
                        tome: '',
                        by_editing: '',
                        country: '',
                        city: '',
                        editing_name: '',
                        pages: '',
                        oesr_es: 0,


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
