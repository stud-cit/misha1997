<template>
    <div>
        <p class="step-subtitle">
            Крок 2 з 4. Додання авторів
        </p>
        <div class=" step-content">


            <!--add to db author-->
            <div class="other-author" v-if="otherAuthor">
                <div class="popup-layout">

                    <!--ssu-->

                    <template v-if="otherAuthor == 1">
                        <h2 class="popup-title">Додати автора з СумДУ</h2>
                        <div class="form-group">
                            <label class="item-title">Оберіть категорію</label>
                            <div class="input-container">
                                <select class="item-value" v-model="selectCateg" @change="getPersonAPI">
                                    <option
                                        v-for="item in categ"
                                        :key="'item-'+item.value"
                                        :value="item.value"
                                    >
                                        {{item.name}}
                                    </option>
                                </select>
                                <div class="hint" ><span>Прізвище, ім’я, по-батькові</span></div>
                            </div>
                        </div>

                        <div v-if="loadingPersons" class="loading">Завантаження</div>

                        <div class="form-group" v-if="persons.length > 0">
                            <label class="item-title">Прізвище, ім’я, по-батькові</label>
                            <div class="input-container authors">
                                <multiselect
                                    v-model="newAuthor"
                                    label="name"
                                    :options="persons"
                                    :preserve-search="true"
                                    :placeholder="'Пошук в базі данних СумДУ'"
                                    :selectLabel="'Натисніть для вибору'"
                                    :selectedLabel="'Вибрано'"
                                    :deselectLabel="'Натисніть для видалення'"
                                    :custom-label="nameWithInfo"
                                >
                                    <span slot="noResult">По даному запиту немає результатів</span>
                                </multiselect>
                                <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                            </div>
                        </div>

                        <div class="row" v-if="newAuthor.name && selectCateg != 'categ1/2'">
                            <div class="form-group col pl-0">
                                <label>Інститут / факультет *</label>
                                <div class="input-container">
                                    <select v-model="newAuthor.faculty_code" @change="getDepartments">
                                        <option value=""></option>
                                        <option
                                            v-for="(item, index) in divisions"
                                            :key="index"
                                            :value="item.ID_DIV"
                                        >{{item.NAME_DIV}}</option>
                                    </select>
                                </div>
                                <div class="error" v-if="$v.newAuthor.faculty_code.$error">
                                    Поле обов'язкове для заповнення
                                </div>
                            </div>
                            <div class="form-group col pr-0">
                                <label>Кафедра *</label>
                                <div class="input-container">
                                    <select v-model="newAuthor.department_code">
                                        <option value=""></option>
                                        <option
                                            v-for="(item, index) in departments"
                                            :key="index"
                                            :value="item.ID_DIV"
                                        >{{item.NAME_DIV}}</option>
                                    </select>
                                </div>
                                <div class="error" v-if="$v.newAuthor.department_code.$error">
                                    Поле обов'язкове для заповнення
                                </div>
                            </div>
                        </div>

                        <div class="step-button-group sumdu-base">
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                            <button class="next active" @click="addNewAuthor">Додати </button>
                        </div>
                    </template>

                    <!--other author-->
                    <template v-if="otherAuthor == 2">
                        <h2 class="popup-title">Створення нового автора</h2>
                        <ul class=" list-view">


                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Прізвище, ім’я, по-батькові *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container hint-container">
                                        <input class="item-value" type="text" v-model="newAuthor.name">
                                        <div class="hint" ><span>Вказати прізвище, ім’я, по-батькові мовою оригіналу публікації:</span></div>
                                    </div>
                                    <div class="error" v-if="$v.newAuthor.name.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                </div>


                            </li>
                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Місце роботи *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <select class="item-value" v-model="jobType">
                                            <option value="1">Учбовий заклад</option>
                                            <option value="2">Підприємство</option>
                                            <option value="0">Не працює</option>
                                        </select>
                                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                                    </div>
                                    <div class="error" v-if="$v.jobType.$error">
                                        Поле обов'язкове для заповнення
                                    </div>

                                </div>

                            </li>
                            <li class="row" v-if="jobType == 1 || jobType == 2">
                                <div class="col-lg-3 list-item list-title">{{ jobType == 1 ? 'Назва учбового закладу' : 'Підприємство' }}</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <input class="item-value" type="text" v-model="newAuthor.job">
                                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                                    </div>
                                    <div class="error" v-if="$v.newAuthor.job.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                </div>

                            </li>
                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Країна автора *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <select class="item-value" v-model="newAuthor.country">
                                            <option
                                                v-for="(item, index) in country"
                                                :key="'country-'+index"
                                                :value="item.name"
                                            >{{item.name}}</option>
                                        </select>
                                        <div class="hint" ><span>Країна автора</span></div>
                                    </div>
                                </div>

                            </li>

                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Індекс Гірша</div>
                                <div class="col-lg-9  list-item list-text d-flex">
                                    <div class="col-lg-6 two-col pr-2">
                                        <label for="">БД Scopus</label>
                                        <div class=" input-container">
                                            <input class="item-value" type="text" v-model="newAuthor.scopus_autor_id">
                                            <div class="hint" ><span>title</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 two-col">
                                        <label for="">БД WoS</label>
                                        <div class=" input-container">
                                            <input class="item-value" type="text" v-model="newAuthor.h_index">
                                            <div class="hint" ><span>title</span></div>
                                        </div>
                                    </div>

                                </div>

                            </li>
                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Research ID</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <input class="item-value" type="text" v-model="newAuthor.scopus_researcher_id">
                                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                                    </div>
                                </div>

                            </li>
                            <li class="row">
                                <div class="col-lg-3 list-item list-title">ORCID</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <input class="item-value" type="text" v-model="newAuthor.orcid">
                                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                                    </div>
                                </div>

                            </li>
                        </ul>
                        <div class="step-button-group">
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                            <button class="next active" @click="addNewAuthor">Додати </button>

                        </div>
                    </template>




                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Під керівництвом</label>
                <div class="input-container hint-container">
                    <select class="item-value" v-model="stepData.useSupervisor" @change="changeSupervisor">
                        <option value="1">Так </option>
                        <option value="0">Ні </option>
                    </select>
                    <div class="hint" ><span>Зазначити "Так" у випадку одноосібної публікації студента</span></div>
                </div>

            </div>

            <div class="form-group" v-if="stepData.useSupervisor == '1'">
                <label class="item-title">Керівник *</label>
                <div class="input-container authors">
                    <multiselect
                        v-model="stepData.supervisor"
                        :options="authors"
                        label="name"
                        :searchable="true"
                        placeholder="Пошук в базі данних сайту"
                        selectLabel="Натисніть для вибору"
                        selectedLabel="Вибрано"
                        deselectLabel="Натисніть для видалення"
                        :custom-label="nameWithInfo"
                    >
                        <span slot="noResult">По даному запиту немає результатів</span>
                    </multiselect>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>

                <div class="error" v-if="$v.stepData.supervisor.$error">
                    Поле обов'язкове для заповнення
                </div>

            </div>


            <div class="form-group">
                <label class="item-title" >Автори:</label>
                <div class="add-group">
                    <div class="input-container hint-container">
                        <button class="add-button btn-blue" @click="addAuthor">
                            Додати автора
                        </button>
                        <div class="hint white-hint" ><span>Додати авторів з бази даних сайту, якщо їх більше одного</span></div>
                    </div>
                </div>
            </div>

            <div v-for="(item, i) in $v.stepData.authors.$each.$iter" :key="'author-'+i" class="form-group">
                <label for="">Прізвище, ім’я, по-батькові автора *</label>
                <div class="input-container authors">
                    <multiselect
                        v-model="stepData.authors[i]"
                        :searchable="true"
                        :options="authors"
                        selectLabel="Натисніть для вибору"
                        selectedLabel="Вибрано"
                        deselectLabel='Натисніть для видалення'
                        placeholder="Пошук в базі данних сайту"
                        :custom-label="nameWithInfo"
                    >
                        <span slot="noResult">По даному запиту немає результатів</span>
                    </multiselect>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    <button class="remove-author" @click="removeAuthor(i)">&times;</button>
                </div>
                <div class="error" v-if="$v.stepData.authors.$each.$iter[i].$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>


            <div class="form-group find-author" v-if="!findAuthor">
                <label class="item-title"><a href="#" @click.prevent="findAuthor=!findAuthor" >Не знайшли потрібного вам автора?</a></label>
            </div>
            <transition name="component-fade" mode="out-in">
                <div class="form-group " v-if="findAuthor">
                    <label class="item-title">Додати автора в базу данних сайту (якщо ви не знайшли потрібного вам автора) </label>
                    <div class="add-group">
                        <div class="input-container">
                            <button class="add-button btn-blue" @click="showNewAuthor(1)">
                                Додати автора з СумДУ
                            </button>
                            <div class="hint white-hint" ><span>Додати автора з СумДУ</span></div>
                        </div>
                        <div class="input-container">
                            <button class="add-button" @click="showNewAuthor(2)">
                                Створити нового автора
                            </button>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="form-group" v-show="stepData.authors.length > 0">
                <label class="item-title">Прізвища та ініціали авторів мовою оригіналу * </label>
                <div class="input-container hint-container">
                    <input class="item-value" type="text" v-model="stepData.initials">
                    <div class="hint" ><span>Ввести через кому прізвише та ініціали авторів мовою оригіналу публікації</span></div>
                </div>
                <div class="error" v-if="$v.stepData.initials.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>

        </div>
        <div class="step-button-group">
            <button class="prev" @click="prevStep">На попередній крок</button>
            <button class="next active" @click="nextStep">Продовжити </button>

        </div>
    </div>
