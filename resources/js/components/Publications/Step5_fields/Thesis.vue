<template>
    <div>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва конференції</label>
                <input class="item-value" type="text" v-model="stepData.thesis.name">

            </div>
            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.thesis.year">
                    <option value="2020">2020</option>
                    <option value="2019">2019</option>
                </select>


            </div>

            <div class="step-item">
                <label class="item-title">Видавництво</label>
                <input class="item-value" type="text" v-model="stepData.thesis.edition">

            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" v-model="stepData.thesis.pages">

            </div>
            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.thesis.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" v-model="stepData.thesis.city">

            </div>

            <div class="step-item">
                <label class="item-title">DOI</label>
                <input class="item-value" type="text" v-model="stepData.thesis.doi">

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
                    thesis:{
                        name: '',
                        year: '',
                        edition: '',
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

        }
    }
</script>

<style lang="scss" scoped>

    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
        text-align-last: center;
    }

</style>
