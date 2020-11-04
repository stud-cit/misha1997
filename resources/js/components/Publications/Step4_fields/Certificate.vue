<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">№ свідоцтва/рішення *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.number_certificate">
                </div>
                <div class="error" v-if="$v.publicationData.number_certificate.$error">
                    Введіть лише цифри
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна, де отримане свідоцтво *</label>
                <div class="input-container">
                    <select class="item-value" v-model="publicationData.country">
                        <option
                            v-for="(item, index) in country"
                            :key="index"
                            :value="item.name"
                        >{{item.name}}</option>
                    </select>
                </div>
                <div class="error" v-if="$v.publicationData.country.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Власник майнових прав *</label>
                <div class="input-container">
                    <select class="item-value" v-model="applicant_id">
                        <option value="0">СумДУ</option>
                        <option value="1">не  СумДУ</option>
                    </select>
                </div>
            </div>
            <div class="form-group" v-if="applicant_id == '1'">
                <label class="item-title">Вкажіть власника майнових прав не СумДУ *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="newApplicant">
                </div>
                <div class="error" v-if="$v.newApplicant.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Дата подачі заявки *</label>
                <div class="input-container">
                    <date-picker
                        v-model="publicationData.date_application"
                        value-type="YYYY-MM-DD"
                        :lang="datepicker.lang"
                        :editable="false"
                        :popup-style="datepicker.styles"
                    ></date-picker>
                    <input style="display:none" class="item-value" type="text" v-model="publicationData.date_application" required>
                </div>
                <div class="error" v-if="$v.publicationData.date_application.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Дата публікації про видачу свідоцтва/рішення *</label>
                <div class="input-container">
                    <date-picker
                        v-model="publicationData.date_publication"
                        value-type="YYYY-MM-DD"
                        :lang="datepicker.lang"
                        :editable="false"
                        :popup-style="datepicker.styles"
                    ></date-picker>
                    <input style="display:none" class="item-value" type="text" v-model="publicationData.date_publication" required>
                </div>
                <div class="error" v-if="$v.publicationData.date_publication.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано університетом *</label>
                <div class="input-container hint-container">
                    <select class="item-value" v-model="publicationData.commerc_university">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>"так" -у разі надходження коштів на рахунок університету</span></div>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Комерціалізовано штатними співробітниками університету *</label>
                <div class="input-container hint-container">
                    <select class="item-value" v-model="publicationData.commerc_employees">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>"так" - при отриманні коштів автором - штатним співробітником університету</span></div>
                </div>
            </div>
        </div>
        <div class="step-button-group">
            <button class="prev" @click="prevStep">На попередній крок</button>
            <button class="next active" @click="nextStep">Продовжити</button>
            <close-edit-button v-if="$route.name == 'publications-edit'"></close-edit-button>
        </div>
    </div>
</template>

<script>
    import CloseEditButton from "../../Buttons/CloseEdit";
    import country from '../../mixins/country';
    import {required, requiredIf} from "vuelidate/lib/validators";
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';
    export default {
        mixins: [country],
        data() {
            return {
                applicant_id: '0',
                newApplicant: '',
                datepicker: {
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
            DatePicker,
            CloseEditButton
        },
        props: {
            publicationData: Object
        },
        validations: {
            publicationData: {
                number_certificate: {
                    required,
                    validFormat: val => /^\d{1,}$/.test(val),
                },
                applicant: {
                    required: requiredIf( function() {
                        return this.applicant_id == '1';
                    }),
                },
                date_publication: {
                    required
                },
                date_application: {
                    required
                },
                country: {
                    required
                },
            },
        },
        methods:{
            nextStep() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                if(this.applicant_id == '0'){
                    this.publicationData.applicant = 'СумДУ';
                }
                this.publicationData.applicant = this.applicant_id ? "СумДУ" : this.newApplicant;
                this.$parent.$emit('getData', this.publicationData);
            },
            prevStep() {
                this.$parent.$emit('prevStep');
            }
        }
    }
</script>

<style lang="scss" scoped>
    .mx-datepicker {
        width: 100%;
    }
</style>