</template>

<script>

    import Multiselect from 'vue-multiselect';
    import {required, requiredIf} from "vuelidate/lib/validators";
    export default {
        name: "Step2",
        data() {
            return {
                departments: [],
                divisions: [],
                jobType: null,
                modalAlias: false,
                findAuthor: false,

                categ: [
                    {
                        name: "Категорії",
                        value: null
                    },
                    {
                        name: "Студент",
                        value: "categ1/2"
                    },
                    {
                        name: "Аспірант",
                        value: "categ1/4"
                    },
                    {
                        name: "Співробітник",
                        value: "categ2/2"
                    },
                    {
                        name: "Викладач",
                        value: "categ2/4"
                    }
                ],
                selectCateg: null,
                loadingPersons: false,
                persons: [],
                supervisors: [],
                authors: [],
                job: "",
                country: [],
                otherAuthor: false,
                defaultNewAuthor: {},
                newAuthor: {
                    guid: null,
                    job: null,
                    name: '',
                    country: '',
                    h_index: '',
                    scopus_autor_id: '',
                    scopus_researcher_id: '',
                    orcid: '',
                    faculty_code: '',
                    department_code: ''
                },
                stepData: {
                    useSupervisor: '0',
                    supervisor: '',
                    authors: [],
                    initials: '',
                }
            }
        },
        props: {
          publicationData: Object
        },
        components: {
            Multiselect,
        },
        mounted() {
            this.defaultNewAuthor = Object.assign(this.defaultNewAuthor, this.newAuthor);
            if(this.publicationData.whose_publication == 'my') {
                this.stepData.authors.push(this.$store.getters.authUser);
            }
            this.getAuthors();
            this.checkPublicationData();
        },

        validations: {
            stepData: {
                supervisor: {
                    required: requiredIf ( function() {
                        return this.stepData.useSupervisor == '1';
                    })
                },
                authors: {
                    required,
                    $each: {
                        name: {
                            required,
                        }
                    }
                },
                initials: {
                    required
                },


            },
            jobType: {
                required: requiredIf ( function() {
                    return this.otherAuthor == '2';
                }),
            },
            newAuthor: {
                required: requiredIf ( function() {
                    return this.otherAuthor == '2';
                }),

                job: {
                    required: requiredIf ( function() {
                        return this.jobType > 0;
                    }),
                },
                name: {
                    required: requiredIf ( function() {
                        return this.otherAuthor == '2';
                    })
                },
                faculty_code: {
                    required: requiredIf ( function() {
                        return this.otherAuthor == '1' && this.selectCateg != 'categ1/2';
                    })
                },
                department_code: {
                    required: requiredIf ( function() {
                        return this.otherAuthor == '1' && this.selectCateg != 'categ1/2';
                    })
                }
            },

        },
        methods: {
            checkPublicationData() {
                if(this.publicationData && this.$route.name == 'publications-edit'){
                    const {supervisor, initials, authors} = this.publicationData;
                    console.log(this.publicationData)
                    this.stepData.useSupervisor = supervisor ? '1' : '0';
                    this.stepData.supervisor = supervisor;
                    this.stepData.initials = initials;
                    this.stepData.authors = authors.map((a)=>a.author);
                }
            },

            getDepartments() {
                this.departments = this.divisions.find(item => {
                    return this.newAuthor.faculty_code == item.ID_DIV
                }).departments;
            },

            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },

            nameWithInfo({name, faculty, department, academic_code, job, categ_1, categ_2}) {
                if(name) {
                    if(categ_1 == 1) {
                        return `${name} - ${academic_code}`
                    } else if(categ_1 == 2 || categ_2 == 2) {
                        return `${name} - ${department}`
                    } else if(categ_2 == 1) {
                        return `${name} - ${job}`
                    } else {
                        return `${name}`
                    }
                } else {
                    return "Пошук в базі данних сайту"
                }
            },

            nextStep () {
                this.$v.stepData.$touch();
                if (this.$v.stepData.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                this.$emit('getData', this.stepData);
            },
            prevStep () {
                this.$emit('prevStep');
            },
            changeSupervisor(){
                if(this.stepData.useSupervisor == '0') {
                    this.stepData.supervisor = null;
                    if(this.publicationData.whose_publication == 'my') {
                        this.stepData.authors.push(this.$store.getters.authUser);
                    }
                } else {
                    if(this.$route.name != 'publications-edit') {
                        this.stepData.authors.splice(this.stepData.authors.indexOf(this.$store.getters.authUser), 1);
                    }
                }
            },
            addAuthor() {
                this.stepData.authors.push({
                    name: '',
                    faculty: "",
                    academic_code: ""
                })
            },
            removeAuthor(i) {
                if(this.stepData.authors.length>1) {
                    this.stepData.authors.splice(i, 1);
                }
                else{
                    swal("Повинен бути хоча б один автор!", {
                        icon: "error",
                    });
                }
            },
            showNewAuthor(n) {
                this.getCountry();
                this.getDivisions();
                window.scrollTo(0,0);
                this.otherAuthor = n;

            },
            getPersonAPI() {
                if(this.selectCateg) {
                    this.loadingPersons = true;
                    this.persons = [];
                    axios.get(`/api/persons/${this.selectCateg}`).then(response => {
                        return response.data.result.map(item => {
                            return {
                                categ_1: item.CATEG_1,
                                categ_2: item.CATEG_2,
                                guid: item.ID_FIO,
                                name: item.F_FIO + " " + item.I_FIO + " " + item.O_FIO,
                                academic_code: item.CATEG_1 == 1 ? item.NAME_GROUP : "",
                                faculty_code: item.CATEG_1 == 1 ? item.KOD_DIV : "",
                                faculty: item.CATEG_1 == 1 ? item.NAME_DIV : "",
                                department_code: (item.CATEG_1 == 2 || item.CATEG_2 == 2) ? item.KOD_DIV : "",
                                department: (item.CATEG_1 == 2 || item.CATEG_2 == 2) ? item.NAME_DIV : "",
                                job: item.CATEG_2 == 1 ? item.NAME_DIV : "",
                            }
                        });

                    }).then(result => {
                        this.persons = result;
                        this.loadingPersons = false;
                    }).catch(() => {
                        this.loadingPersons = false;
                    })
                }
            },
            getAuthors() {
                axios.get(`/api/authors-all`).then(response => {
                    console.log(response.data)
                    this.authors = response.data;
                })
            },

            addNewAuthor() {
                this.$v.newAuthor.$touch();
                if (this.$v.newAuthor.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                if(this.newAuthor.categ_2 == 2) {
                    this.newAuthor.job = "СумДУ";
                } else {
                    this.newAuthor.job = this.jobType == 1 ? this.newAuthor.job : "Не працює";
                }

                axios.post('/api/author', this.newAuthor)
                .then((response) => {
                    if(response.data.status == 'ok') {
                        this.otherAuthor = false;
                        this.getAuthors();
                        this.newAuthor = this.defaultNewAuthor;
                        swal("Автора успішно додано!", {
                            icon: "success",
                        });
                    } else {
                        swal({
                            icon: "error",
                            title: 'Помилка',
                            text: "Автор вже зареєстрований в системі"
                        });
                    }
                })
            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },

        }
    }
</script>

<style lang="scss" scoped>
    .find-author{
        margin-bottom: 20px;
    }
    .other-author{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
        padding: 5% 10%;
        width: 100%;
        min-height: 100%;
        background: rgba(0,0,0,0.8);
        .popup-layout{
            padding: 60px 60px;
            background-color: #fff;
            border-radius: 10px;
            .popup-title{
                margin-bottom: 30px;
                font-family: Arial;
                font-style: normal;
                font-weight: normal;
                font-size: 30px;
                text-align: center;
                /*color: #18A0FB;*/
            }
            .row{
                margin: 0;
            }
        }
    }
    .add-group {
        display: flex;
        justify-content: space-between;
        .hint {
            top: 10px;
            left: 10px
        }
        .add-button {
            padding: 13px;
            padding-right: 0;
            font-family: Arial;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            line-height: 23px;
            text-align: center;
            color: #007BFF;
            outline: none;
            background: transparent;
        }
        .btn-blue {
            background: #007BFF;
            color: #fff;
            outline: none;
            padding-right: 15px;
        }
        .white-hint {
            background: url("/img/hint_white.svg");
        }
        .hint-container{
            .add-button {
                padding: 13px 48px;
            }
        }
    }
    .loading{
        text-align: center;
        font-size: 22px;
        font-weight: bold;
        margin: 30px 0;
    }
    @media  (max-width: 575px) {
        .other-author{
            padding: 10% 10px;
            .popup-layout{
                padding: 15px;
                .popup-title{
                    margin-bottom: 20px;
                    font-size: 25px;
                }
            }
        }
        .add-group {
            flex-wrap: wrap;
            .add-button {
                margin-top: 10px;
            }
            .hint{
                top: 20px;
            }
        }
    }
</style>
