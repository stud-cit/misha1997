<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Рік видання</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>

                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">За редакцією</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.by_editing">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна видання</label>
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
                <label class="item-title">Місто видання</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.city">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Назва редакції</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.editor_name">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.pages">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Опубліковано мовами ОЕСР та ЄС</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.languages">
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
                    year: new Date().getFullYear(),
                    // number: '',
                    by_editing: '',
                    country: 'Україна',
                    city: '',
                    editor_name: '',
                    pages: '',
                    languages: 0
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



</style>
