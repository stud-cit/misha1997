<template>
    <div>

        <p class="step-subtitle">
            Крок 1 з 4.
        </p>
        <div  class="step-content">
            <div class="form-group" v-show="userRole != 1 && $route.name != 'publications-edit'">
                <label>Додати власну публікацію або співробітника кафедри *</label>
                <div class="input-container">
                    <select  v-model="stepData.whose_publication">
                        <option value="my">Власна публікація</option>
                        <option value="another">Публікація співробітника кафедри</option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>
            <div class="form-group">
                <label >Назва публікації *</label>
                <div class="input-container">
                    <input v-model="stepData.title" type="text" list="names" @input="findNames">
                    <datalist id="names">
                        <option v-for="(item, index) in names" :key="index" :value="item">{{item}}</option>
                    </datalist>
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
                            <option value="2">WoS</option>
                            <option value="3">Scopus та WoS</option>
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
            <router-link :to="'/home'" tag="button" class="prev">Назад</router-link>
            <button class="next" @click="nextStep" >Продовжити </button>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import { required, maxLength } from 'vuelidate/lib/validators';
    export default {
        name: "Step1",
        components: {
            Multiselect,
        },
        data() {
            return {
                names: [],
                publicationTypes: [],
                publicationNames: [],
                errorName: false,
                prevVal: '',
                stepData: {
                    whose_publication: 'my',
                    title: '',
                    science_type_id: '',
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
            this.checkPublicationData();
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
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },
        methods: {
            findNames() {
                this.names = this.publicationNames.filter(item => {
                    return item.indexOf(this.stepData.title) + 1
                })
            },
            checkPublicationData() {
                    if(this.publicationData && this.$route.name == 'publications-edit'){
                        const {title, science_type_id, publication_type} = this.publicationData;
                        this.stepData.title = title;
                        this.stepData.science_type_id = science_type_id;
                        this.stepData.publication_type = publication_type;
                    }
            },
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            getNamePublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data;
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
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }
        }
    }
</script>
