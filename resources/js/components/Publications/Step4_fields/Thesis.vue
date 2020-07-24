<template>
    <div>
        <div class="step-content">
            <div class="step-item">
                <label class="item-title">Назва конференції</label>
                <input class="item-value" type="text" v-model="stepData.name_conference">
            </div>
            <div class="step-item">
                <label class="item-title">Рік видання</label>
                <select class="item-value" v-model="stepData.year">
                    <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                </select>
            </div>
            <div class="step-item">
                <label class="item-title">Видавництво</label>
                <input class="item-value" type="text" v-model="stepData.publisher">
            </div>
            <div class="step-item">
                <label class="item-title">Сторінки</label>
                <input class="item-value" type="text" v-model="stepData.pages">
            </div>
            <div class="step-item">
                <label class="item-title">Країна видання</label>
                <select class="item-value" v-model="stepData.country">
                    <option
                        v-for="(item, index) in country"
                        :key="index"
                        :value="item.name"
                    >{{item.name}}</option>
                </select>
            </div>
            <div class="step-item">
                <label class="item-title">Місто видання</label>
                <input class="item-value" type="text" v-model="stepData.city">
            </div>
            <div class="step-item">
                <label class="item-title">DOI</label>
                <input class="item-value" type="text" v-model="stepData.doi">
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
                country: [],
                stepData: {
                    year: new Date().getFullYear(),
                    name_conference: '',
                    publisher: '',
                    pages: '',
                    country: 'Україна',
                    city: '',
                    doi: ''
                }
            }
        },
        created() {
            this.getCountry();
        },
        methods: {
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            nextStep() {
                this.$parent.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            }
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
