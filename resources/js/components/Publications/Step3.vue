<template>
    <div>
        <p class="step-subtitle">
            Крок 3 з 4.
        </p>
        <div class=" step-content">
            <form class="form-block">
                <div class="form-row" v-if="publicationData.science_type_id == 1 || publicationData.science_type_id == 3">
                    <div class="form-group col-lg-6">
                        <label >SNIP журналу (БД Scopus)</label>
                        <div class="input-container">
                            <input type="number" v-model="publicationData.snip">
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>Квартиль журналу (БД Scopus)</label>
                        <div class="input-container">
                            <select  v-model="publicationData.quartil_scopus" >
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
                            <input type="number" v-model="publicationData.impact_factor">
                        </div>
                    </div>
                    <div class="form-group col-lg-4">
                        <label >Квартиль журналу (БД WoS)</label>
                        <div class="input-container">
                            <select  v-model="publicationData.quartil_wos" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group" v-if="(publicationData.science_type_id == 2 || publicationData.science_type_id == 3) && (publicationType.id == 1 || publicationType.id == 3)">
                    <label >Підбаза WoS</label>
                    <div class="input-container">
                        <select v-model="publicationData.sub_db_index">
                            <option value=""></option>
                            <option value="1">Science Citation Index Expanded (SCIE)</option>
                            <option value="2">Social Science Citation Index (SSCI)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label>Обліковується рентингом Nature Index</label>
                        <div class="input-container">
                            <select v-model="publicationData.nature_index">
                                <option value=""></option>
                                <option value="1">Так</option>
                                <option value="2">Ні</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>У журналах</label>
                        <div class="input-container">
                            <select v-model="publicationData.nature_science">
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
                            <select v-model="publicationData.db_scopus_percent">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group col" v-if="publicationData.science_type_id == 2 || publicationData.science_type_id == 3">
                        <label>Увійшли до 1% за БД WoS найбільш цитованих публікацій для своєї предметної галузі</label>
                        <div class="input-container">
                            <select v-model="publicationData.db_wos_percent">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row" v-if="$route.name == 'publications-edit' && userRole == 4">
                     <div class="form-group col">
                        <label>Процитована у міжнародних патентах</label>
                        <div class="input-container">
                            <select v-model="publicationData.cited_international_patents">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
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
            publicationType: Object,
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
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },
        methods:{
            nextStep() {
                this.$emit('getData');
            },
            prevStep(){
                this.$emit('prevStep');
            },
        },
    }
</script>