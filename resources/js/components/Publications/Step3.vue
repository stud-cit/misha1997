<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 4.
        </p>
        <div class=" step-content">
            <form class="form-block">
                <div class="form-row" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-8">
                        <label >SNIP журналу (БД Scopus)</label>
                        <div class="input-container">
                            <input type="text" v-model="stepData.snip">
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Квартиль журналу (БД Scopus)</label>
                        <div class="input-container">
                            <select  v-model="stepData.quartil_scopus" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                </div>
                <div class="form-row" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-8">
                        <label >Імпакт-фактор (БД WoS)</label>
                        <div class="input-container">
                            <input type="text" v-model="stepData.impact_factor">
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Квартиль журналу (БД WoS)</label>
                        <div class="input-container">

                            <select  v-model="stepData.quartil_wos" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                </div>

                <div class="form-row" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-8">
                        <label >Підбаза WoS</label>
                        <div class="input-container">
                            <select  v-model="stepData.sub_db_index" >
                                <option value=""></option>
                                <option value="1">Science Citation Index Expanded (SCIE)</option>
                                <option value="2">Social Science Citation Index</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="step-button-group">
            <button class="prev" @click="prevStep">На попередній крок</button>
            <button class="next active" @click="nextStep">Продовжити </button>

        </div>
    </div>
</template>

<script>
    export default {
        name: "Step4",
        props: {
          scopus: Boolean
        },
        data() {
            return {
                stepData:{
                    snip: '',
                    impact_factor: '',
                    quartil_scopus: null,
                    quartil_wos: null,
                    sub_db_index: ''
                }
            }
        },
        props: {
            publicationData: Object
        },
        created() {
            this.checkPublicationData();
        },
        methods:{
            checkPublicationData() {
                if(this.publicationData && this.$route.name == 'publications-edit'){
                    const {snip, impact_factor, quartil_scopus, quartil_wos, sub_db_index} = this.publicationData;
                    this.stepData.snip = snip;
                    this.stepData.impact_factor = impact_factor;
                    this.stepData.quartil_scopus = quartil_scopus;
                    this.stepData.quartil_wos = quartil_wos;
                    this.stepData.sub_db_index = sub_db_index;
                }
            },
            nextStep() {
                this.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$emit('prevStep', this.stepData);
            },
        },
    }
</script>

<style lang="scss" scoped>


    /*.step-item{*/
    /*    margin-top: 50px;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*    &:first-of-type{*/
    /*        margin-top: 0;*/
    /*    }*/
    /*    .item-title{*/
    /*        width: auto;*/
    /*        font-family: Montserrat;*/
    /*        font-style: normal;*/
    /*        font-weight: normal;*/
    /*        font-size: 25px;*/
    /*        color: #A6A6A6;*/
    /*        margin-right: 20px;*/
    /*        margin-bottom: 0;*/
    /*    }*/
    /*    .item-input{*/
    /*        padding: 12px;*/
    /*        text-align: center;*/
    /*        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.25);*/
    /*        border-radius: 44.5px;*/
    /*        border: none;*/
    /*        outline: none;*/
    /*        font-family: Montserrat;*/
    /*        font-style: normal;*/
    /*        font-weight: normal;*/
    /*        font-size: 20px;*/
    /*        color: #0E0E0E;*/
    /*        min-width: 258px;*/

    /*    }*/
    /*    .categories-elem{*/
    /*        display: flex;*/

    /*        .small-box{*/
    /*            width: 31px;*/
    /*            padding: 4px 0;*/
    /*            margin: 0 10px;*/
    /*            border: 1px solid #18A0FB;*/
    /*            box-sizing: border-box;*/
    /*            border-radius: 10px;*/
    /*            font-family: Montserrat;*/
    /*            font-style: normal;*/
    /*            font-weight: normal;*/
    /*            font-size: 20px;*/
    /*            color: #18A0FB;*/
    /*            text-align: center;*/
    /*            cursor: pointer;*/
    /*        }*/
    /*        .big-box{*/
    /*            padding: 10px 40px;*/
    /*            margin: 0 10px;*/
    /*            border: 1px solid #18A0FB;*/
    /*            box-sizing: border-box;*/
    /*            border-radius: 44.5px;*/
    /*            font-family: Montserrat;*/
    /*            font-style: normal;*/
    /*            font-weight: normal;*/
    /*            font-size: 20px;*/
    /*            color: #18A0FB;*/
    /*            cursor: pointer;*/

    /*        }*/
    /*        input{*/
    /*            display: none;*/
    /*        }*/
    /*        input:checked +label{*/
    /*            background: #18A0FB;*/
    /*            border: 1px solid #18A0FB;*/
    /*            color: #fff;*/
    /*        }*/
    /*    }*/
    /*}*/


</style>
