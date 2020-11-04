<template>
    <div>
        <p class="step-subtitle">
            Крок 1 з 4.
        </p>
        <div  class="step-content">
            <div class="form-group" v-show="userRole != 1 && $route.name != 'publications-edit'">
                <label>Додати власну публікацію або кафедри *</label>
                <div class="input-container">
                    <select  v-model="publicationData.whose_publication" @change="whosePublication">
                        <option value="my">Власна публікація</option>
                        <option value="another">Публікація <span v-if="userRole == 2">кафедри</span><span v-if="userRole == 3">факультету</span><span v-if="userRole == 4">університету</span></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label >Назва публікації *</label>
                <div class="input-container">
                    <input v-model="publicationData.title" type="text" list="names" @input="findNames">
                    <datalist id="names">
                        <option v-for="(item, index) in names" :key="index" :value="item.title">{{item.publication_type.title}}</option>
                    </datalist>
                </div>
                <div class="error" v-if="$v.publicationData.title.$error">
                    Поле обов'язкове для заповнення
                </div>
                <div class="error" v-if="errorName">
                    Публікація вже додана на сайт: {{ errorName.title }} - {{ errorName.publication_type.title }}
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <label >БД Scopus/WoS</label>
                    <div class="input-container">
                        <select  v-model="publicationData.science_type_id" @change="changeScienceType">
                            <option value=""></option>
                            <option value="1">Scopus</option>
                            <option value="2">WoS</option>
                            <option value="3">Scopus та WoS</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-8">
                    <label >Вид публікації *</label>
                    <div class="input-container">
                        <select  v-model="publicationData.publication_type" v-if="publicationData.science_type_id">
                            <option v-for="(item, i) in publicationTypes" :key="i" v-show="item.scopus_wos" :value="item">{{item.title }}</option>
                        </select>
                        <select  v-model="publicationData.publication_type" v-else>
                            <option v-for="(item, i) in publicationTypes" :key="i" :value="item">{{item.title  }}</option>
                        </select>
                        <div class="hint" ><span>Прізвище, ім’я, по-батькові</span></div>
                    </div>
                    <div class="error" v-if="$v.publicationData.publication_type.$error">
                        Поле обов'язкове для заповнення
                    </div>
                </div>
            </div>
        </div>
        <div class="step-button-group">
            <button @click="$router.go(-1)" class="prev">Назад</button>
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
                errorName: null
            }
        },
        props: {
            publicationData: Object
        },
        mounted() {
            this.getTypePublications();
            this.getNamesPublications();
            this.checkPublicationData();
        },
        validations: {
            publicationData: {
                title: {
                    required,
                },
                publication_type: {
                    required,
                    isRequired(value) {
                        return value.title != '' ? true : false
                    }
                },
            },
        },
        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },
        methods: {
            whosePublication() {
                if(this.$route.name == 'publications-add') {
                    if(this.publicationData.whose_publication == 'my') {
                        this.publicationData.authors.push(this.$store.getters.authUser);
                    } else {
                        this.publicationData.authors.splice(this.publicationData.authors.indexOf(this.$store.getters.authUser), 1);
                    }
                }
            },
            // пошук схожих назв публікацій
            findNames() {
                this.names = this.publicationNames.filter(item => {
                    return item.title.indexOf(this.publicationData.title) + 1
                })
            },
            checkPublicationData() {
                if(this.publicationData && this.$route.name == 'publications-edit'){
                    const {title, science_type_id, publication_type} = this.publicationData;
                    this.publicationData.title = title;
                    this.publicationData.science_type_id = science_type_id;
                    this.publicationData.publication_type = publication_type;
                }
            },
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            getNamesPublications() {
                axios.get(`/api/publications-names`).then(response => {
                    this.publicationNames = response.data;
                })
            },
            changeScienceType(){
              this.publicationData.publication_type = "";
            },
            // перехід на наступний крок
            nextStep() {
                this.$v.$touch()
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                // check scopus
                if(this.publicationData.science_type_id) {
                    this.$parent.isScopus = true;
                } else {
                    this.$parent.isScopus = false;
                }
                let editTitle = false;
                if(this.$route.name == 'publications-edit') {
                    editTitle = this.parseString(this.publicationData.title) == this.parseString(this.publicationData.title);
                }

                // перевірка унікальності назви і типу публікації
                if(this.$route.name != 'publications-edit') {
                    var findPublication = this.publicationNames.find(item => this.parseString(item.title) == this.parseString(this.publicationData.title) && item.publication_type_id == this.publicationData.publication_type.id);
                    if(findPublication) {
                        this.errorName = findPublication;
                        return;
                    } else {
                        this.errorName = null;
                    }
                }
                this.$emit('getData');
            },
            parseString(s) {
                const punctuation = s.replace(/[.,\/\[\]#!$%\^&\*;:{}=\-_`~()]/g,"");
                return punctuation.replace(/\s+/g,' ' ).trim().toLowerCase();
            }
        }
    }
</script>
