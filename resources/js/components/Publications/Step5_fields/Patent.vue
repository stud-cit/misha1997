<template>
    <div>
        <div class="step-content">


            <div class="step-item">
                <label class="item-title">№ патенту</label>
                <input class="item-value" type="text" v-model="stepData.publication.number">

            </div>
            <div class="step-item">
                <label class="item-title">Країна, де отриманий патент</label>
                <select class="item-value" v-model="stepData.publication.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>
            <div class="step-item">
                <label class="item-title">МПК</label>
                <input class="item-value" type="text" v-model="stepData.publication.mpk">

            </div>
            <div class="step-item">
                <label class="item-title">Заявник</label>
                <input class="item-value" type="text" v-model="stepData.publication.applicant">

            </div>



            <div class="step-item">
                <label class="item-title">№ заявки</label>
                <input class="item-value" type="text" v-model="stepData.publication.application_number">

            </div>
            <div class="step-item">
                <label class="item-title">Дата подачі заявки</label>
                <div class="input-container">
                    <img src="/img/step2-data.png" class="input-img">
                    <date-picker
                        v-model="stepData.publication.date_application"
                        value-type="YYYY-MM-DD"
                        :lang="datepicker.lang"
                        :default-value="datepicker.minDate"
                        :disabled-date="rangeDate"
                        :editable="false"
                        :popup-style="datepicker.styles"
                    ></date-picker>
                    <input style="display:none" class="item-value" type="text" v-model="stepData.publication.date_application" required>
                </div>
            </div>
            <div class="step-item">
                <label class="item-title">Дата публікації про видачу патенту</label>
                <div class="input-container">
                    <img src="/img/step2-data.png" class="input-img">
                    <date-picker
                        v-model="stepData.publication.date_publication"
                        value-type="YYYY-MM-DD"
                        :lang="datepicker.lang"
                        :default-value="datepicker.minDate"
                        :disabled-date="rangeDate"
                        :editable="false"
                        :popup-style="datepicker.styles"
                    ></date-picker>
                    <input style="display:none" class="item-value" type="text" v-model="stepData.publication.date_publication" required>
                </div>
            </div>
            <div class="step-item">
                <label class="item-title">№ бюлетеня</label>
                <input class="item-value" type="text" v-model="stepData.publication.newsletter_number">

            </div>
            <div class="step-item">
                <p class="item-title">Комерціалізовано університетом</p>
                <div class="categories-elem">
                    <input id="univer1" type="radio" v-model="stepData.publication.commerc_university" value="1">
                    <label class="small-box" for="univer1">Так</label>
                    <input id="univer0" type="radio" v-model="stepData.publication.commerc_university" value="0">
                    <label class="small-box" for="univer0">Ні</label>
                </div>
            </div>
            <div class="step-item">
                <p class="item-title">Комерціалізовано штатними співробітниками університету</p>
                <div class="categories-elem">
                    <input id="colleagues1" type="radio" v-model="stepData.publication.commerc_employees" value="1">
                    <label class="small-box" for="colleagues1">Так</label>
                    <input id="colleagues0" type="radio" v-model="stepData.publication.commerc_employees" value="0">
                    <label class="small-box" for="colleagues0">Ні</label>
                </div>
            </div>


        </div>
        <div class="step-button-group">
            <button class="next active" @click="nextStep">Продовжити <span>&gt;</span></button>
            <button class="prev" @click="prevStep">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker';
    import 'vue2-datepicker/index.css';

    const nowDate = new Date();

    export default {
        components: {
            DatePicker
        },
        data() {
            return {
                stepData: {
                    publication: {
                        number: '',
                        country: '',
                        mpk: '',
                        applicant: '',
                        application_number: '',
                        date_application: '',
                        date_publication: '',
                        newsletter_number: '',
                        commerc_university: 0,
                        commerc_employees: 0,
                    }
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
        methods:{
            nextStep() {
                this.$parent.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            },
            rangeDate(date) {
                return date <= this.datepicker.minDate || date >= this.datepicker.maxDate;
            },
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>

    .categories-elem{
        display: flex;


        .small-box{
            padding: 10px 40px;
            margin: 0 10px;
            border: 1px solid #18A0FB;
            box-sizing: border-box;
            border-radius: 44.5px;
            font-family: Montserrat;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: #18A0FB;
            cursor: pointer;
            &:first-of-type{
                margin-left: 0;
            }

        }
        input{
            display: none;
        }
        input:checked +label{
            background: #18A0FB;
            border: 1px solid #18A0FB;
            color: #fff;
        }
    }
    .input-container img {
        margin: 0 20px;
    }
    .mx-datepicker {
        width: 30%;
    }
    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
        text-align-last: center;
    }

</style>
