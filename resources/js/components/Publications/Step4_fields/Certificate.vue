<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">№ свідоцтва/рішення *</label>
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
                <label class="item-title">Власник майнових прав *</label>
                <div class="input-container">

                    <select class="item-value" v-model="applicant_id">
                        <option value="0">СумДУ</option>
                        <option value="1">не  СумДУ</option>
                    </select>
                    <div class="hint" ><span></span></div>
                </div>

            </div>
            <div class="form-group" v-if="applicant_id == '1'">
                <label class="item-title">Вкажіть власника майнових прав не СумДУ *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.applicant">

                    <div class="hint" ><span></span></div>
                </div>
                <div class="error" v-if="$v.stepData.applicant.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
<!--            <div class="form-group">-->
<!--                <label class="item-title">Дата подачі заявки *</label>-->
<!--                <div class="input-container">-->
<!--                    <date-picker-->
<!--                        v-model="stepData.date_application"-->
<!--                        value-type="YYYY-MM-DD"-->
<!--                        :lang="datepicker.lang"-->
<!--                        :default-value="datepicker.minDate"-->
<!--                        :disabled-date="rangeDate"-->
<!--                        :editable="false"-->
<!--                        :popup-style="datepicker.styles"-->
<!--                    ></date-picker>-->
<!--                    <input style="display:none" class="item-value" type="text" v-model="stepData.date_application">-->
<!--                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>-->
<!--                </div>-->
<!--                <div class="error" v-if="$v.stepData.date_application.$error">-->
<!--                    Поле обов'язкове для заповнення-->
<!--                </div>-->
<!--            </div>-->
            <div class="form-group">
                <label class="item-title">Дата реєстрації свідоцтва/рішення *</label>
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
                    <input style="display:none" class="item-value" type="text" v-model="stepData.date_publication">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.date_publication.$error">
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
    import {required, requiredIf} from "vuelidate/lib/validators";
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    const nowDate = new Date();
    export default {

        data() {
            return {
                country: [],
                applicant_id: '0',
                stepData: {
                    number_certificate: '',
                    country: 'Україна',
                    applicant: '',

                    // date_application: '',
                    date_publication: '',
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
        components: {
            DatePicker
        },
        created() {
            this.getCountry();
        },
        validations: {

            stepData: {

                number_certificate: {
                    required
                },
                applicant: {
                    required: requiredIf( function() {
                        return this.applicant_id == '1';
                    }),
                },
                // date_application: {
                //     required
                // },
                date_publication: {
                    required
                },



            },



        },
        methods:{
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
                if(this.applicant_id == '0'){
                    this.stepData.applicant = 'СумДУ';
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
