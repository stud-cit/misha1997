<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Додати нову публікацію</h1>
        <transition name="component-fade" mode="out-in">
            <keep-alive>
                <step1 v-if="currentStep==1" :publicationData="publicationData" @getData="getStepData"></step1>
                <step2 v-if="currentStep==2" :publicationData="publicationData" @getData="getStepData" @prevStep="prevStep"></step2>
                <step3 v-if="currentStep==3" :publicationData="publicationData" @getData="getStepData" @prevStep="prevStep" :publicationType="publicationData.publication_type"></step3>
                <step4 v-if="currentStep==4" :publicationData="publicationData" @getData="getStepData" @prevStep="prevStep" :publicationType="publicationData.publication_type.type"></step4>
            </keep-alive>
        </transition>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';

    import Step1 from "./Step1";
    import Step2 from "./Step2";
    import Step3 from "./Step3";
    import Step4 from "./Step4";
    export default {
        data() {
            return {
                isScopus: false,
                currentStep: 1,
                publicationData: {
                    whose_publication: "my",
                    title: "",
                    science_type_id: null,
                    science_type: {
                        type: "",
                    },
                    publication_type_id: null,
                    publication_type: {
                        scopus_wos: "",
                        title: "",
                        type: "",
                    },
                    snip: "",
                    impact_factor: "",
                    quartil_scopus: "",
                    quartil_wos: "",
                    sub_db_index: "",
                    year: new Date().getFullYear(),
                    number: "",
                    pages: "",
                    initials: "",
                    country: "",
                    number_volumes: "",
                    by_editing: "",
                    city: "",
                    editor_name: "",
                    languages: "",
                    number_certificate: "",
                    applicant: "",
                    date_application: "",
                    date_publication: "",
                    commerc_university: "",
                    commerc_employees: "",
                    access_mode: "",
                    mpk: "",
                    application_number: "",
                    newsletter_number: "",
                    name_conference: "",
                    url: "",
                    out_data: "",
                    name_magazine: "",
                    doi: "",
                    nature_index: 2,
                    nature_science: "",
                    db_scopus_percent: "",
                    db_wos_percent: "",
                    cited_international_patents: "",
                    name_monograph: "",
                    authors: [],
                    useSupervisor: 0,
                    supervisor: null
                },
            };
        },

        components: {
            Step1,
            Step2,
            Step3,
            Step4,
        },
        
        methods: {
            getStepData() {
                if(this.currentStep !== 4) {
                    // этот код скрывает 3 шаг
                    if(!this.isScopus && this.currentStep == 2) {
                        this.currentStep+=2;
                        const falseScinceType = {
                            snip: null,
                            impact_factor: null,
                            quartil_scopus: null,
                            quartil_wos: null,
                            sub_db_index: null
                        };
                        this.publicationData = Object.assign(this.publicationData, falseScinceType);
                    } else {
                        this.currentStep++;
                    }
                } else {
                    this.publicationData.out_data = this.outDataParser(this.publicationData);
                    if(this.publicationData.science_type_id == 1) {
                        this.publicationData.quartil_wos = null;
                        this.publicationData.impact_factor = null;
                        this.publicationData.sub_db_index = null;
                    }
                    if(this.publicationData.science_type_id == 2) {
                        this.publicationData.snip = null;
                        this.publicationData.quartil_scopus = null;
                    }
                    axios.post('/api/publication', this.publicationData)
                    .then(() => {
                        swal.fire({
                            icon: 'success',
                            title: 'Публікацію успішно додано!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    })
                    .catch(() => {
                        swal.fire({
                            icon: 'error',
                            title: 'Помилка',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    });
                }
            },
            prevStep() {
                if(this.currentStep !== 1){
                    // этот код скрывает 3 шаг
                    if(!this.isScopus && this.currentStep == 4){
                        this.currentStep-=2;
                    } else {
                        this.currentStep--;
                    }
                }
            },
            outDataParser (item) {
                let result = "";
                switch(item.publication_type.type){
                    case "article":
                        result = item.name_magazine + '. ' + item.year +  '. С. ' + item.pages + '.' + (item.doi ? ' DOI: ' + item.doi : '');
                        // result = item.initials + ' ' + item.title + '. ' + item.name_magazine + '. ' + item.year + '. №' + item.number + '. С. ' + item.pages + '.' + (item.doi ? ' DOI: ' + item.doi : '');
                        break;
                    case "book":
                        result = item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.year + '. ' + item.pages + ' с.' + (item.doi ? ' DOI: ' + item.doi : '');
                        // result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с.' + (item.doi ? ' DOI: ' + item.doi : '');
                        break;
                    case "book-part":
                        result = item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.year + '. С. ' + item.pages + '.';
                        // result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages + '.';
                        break;
                    case "thesis":
                        result = 'Матеріали ' + item.name_conference + '. ' + ', ' + item.year + '. С. ' + item.pages + '. ' + (item.doi ? ' DOI: ' + item.doi : '');
                        // result = item.initials + ' ' + item.title + ': матеріали ' + item.name_conference + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages + '. ' + (item.doi ? ' DOI: ' + item.doi : '');
                        break;
                    case "patent":
                        result =  'Пат. ' + item.number_certificate + ' ' + item.country + ': МПК ' + item.mpk + '. № ' + item.application_number +  '; опубл. ' + item.date_publication + ', Бюл. № ' + item.newsletter_number + '.';
                        break;
                    case "certificate":
                        result =  'Свідоцтво про реєстрацію авторського права на твір № ' +  item.number_certificate + '. ' + item.title + ' / ' + item.initials + ' ' + item.country + ';  опубл. ' + item.date_publication + '.';
                        // result =  'Свідоцтво про реєстрацію авторського права на твір № ' +  item.number_certificate + '. ' + item.title + ' / ' + item.initials + ' ' + item.country + '; заявл. ' + item.date_application + '; опубл. ' + item.date_publication + '.';
                        break;
                    case "methodical":
                        result =  item.year + '. ' + item.pages + ' с.';
                        // result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с.';
                        break;
                    case "electronic":
                        result =  item.year + '. ' + item.pages + ' с. URL: ' + item.url;
                        // result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + ' с. URL: ' + item.url;
                        break;
                }
                return result;
            },
        }
    }
</script>