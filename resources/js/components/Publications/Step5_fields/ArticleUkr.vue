<template>
    <div>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва журналу</label>
                <input class="item-value" type="text" v-model="stepData.journal.name">

            </div>
            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.journal.year">
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                </select>


            </div>

            <div class="step-item">
                <label class="item-title">Номер (том)</label>
                <input class="item-value" type="text" v-model="stepData.journal.tome">

            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" v-model="stepData.journal.pages">

            </div>
            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.journal.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>

            <div class="step-item">
                <label class="item-title">DOI</label>
                <input class="item-value" type="text" v-model="stepData.journal.doi">

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
                    journal:{
                        name: '',
                        year: '',
                        tome: '',
                        pages: '',
                        country: '',
                        doi: '',
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

    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
    }

</style>
