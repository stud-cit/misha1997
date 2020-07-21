<template>
    <div>
        <div class="step-content">

            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.publication.year">
                    <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                </select>
            </div>
            <div class="step-item">
                <label class="item-title">Кількість томів</label>
                <input class="item-value" type="number" v-model="stepData.publication.number_volumes">

            </div>
            <div class="step-item">
                <label class="item-title">Том</label>
                <input class="item-value" type="text" v-model="stepData.publication.volume">

            </div>
            <div class="step-item">
                <label class="item-title">За редакцією</label>
                <input class="item-value" type="text" v-model="stepData.publication.by_editing">

            </div>


            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.publication.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" v-model="stepData.publication.city">

            </div>
            <div class="step-item">
                <label class="item-title">Назва редакції</label>
                <input class="item-value" type="text" v-model="stepData.publication.editor_name">

            </div>
            <div class="step-item">
                <label class="item-title">Кількість сторінок</label>
                <input class="item-value" type="text" v-model="stepData.publication.number_pages">

            </div>
            <div class="step-item">
                <p class="item-title">Опубліковано мовами ОЕСР та ЄС</p>
                <div class="categories-elem">
                    <input id="oesr_es1" type="radio" v-model="stepData.publication.languages" value="1">
                    <label class="small-box" for="oesr_es1">Так</label>
                    <input id="oesr_es0" type="radio" v-model="stepData.publication.languages" value="0">
                    <label class="small-box" for="oesr_es0">Ні</label>
                </div>
            </div>
            <div class="step-item">
                <p class="item-title">Режим доступу</p>
                <div class="categories-elem">
                    <input id="access1" type="radio" v-model="stepData.publication.access_mode" value="1">
                    <label class="small-box" for="access1">відкритий</label>
                    <input id="access0" type="radio" v-model="stepData.publication.access_mode" value="0">
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
                stepData: {
                    publication: {
                        year: '',
                        number_volumes: '',
                        volume: '',
                        by_editing: '',
                        country: '',
                        city: '',
                        editor_name: '',
                        number_pages: '',
                        languages: 0,
                        access_mode: 0
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

        },
        computed: {
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: year - 2000}, (value, index) => 2001 + index);
            },
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
