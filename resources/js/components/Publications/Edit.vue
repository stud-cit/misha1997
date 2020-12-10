<template>
    <div class="container page-content general-block">
        <div class="message-block" v-if="userRole != 1">
            <img src="/img/exclamation-mark.png" @click="sendMessage" title="Написати повідомлення авторам публікації">
        </div>
        <h1 class="page-title">Редагувати публікацію</h1>
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
                    whose_publication: "",
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
                    sub_db_scie: 0,
                    sub_db_ssci: 0,
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
                    not_previous_year: false,
                    name_monograph: "",
                    authors: [],
                    useSupervisor: false,
                    supervisor: null,
                    old_supervisor: null
                },
            };
        },
        created() {
            this.getPublicationData();
        },

        components: {
            Step1,
            Step2,
            Step3,
            Step4,
        },

        computed: {
            userRole() {
                return this.$store.getters.authUser ? this.$store.getters.authUser.roles_id : null
            }
        },

        methods: {
            sendMessage() {
                swal.fire({
                    title: 'Введіть текст повідомлення',
                    input: 'textarea',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Надіслати',
                    cancelButtonText: 'Відміна',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Поле не має бути пустим'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/api/notifications/${this.$route.params.id}`, {
                            comment: result.value
                        })
                        .then((response) => {
                            swal.fire({
                                icon: 'success',
                                title: "Повідомлення надіслано"
                            })
                        }).catch(() => {
                            swal.fire({
                                icon: 'error',
                                title: 'Помилка'
                            })
                        });
                    }
                })
            },
            getPublicationData() {
                axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
                    this.publicationData = Object.assign(this.publicationData, response.data);
                    this.publicationData.authors = [];
                    response.data.authors.map(item => {
                        if(item.supervisor == 0) {
                            this.publicationData.authors.push(item.author);
                        }
                        if(item.supervisor == 1) {
                            this.publicationData.useSupervisor = true;
                            this.publicationData.supervisor = item.author;
                            this.publicationData.old_supervisor = item.author;
                        }
                    });
                });
            },
            getStepData(num = 4) {
                if(num !== 4) {
                    // этот код скрывает 3 шаг
                    if(!this.isScopus && num == 2){
                        this.currentStep = num + 2;
                        const falseScinceType = {
                            snip: null,
                            impact_factor: null,
                            quartil_scopus: null,
                            quartil_wos: null,
                            sub_db_index: null
                        };
                        this.publicationData = Object.assign(this.publicationData, falseScinceType);
                    }
                    else {
                        this.currentStep = num + 1;
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
                    axios.post(`/api/update-publication/${this.$route.params.id}`, this.publicationData)
                        .then((response) => {
                            swal.fire({
                                icon: 'success',
                                title: 'Публікацію успішно змінено!',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            setTimeout(() => {
                                this.$router.push({path: '/publications'});
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
                    }
                    else {
                        this.currentStep--;
                    }
                    // this.currentStep--;
                }

            },
            outDataParser(item) {
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
    .message-block img {
        float: right;
        cursor: pointer;
    }
</style>
