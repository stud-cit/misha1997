<template>
    <div>

        <p class="step-subtitle">
            Крок 1 з 4.
        </p>
        <div  class="step-content">


            <div class="form-group">
                <label >Назва публікації *</label>
                <div class="input-container hint-container">
                    <input type="text"  v-model.trim="stepData.title" @blur="$v.stepData.title.$touch()">

                    <div class="hint" ><span>Зазначається назва публікації мовою оригіналу, починаючи з великої літери</span></div>
                </div>

                <div class="error" v-if="$v.stepData.title.$error">
                    Поле обов'язкове для заповнення
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <label >БД Scopus\WoS</label>
                    <div class="input-container">
                        <select  v-model="stepData.science_type_id" >
                            <option value=""></option>
                            <option value="1">Scopus</option>
                            <option value="2">Wos</option>
                            <option value="3">Scopus та Wos</option>
                        </select>
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>
                <div class="form-group col-lg-8">
                    <label >Вид публікації *</label>
                    <div class="input-container">
                        <select  v-model.trim="stepData.publication_type" v-if="stepData.science_type_id">

                            <option v-for="(item, i) in publicationTypes" :key="i" v-show="item.scopus_wos" :value="item">{{item.title }}</option>


                        </select>
                        <select  v-model.trim="stepData.publication_type" v-else>

                            <option v-for="(item, i) in publicationTypes" :key="i" :value="item">{{item.title  }}</option>


                        </select>
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові</span></div>
                    </div>

                    <div class="error" v-if="$v.stepData.publication_type.$error">
                        Поле обов'язкове для заповнення
                    </div>

                </div>

            </div>



        </div>

        <div class="step-button-group">
            <button class="next" @click="nextStep" >Продовжити </button>
        </div>
    </div>
</template>

<script>
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        name: "Step1",
        data() {
            return {

                publicationTypes: [],
                publicationNames: [],
                prevVal: '',
                stepData: {
                    title: '',
                    science_type_id: 0,
                    publication_type: null
                }
            }
        },
        props : {
            publicationData: Object
        },
        mounted() {
            this.getTypePublications();
            this.getNamesPublications();
            this.checkPublicationsData();
        },
        validations: {


            stepData: {
                title: {
                    required,
                },
                publication_type: {
                    required,
                },
            },

        },
        methods: {
            checkPublicationsData() {



                    if(this.publicationData && this.$route.name == 'publications-edit'){

                        this.stepData.title = this.publicationData.title;

                        this.stepData.science_type_id = this.publicationData.science_type_id;

                        this.stepData.publication_type = this.publicationData.publication_type;

                        // clearInterval(timer);
                    }


            },
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data.map(n => this.parseString(n.title));

                })
            },
            changeScienceType(){
              this.stepData.publication_type = "";

            },
            nextStep() {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }



                // check scopus
                if(this.stepData.science_type_id) {
                    this.$parent.isScopus = true;
                } else {
                    this.$parent.isScopus = false;
                }
                //

                let editTitle = false;

                if(this.$route.name == 'publications-edit'){
                    editTitle = this.parseString(this.stepData.title) == this.parseString(this.publicationData.title);
                }

                if(!this.publicationNames.includes(this.parseString(this.stepData.title)) | editTitle){
                    this.$emit('getData', this.stepData);
                }
                else{
                    swal("Публікація з такою назвою вже існує!", {
                                        icon: "error",
                    });
                }

            },
            parseString(s){

                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }

        }
    }
</script>

<style lang="scss" scoped>


</style>
