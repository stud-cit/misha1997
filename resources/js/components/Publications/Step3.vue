<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 4.
        </p>
        {{publicationData}}
        <div class=" step-content">
            <form class="form-block">
                <div class="form-row" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-6">
                        <label >SNIP журналу (БД Scopus)</label>
                        <div class="input-container">
                            <input type="number" v-model="stepData.snip">
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Квартиль журналу (БД Scopus)</label>
                        <div class="input-container">
                            <select  v-model="stepData.quartil_scopus" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-8">
                        <label >Імпакт-фактор (БД WoS)</label>
                        <div class="input-container">
                            <input type="text" v-model="stepData.impact_factor">
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Квартиль журналу (БД WoS)</label>
                        <div class="input-container">
                            <select  v-model="stepData.quartil_wos" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                    <label >Підбаза WoS</label>
                    <div class="input-container">
                        <select v-model="stepData.sub_db_index">
                            <option value=""></option>
                            <option value="1">Science Citation Index Expanded (SCIE)</option>
                            <option value="2">Social Science Citation Index</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label>Обліковується рентингом Nature Index</label>
                        <div class="input-container">
                            <select v-model="stepData.nature_index">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="2">Ні</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Зазначені в журналах</label>
                        <div class="input-container">
                            <select v-model="stepData.nature_science">
                                <option value=""></option>
                                <option value="Nature">Nature</option>
                                <option value="Science">Science</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row" v-if="$route.name == 'publications-edit' && userRole == 4">
                    <div class="form-group col" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
                        <label>Увійшли до 10% за БД Scopus найбільш цитованих публікацій для своєї предметної галузі</label>
                        <div class="input-container">
                            <select v-model="stepData.db_scopus_percent">
                                <option value="1">Так</option>
                                <option value="2">Ні</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group col" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                        <label>Увійшли до 1% за БД WoS найбільш цитованих публікацій для своєї предметної галузі</label>
                        <div class="input-container">
                            <select v-model="stepData.db_wos_percent">
                                <option value="1">Так</option>
                                <option value="2">Ні</option>
                            </select>
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
                stepData: {
                    snip: '',
                    impact_factor: '',
                    quartil_scopus: null,
                    quartil_wos: null,
                    sub_db_index: '',
                    db_scopus_percent: 0,
                    db_wos_percent: 0,
                    nature_index: null,
                    nature_science: null
                }
            }
        },
        props: {
            publicationData: Object
        },
        watch: {
            publicationData: {  
                // the callback will be called immediately after the start of the observation
                immediate: true, 
                handler (val, oldVal) {
                    console.log(val)
                }
            }
        },
        // watch: { 
        //     publicationData: function(newVal, oldVal) {
        //         console.log(newVal)
        //         return newVal
        //     }
            
        // },
        created() {
            this.checkPublicationData();
        },
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },
        methods:{
            checkPublicationData() {
                if(this.publicationData && this.$route.name == 'publications-edit') {
                    const {snip, impact_factor, quartil_scopus, quartil_wos, sub_db_index, db_scopus_percent, db_wos_percent, nature_index, nature_science} = this.publicationData;
                    this.stepData.snip = snip;
                    this.stepData.impact_factor = impact_factor;
                    this.stepData.quartil_scopus = quartil_scopus;
                    this.stepData.quartil_wos = quartil_wos;
                    this.stepData.sub_db_index = sub_db_index;
                    this.stepData.db_scopus_percent = db_scopus_percent;
                    this.stepData.db_wos_percent = db_wos_percent;
                    this.stepData.nature_index = nature_index;
                    this.stepData.nature_science = nature_science;
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