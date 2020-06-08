<template>
    <div>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва журналу</label>
                <input class="item-value" type="text" v-model="stepData.journal_name">

            </div>
            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">Кількість томів</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">Номер (том)</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">DOI</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">За редакцією</label>
                <input class="item-value" type="text" >

            </div>
            <div class="step-item">
                <label class="item-title">За редакцією</label>
                <input class="item-value" type="text" >

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
                    journal_name: ''
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



</style>
