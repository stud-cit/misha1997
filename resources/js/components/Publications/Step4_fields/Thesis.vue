<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Назва конференції *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.name_conference">
                    <div class="hint" ><span>Вказати повну назву конференції</span></div>
                </div>
                <div class="error" v-if="$v.stepData.name_conference.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Рік видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Видавництво</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.editor_name">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.editor_name.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.pages">
                    <div class="hint" ><span></span></div>
                </div>
                <div class="error" v-if="$v.stepData.pages.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна видання *</label>
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
                <div class="error" v-if="$v.stepData.city.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">DOI</label>
                <div class="input-container hint-container">
                    <input class="item-value" type="text" v-model="stepData.doi">
                    <div class="hint" ><span></span></div>
                </div>
                <div class="error" v-if="$v.stepData.doi.$error">
                    Поле обов'язкове для заповнення
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
    import {required} from "vuelidate/lib/validators";

    export default {

        data() {
            return {
                country: [],
                stepData: {
                    year: new Date().getFullYear(),
                    name_conference: '',
                    editor_name: '',
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
        validations: {

            stepData: {

                name_conference: {
                    required
                },
                city: {
                    // required
                },
                editor_name: {
                    // required
                },
                pages: {
                    required
                },
                doi: {
                    // required
                },



            },



        },
        methods: {
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            nextStep() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
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
