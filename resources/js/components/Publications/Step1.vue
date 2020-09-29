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
                        <select  v-model="stepData.science_type_id" @change="changeScienceType">
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

                            <option v-for="(item, i) in publicationTypes" :key="i" v-if="item.scopus_wos" :value="item">{{item.title }}</option>


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
                prevVal: '',
                stepData: {
                    title: '',
                    science_type_id: '',
                    publication_type: null
                }
            }
        },
        created() {
            this.getTypePublications();
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
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
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
                if(this.stepData.science_type_id){
                    this.$parent.isScopus = true;

                }
                else{
                    this.$parent.isScopus = false;
                }
                //
                this.$emit('getData', this.stepData);
            },
            checkString(s){

                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }

        }
    }
</script>

<style lang="scss" scoped>


</style>
