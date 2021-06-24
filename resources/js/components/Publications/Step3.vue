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
                            <input type="text" v-model="publicationData.snip">
                        </div>
                        <div class="error" v-if="$v.publicationData.snip.$error">
                            Вводити лише числа не менше 0 (дроби через крапку)
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
                    <div class="form-group col-lg-6">
                        <label >Імпакт-фактор (БД WoS)</label>
                        <div class="input-container">
                            <input type="text" v-model="publicationData.impact_factor">
                        </div>
                        <div class="error" v-if="$v.publicationData.impact_factor.$error">
                            Вводити лише числа не менше 0 (дроби через крапку)
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label >Квартиль журналу (БД WoS)</label>
                        <div class="input-container">
                            <select  v-model="publicationData.quartil_wos" >
                                <option value=""></option>
                                <option v-for="n in 4" :key="n" :value="n">{{n }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row" v-if="(publicationData.science_type_id == 2 || publicationData.science_type_id == 3) && (publicationType.id == 1 || publicationType.id == 3)">
                    <div class="form-group col-lg-6">
                        <label >Підбаза SCIE (Science Citation Index Expanded)</label>
                        <div class="input-container">
                            <select v-model="publicationData.sub_db_scie">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label >Підбаза SSCI (Social Science Citation Index)</label>
                        <div class="input-container">
                            <select v-model="publicationData.sub_db_ssci">
                                <option value="1">Так</option>
                                <option value="0">Ні</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-lg-6">
                        <label>Обліковується рейтингом Nature Index</label>
                        <div class="input-container">
                            <select :disabled="userRole != 4" v-model="publicationData.nature_index">
                                <option value="1">Так</option>
                                <option value="2">Ні</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-6">
                        <label>У журналах (Nature / Science)</label>
                        <div class="input-container">
                            <select v-model="publicationData.nature_science" @change="setNatureScience">
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
            <button class="next active" @click="nextStep">Продовжити</button>
            <close-edit-button v-if="$route.name == 'publications-edit'"></close-edit-button>
        </div>
    </div>
</template>

<script>
    import CloseEditButton from "../Buttons/CloseEdit";
    import {required, requiredIf} from "vuelidate/lib/validators";
    export default {
        name: "Step4",
        data(){
            return{
                clicks: 0,
            }
        },
        props: {
            publicationType: Object,
            publicationData: Object
        },
        components: {
            CloseEditButton
        },
        watch: {
            publicationData: {
                // the callback will be called immediately after the start of the observation
                immediate: true,
                handler (val, oldVal) {
                    //console.log(val)
                }
            }
        },
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },
        validations: {
            publicationData: {
                snip: {
                    test(value) {
                        if(value) {
                            return value > 0 ? true : false;
                        } else {
                            return true;
                        }
                    },
                },

                impact_factor: {
                    test(value) {
                        if(value) {
                            return value > 0 ? true : false;
                        } else {
                            return true;
                        }
                    },
                }
            }
        },
        methods:{
            setNatureScience() {
                this.publicationData.name_magazine = this.publicationData.nature_science;
                this.publicationData.default_name_magazine = this.publicationData.nature_science ? true : false;
            },
            nextStep() {
               this.$v.publicationData.$touch();
                if (this.$v.publicationData.$invalid) {
                    return
                }
                this.$emit('getData', 3);
            },
            prevStep(){
                this.$emit('prevStep');
            },
        },
    }
</script>
