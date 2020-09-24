<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">№ патенту *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.number_certificate">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.number_certificate.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна, де отриманий патент *</label>
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
                <label class="item-title">МПК *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.mpk">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.mpk.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Заявник *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.applicant">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.applicant.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">№ заявки *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.application_number">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.application_number.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-row">
                <div class="col-lg-6 form-group">
                    <label class="item-title">Дата подачі заявки *</label>
                    <div class="input-container">

                        <date-picker
                            v-model="stepData.date_application"
                            value-type="YYYY-MM-DD"
                            :lang="datepicker.lang"
                            :default-value="datepicker.minDate"
                            :disabled-date="rangeDate"
                            :editable="false"
                            :popup-style="datepicker.styles"
                        ></date-picker>
                            <input style="display:none" class="item-value" type="text" v-model="stepData.date_application" required>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                    <div class="error" v-if="$v.stepData.date_application.$error">
                        Поле обов'язкове для заповнення
                    </div>
                </div>
                <div class="col-lg-6 form-group">
                    <label class="item-title">Дата публікації про видачу патенту *</label>
                    <div class="input-container">

                        <date-picker
                            v-model="stepData.date_publication"
                            value-type="YYYY-MM-DD"
                            :lang="datepicker.lang"
                            :default-value="datepicker.minDate"
                            :disabled-date="rangeDate"
                            :editable="false"
                            :popup-style="datepicker.styles"
                        ></date-picker>
                            <input style="display:none" class="item-value" type="text" v-model="stepData.date_publication" required>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                    <div class="error" v-if="$v.stepData.date_publication.$error">
                        Поле обов'язкове для заповнення
                    </div>
                </div>

            </div>
            <div class="form-group">
                <label class="item-title">№ бюлетеня *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.newsletter_number">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.newsletter_number.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано університетом *</label>
                <div class="input-container hint-container">
                    <select class="item-value" v-model="stepData.commerc_university">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>"так" -у разі надходження коштів на рахунок університету</span></div>
                </div>

            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано штатними співробітниками університету *</label>
                <div class="input-container hint-container">
                    <select class="item-value" v-model="stepData.commerc_employees">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>"так" - при отриманні коштів автором - штатним співробітником університету</span></div>
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
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    import {required} from "vuelidate/lib/validators";

    const nowDate = new Date();

    export default {
        components: {
            DatePicker
        },
        data() {
            return {
                country: [],
                stepData: {
                    number_certificate: '',
                    country: 'Україна',
                    mpk: '',
                    applicant: '',
                    application_number: '',
                    date_application: '',
                    date_publication: '',
                    newsletter_number: '',
                    commerc_university: 0,
                    commerc_employees: 0
                },
                datepicker: {
                    minDate: new Date(String(nowDate.getFullYear()-18) + '-' + String(nowDate.getMonth()+1) + '-' + nowDate.getDate()).setHours(0, 0, 0, 0),
                    maxDate: new Date(String(nowDate.getFullYear()) + '-' + String(nowDate.getMonth()+1) + '-' + nowDate.getDate()).setHours(0, 0, 0, 0),
                    lang: {
                        formatLocale: {
                            months: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
                            monthsShort: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
                            weekdays: ["Неділя", "Понеділок", "Вівторок", "Середа", "Четвер", "П'ятниця", "Субота"],
                            weekdaysShort: ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'НД'],
                            weekdaysMin: ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'НД']
                        }
                    },
                    styles: { left: '30%' }
                },
            }
        },
        created() {
            this.getCountry();
        },
        validations: {

            stepData: {

                number_certificate: {
                    required
                },
                mpk: {
                    required
                },
                applicant: {
                    required
                },
                application_number: {
                    required
                },
                date_application: {
                    required
                },
                date_publication: {
                    required
                },
                newsletter_number: {
                    required
                },
            }



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
            },
            rangeDate(date) {
                return date <= this.datepicker.minDate || date >= this.datepicker.maxDate;
            },
        }
    }
</script>

<style lang="scss" scoped>



    .mx-datepicker {
        width: 100%;
    }


</style>
