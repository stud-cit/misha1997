<template>
    <div>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва конференції</label>
                <input class="item-value" type="text" v-model="stepData.publication.name_conference">

            </div>
            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.publication.year">
                    <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                </select>


            </div>

            <div class="step-item">
                <label class="item-title">Видавництво</label>
                <input class="item-value" type="text" v-model="stepData.publication.publisher">

            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" v-model="stepData.publication.pages">

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
                <label class="item-title">DOI</label>
                <input class="item-value" type="text" v-model="stepData.publication.doi">

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
                    publication: {
                        year: new Date().getFullYear(),
                        name_conference: '',
                        publisher: '',
                        pages: '',
                        country: '',
                        city: '',
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

        },
        computed: {
            years() {
                const year = new Date().getFullYear();
                return Array.from({length: year - 2000}, (value, index) => 2001 + index);
            },
        },
    }
</script>

<style lang="scss" scoped>

    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
        text-align-last: center;
    }

</style>
