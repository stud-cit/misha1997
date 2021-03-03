<template>
    <div class="container general-block">
        <h1 class="blue-page-title">{{data.title}}</h1>
        <transition name="component-fade" mode="out-in">
        <div class="page-content" v-if="data.publication_type">
            <ul class="list-view">
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Назва публікації:</div>
                    <div class="col-lg-9 list-item list-text">{{data.title}}</div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Автори публікації:</div>
                    <div class="col-lg-9 list-item list-text">
                        <span v-for="(author, index) in data.authors" :key="index"><a :href="'/user/'+author.id">{{author.name}} </a></span>
                    </div>
                </li>
                <li class="row">
                    <div class="col-lg-3 list-item list-title">Вид публікації:</div>
                    <div class="col-lg-9 list-item list-text">{{data.publication_type.title}}</div>
                </li>
                <template v-if="data.supervisor">
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Під керівництвом:</div>
                        <div class="col-lg-9 list-item list-text">Так</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Керівник:</div>
                        <div class="col-lg-9 list-item list-text"><a :href="'/user/'+data.supervisor.id">{{data.supervisor.name}}</a></div>
                    </li>
                </template>
                <template v-else>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Під керівництвом:</div>
                        <div class="col-lg-9 list-item list-text">Ні</div>
                    </li>
                </template>
                <template v-if="data.science_type_id != null">
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">БД Scopus/WoS:</div>
                        <div class="col-lg-9 list-item list-text">{{data.science_type.type}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">SNIP журналу (БД Scopus):</div>
                        <div class="col-lg-9 list-item list-text">{{data.snip}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Квартиль журналу (БД Scopus):</div>
                        <div class="col-lg-9 list-item list-text">{{data.quartil_scopus}}</div>
                    </li>

                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Імпакт-фактор (БД WoS):</div>
                        <div class="col-lg-9 list-item list-text">{{data.impact_factor}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Квартиль журналу (БД WoS):</div>
                        <div class="col-lg-9 list-item list-text">{{data.quartil_wos}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Підбаза WoS:</div>
                        <div class="col-lg-9 list-item list-text">
                            <span v-if="data.sub_db_scie==1 && data.sub_db_ssci==0">Science Citation Index Expanded (SCIE)</span>
                            <span v-if="data.sub_db_ssci==1 && data.sub_db_scie==0">Social Science Citation Index (SSCI)</span>
                            <span v-if="data.sub_db_scie==1 && data.sub_db_ssci==1">Science Citation Index Expanded (SCIE), Social Science Citation Index (SSCI)</span>
                        </div>
                    </li>
                    <li class="row" v-if="data.nature_index">
                        <div class="col-lg-3 list-item list-title">Обліковується рентингом Nature Index:</div>
                        <div class="col-lg-9 list-item list-text">{{data.nature_index == 1 ? "Так" : "Ні" }}</div>
                    </li>
                    <li class="row" v-if="data.nature_science">
                        <div class="col-lg-3 list-item list-title">У журналах:</div>
                        <div class="col-lg-9 list-item list-text">{{data.nature_science}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Увійшли до 10% за БД Scopus найбільш цитованих публікацій для своєї предметної галузі:</div>
                        <div class="col-lg-9 list-item list-text">{{data.db_scopus_percent ? "Так" : "Ні"}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Увійшли до 1% за БД WoS найбільш цитованих публікацій для своєї предметної галузі:</div>
                        <div class="col-lg-9 list-item list-text">{{data.db_wos_percent ? "Так" : "Ні"}}</div>
                    </li>
                    <li class="row">
                        <div class="col-lg-3 list-item list-title">Процитована у міжнародних патентах:</div>
                        <div class="col-lg-9 list-item list-text">{{data.cited_international_patents ? "Так" : "Ні"}}</div>
                    </li>
                </template>
            </ul>

            <articles :data="data" v-if="data.publication_type.type == 'article'"></articles>
            <book :data="data" v-if="data.publication_type.type == 'book'"></book>
            <book :data="data" v-if="data.publication_type.type == 'monograph'"></book>
            <book :data="data" v-if="data.publication_type.type == 'monograph-part'"></book>
            <book-part :data="data" v-if="data.publication_type.type == 'book-part'"></book-part>
            <thesis :data="data" v-if="data.publication_type.type == 'thesis'"></thesis>
            <patent :data="data" v-if="data.publication_type.type == 'patent'"></patent>
            <certificate :data="data" v-if="data.publication_type.type == 'certificate'"></certificate>
            <methodical :data="data" v-if="data.publication_type.type == 'methodical'"></methodical>
            <electronic :data="data" v-if="data.publication_type.type == 'electronic'"></electronic>

            <ul class="list-view">
                <li class="row" v-if="authUser.roles_id == 4">
                    <div class="col-lg-3 list-item list-title">Публікація врахована в рейтингу попереднього року:</div>
                    <div class="col-lg-9 list-item list-text">{{ data.not_previous_year ? "Так" : "Ні" }}</div>
                </li>
                <li class="row" v-if="authUser.roles_id == 4">
                    <div class="col-lg-3 list-item list-title">Публікація не врахована в рейтингу цього року:</div>
                    <div class="col-lg-9 list-item list-text">{{ data.not_this_year ? "Так" : "Ні" }}</div>
                </li>
                <li class="row" v-if="data.publication_add">
                    <div class="col-lg-3 list-item list-title">Додано:</div>
                    <div class="col-lg-9 list-item list-text"><a :href="'/user/'+data.publication_add.id">{{data.publication_add.name}}</a></div>
                </li>
                <li class="row" v-if="data.publication_edit">
                    <div class="col-lg-3 list-item list-title">Редаговано:</div>
                    <div class="col-lg-9 list-item list-text"><a :href="'/user/'+data.publication_edit.id">{{data.publication_edit.name}}</a></div>
                </li>
            </ul>

            <div class="step-button-group">
                <back-button></back-button>
                <edit-button v-if="checkAccess" @click.native="editPublication"></edit-button>
                <delete-button v-if="checkAccess" @click.native="deletePublication"></delete-button>
            </div>
        </div>
        </transition>
    </div>
</template>

<script>
    import Articles from './View_fields/Articles';
    import Book from './View_fields/Book';

    import BookPart from "./View_fields/BookPart";
    import Thesis from "./View_fields/Thesis";
    import Patent from "./View_fields/Patent";
    import Certificate from "./View_fields/Certificate";
    import Methodical from "./View_fields/Methodical";
    import Electronic from "./View_fields/Electronic";

    import BackButton from "../Buttons/Back";
    import DeleteButton from "../Buttons/Delete";
    import EditButton from "../Buttons/Edit";

    export default {
        data() {
            return {
                data: {
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
                    sub_db_scie: "",
                    sub_db_ssci: "",
                    year: "",
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
                    nature_index: "",
                    nature_science: "",
                    db_scopus_percent: "",
                    db_wos_percent: "",
                    authors: [],
                    supervisor: null,
                    not_previous_year: false
                },
            }
        },
        components: {
            Articles,
            Book,
            BookPart,
            Thesis,
            Patent,
            Certificate,
            Methodical,
            Electronic,
            BackButton,
            DeleteButton,
            EditButton
        },
        created() {
            this.getPublicationData();
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            },
            checkAccess() {
                if(this.authUser.roles_id == 4) {
                    return true;
                } else if(this.$store.getters.accessMode == 'open' && (this.data.authors.find(item => item.id == this.authUser.id))) {
                    return true;
                } else if((this.$store.getters.accessMode == 'open' && this.authUser.roles_id != 1) && this.authUser.roles_id != 5) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        methods: {
            getPublicationData(){
                axios.get(`/api/publication/${this.$route.params.id}`).then(response => {
                    this.data = Object.assign(this.data, response.data);
                    this.data.authors = [];
                    response.data.authors.map(item => {
                        if(item.supervisor == 0) {
                            this.data.authors.push(item.author);
                        }
                        if(item.supervisor == 1) {
                            this.data.supervisor = item.author;
                        }
                    });
                });
            },
            editPublication() {
                this.$router.push({path: `/publications/edit/${this.$route.params.id}`});
            },
            deletePublication() {
                swal.fire({
                    title: 'Бажаєте видалити?',
                    text: "Після видалення ви не зможете відновити дані!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Видалити',
                    cancelButtonText: 'Відміна',
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/api/delete-publication/${this.$route.params.id}`, {
                            publication: this.data,
                            user: this.$store.getters.authUser
                        })
                        .then(() => {
                            swal.fire("Публікацію успішно видалено");
                            this.$router.push({path: `/publications`});
                        });
                    }
                })
            }
        }
    }
</script>
<style lang="css" scoped>
    .list-view {
        margin-bottom: -1px;
    }
</style>
