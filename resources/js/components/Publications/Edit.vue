<template>
    <div class="container page-content">
        <h1 class="page-title">Редагувати публікацію</h1>
        <transition name="component-fade" mode="out-in">
            <keep-alive>
                <step1 v-if="currentStep==1 && publicationData" :publicationData="publicationData" @getData="getStepData($event)"></step1>
                <step2 v-if="currentStep==2" :publicationData="publicationData" @getData="getStepData($event)" @prevStep="prevStep"></step2>
                <step3 v-if="currentStep==3" :publicationData="publicationData" @getData="getStepData($event)" @prevStep="prevStep" ></step3>
                <step4 v-if="currentStep==4" :publicationData="publicationData" @getData="getStepData($event)" @prevStep="prevStep" :publicationType="stepData.publication_type.type"></step4>
            </keep-alive>
        </transition>
    </div>
</template>

<script>
    import Step1 from "./Step1";
    import Step2 from "./Step2";
    import Step3 from "./Step3";
    import Step4 from "./Step4";
    export default {
        data() {
            return {
                isScopus: false,
                currentStep: 1,
                publicationData: '',
                stepData: {
                    publication_type:{
                        type: ''
                    }
                },
            };
        },
        created () {
            this.getPublicationData();
        },

        components: {
            Step1,
            Step2,
            Step3,
            Step4,
        },

        computed: {

        },

        methods: {
            getPublicationData(){

                axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
                    this.publicationData = response.data;
                }).catch(error => {
                    console.log(error);
                });
            },
            getStepData(event){
                this.publicationData = Object.assign(this.publicationData, event);
                this.stepData = Object.assign(this.stepData, event);

                if(this.currentStep !== 4) {
                    // этот код скрывает 3 шаг

                    if(!this.isScopus && this.currentStep == 2){
                        this.currentStep+=2;
                        const falseScinceType = {
                            snip: null,
                            impact_factor: null,
                            quartil_scopus: null,
                            quartil_wos: null,
                            sub_db_index: null
                        };
                        this.stepData = Object.assign(this.stepData, falseScinceType);
                    }
                    else {
                        this.currentStep++;
                    }
                    //

                    // this.currentStep++;
                } else {
                    this.stepData.out_data = this.outDataParser(this.stepData);
                    axios.post(`/api/update-publication/${this.$route.params.id}`, this.stepData)
                        .then((response) => {

                            swal("Публікацію успішно змінено!", {
                                icon: "success",
                            });
                            this.$router.push({path: '/publications'});
                        })
                        .catch(() => {
                            swal({
                                icon: "error",
                                title: 'Помилка'
                            });
                        });
                }
            },
            prevStep() {
                if(this.currentStep !== 1){
                    // этот код скрывает 3 шаг

                    if(!this.isScopus && this.currentStep == 4){
                        this.currentStep-=2;
                    }
                    else {
                        this.currentStep--;
                    }
                    // this.currentStep--;
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

<style lang="scss" >


</style>
