<template>
    <div>
        <div class="step-content">
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
                <label class="item-title">Кількість томів </label>
                <div class="input-container">
                    <input class="item-value" type="number" v-model="stepData.number_volumes">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.number_volumes.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Том </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.number">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.number.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">За редакцією </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.by_editing">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.by_editing.$error">
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
                <label class="item-title">Місто видання </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.city">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.city.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Видавництво </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.editor_name">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.editor_name.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>

            <div class="form-group">
                <label class="item-title">Кількість сторінок *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.pages">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.pages.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Електронна адреса (url) *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.url">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.url.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label class="item-title">Опубліковано мовами ОЕСР *</label>-->
<!--                <div class="input-container hint-container">-->
<!--                    <select class="item-value" v-model="stepData.languages">-->
<!--                        <option value="1">Так </option>-->
<!--                        <option value="0">Ні </option>-->
<!--                    </select>-->
<!--                    <div class="hint" ><span>Опубліковано мовою країн організації економічного співробітництва та розвитку</span></div>-->
<!--                </div>-->

<!--            </div>-->
            <div class="form-group">
                <label class="item-title">Режим доступу *</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.access_mode">
                        <option value="1">Відкритий </option>
                        <option value="0">Закритий </option>
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
    import {required} from "vuelidate/lib/validators";

    export default {

        data() {
            return {
                country: [],
                stepData: {
                    year: new Date().getFullYear(),
                    number_volumes: '',
                    number: '',
                    by_editing: '',
                    country: 'Україна',
                    city: '',
                    editor_name: '',
                    pages: '',
                    // languages: 0,
                    access_mode: 1,
                    url: ''
                }
            }
        },
        created() {
            this.getCountry();
        },
        validations: {

            stepData: {

                number_volumes: {
                    // required
                },
                number: {
                    // required
                },
                by_editing: {
                    // required
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
                url: {
                    required
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
        }
    }
</script>

<style lang="scss" scoped>



</style>
