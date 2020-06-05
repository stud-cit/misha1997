<template>
    <div>
        <p class="step-subtitle">
            Крок 5 з 5. Вихідні дані
        </p>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва журналу</label>
                <input class="item-value" type="text" >

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
        name: "Step5",
        data() {
            return {
                stepData:{

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



</style>
