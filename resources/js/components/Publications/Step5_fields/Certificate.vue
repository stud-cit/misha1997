<template>
    <div>
        <div class="step-content">


            <div class="step-item">
                <label class="item-title">№ свідоцтва/рішення</label>
                <input class="item-value" type="text" v-model="stepData.certificate.number">

            </div>
            <div class="step-item">
                <label class="item-title">Країна, де отриманий патент</label>
                <select class="item-value" v-model="stepData.certificate.country">
                    <option value="Україна">Україна</option>
                    <option value="Англія">Англія</option>
                </select>

            </div>

            <div class="step-item">
                <label class="item-title">Заявник</label>
                <input class="item-value" type="text" v-model="stepData.certificate.applicant">

            </div>




            <div class="step-item">
                <label class="item-title">Дата подачі заявки</label>
                <input class="item-value" type="text" v-model="stepData.certificate.dateSend">

            </div>
            <div class="step-item">
                <label class="item-title">Дата публікації про видачу свідоцтва/рішення</label>
                <input class="item-value" type="text" v-model="stepData.certificate.datePublication">

            </div>

            <div class="step-item">
                <p class="item-title">Комерціалізовано університетом</p>
                <div class="categories-elem">
                    <input id="univer1" type="radio" v-model="stepData.certificate.univer" value="1">
                    <label class="small-box" for="univer1">Так</label>
                    <input id="univer0" type="radio" v-model="stepData.certificate.univer" value="0">
                    <label class="small-box" for="univer0">Ні</label>
                </div>
            </div>
            <div class="step-item">
                <p class="item-title">Комерціалізовано штатними співробітниками університету</p>
                <div class="categories-elem">
                    <input id="colleagues1" type="radio" v-model="stepData.certificate.colleagues" value="1">
                    <label class="small-box" for="colleagues1">Так</label>
                    <input id="colleagues0" type="radio" v-model="stepData.certificate.colleagues" value="0">
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
    export default {

        data() {
            return {
                stepData:{
                    certificate:{
                        number: '',
                        country: '',
                        applicant: '',
                        dateSend: '',
                        datePublication: '',
                        univer: 0,
                        colleagues: 0,


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
    select{
        background: url("/img/arrow-down.png") no-repeat 98% 50%;
        text-align-last: center;
    }

</style>
