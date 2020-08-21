<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">№ свідоцтва/рішення</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.number_certificate">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна, де отриманий патент</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.country">
                        <option
                            v-for="(item, index) in country"
                            :key="index"
                            :value="item.name"
                        >{{item.name}}</option>
                    </select>

                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Заявник</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.applicant">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Дата подачі заявки</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.date_application">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Дата публікації про видачу свідоцтва/рішення</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.date_publication">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано університетом</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.commerc_university">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>

            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано штатними співробітниками університету</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.commerc_employees">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>

            </div>

        </div>
        <div class="step-button-group">
            <button class="prev" @click="prevStep">На попередній крок</button>
            <button class="next active" @click="nextStep">Продовжити </button>

        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                country: [],
                stepData: {
                    number_certificate: '',
                    country: 'Україна',
                    applicant: '',
                    date_application: '',
                    date_publication: '',
                    commerc_university: 0,
                    commerc_employees: 0
                }
            }
        },
        created() {
            this.getCountry();
        },
        methods:{
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
        }
    }
</script>

<style lang="scss" scoped>



</style>
