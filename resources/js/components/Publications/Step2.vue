<template>
    <div>
        <p class="step-subtitle">
            Крок 2 з 4. Додання авторів
        </p>
        <div class="step-content">

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
                            </div>
                        </div>

                        <div v-if="loadingPersons" class="loading">Завантаження</div>

                        <div class="form-group" v-if="persons.length > 0">
                            <label class="item-title">Прізвище, ім’я, по-батькові</label>
                            <div class="input-container authors">
                                <multiselect
                                    v-model="newAuthorSSU"
                                    label="name"
                                    :options="persons"
                                    :preserve-search="true"
                                    :placeholder="'Пошук в базі данних СумДУ'"
                                    :selectLabel="'Натисніть для вибору'"
                                    :selectedLabel="'Вибрано'"
                                    :deselectLabel="'Натисніть для видалення'"
                                    :custom-label="nameWithInfoSSU"
                                >
                                    <span slot="noResult">По даному запиту немає результатів</span>
                                </multiselect>
                            </div>
                        </div>
                        <div class="step-button-group sumdu-base">
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                            <button class="next active" @click="addNewAuthorSSU">Додати</button>
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
                                        <input class="item-value" type="text" list="names" v-model="newAuthor.name" @input="findNames">
                                        <datalist id="names">
                                            <option v-for="(item, index) in names" :key="index" :value="item.name">{{ item.job }}</option>
                                        </datalist>
                                        <div class="hint"><span>Вказати прізвище, ім’я, по-батькові мовою оригіналу публікації:</span></div>
                                    </div>
                                    <div class="error" v-if="$v.newAuthor.name.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                     <div class="error" v-if="errorName">
                                        Автор вже зареєстрований в системи: {{ errorName.name }} - {{ errorName.job }}
                                    </div>
                                </div>
                            </li>
                            <li class="row">
                                <div class="col-lg-3 list-item list-title">Місце роботи *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <select class="item-value" v-model="jobType" @change="setJobType">
                                            <option value="1">Учбовий заклад</option>
                                            <option value="2">Підприємство</option>
                                            <option value="3">Студент - випускник</option>
                                            <option value="0">Не працює</option>
                                        </select>
                                    </div>
                                    <div class="error" v-if="$v.jobType.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                </div>
                            </li>
                            <li class="row" v-if="jobType == 1 || jobType == 2">
                                <div class="col-lg-3 list-item list-title">{{ jobType == 1 ? 'Назва учбового закладу *' : 'Підприємство *' }}</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <input class="item-value" type="text" v-model="newAuthor.job">
                                    </div>
                                    <div class="error" v-if="$v.newAuthor.job.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                </div>
                            </li>
                            <li class="row" v-if="jobType == 2">
                                <div class="col-lg-3 list-item list-title">Входить до списків Forbes та Fortune *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <div class="input-container">
                                        <select v-model="newAuthor.forbes_fortune">
                                            <option value="1">Так</option>
                                            <option value="0">Ні</option>
                                        </select>
                                    </div>
                                    <div class="error" v-if="$v.newAuthor.forbes_fortune.$error">
                                        Поле обов'язкове для заповнення
                                    </div>
                                </div>
                            </li>
                            <li class="row" v-show="jobType != 3">
                                <div class="col-lg-3 list-item list-title">Країна автора *</div>
                                <div class="col-lg-9 list-item list-text">
                                    <Country :data="newAuthor"></Country>
                                    <div class="error" v-if="$v.newAuthor.country.$error">
                                        Поле обов'язкове для заповнення
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
                    <select class="item-value" v-model="publicationData.useSupervisor" @change="changeSupervisor">
                        <option value="1">Так</option>
                        <option value="0">Ні</option>
                    </select>
                    <div class="hint" ><span>Зазначити "Так" у випадку одноосібної публікації студента</span></div>
                </div>

            </div>

            <div class="form-group" v-if="publicationData.useSupervisor == '1'">
                <label class="item-title">Керівник *</label>
                <div class="input-container authors">
                    <multiselect
                        v-model="publicationData.supervisor"
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

                <div class="error" v-if="$v.publicationData.supervisor.$error">
                    Поле обов'язкове для заповнення
                </div>

            </div>


            <div class="form-group">
                <label class="item-title">Автори:</label>
                <div v-for="(item, i) in publicationData.authors" :key="'author-'+i" class="form-group">
                    <label for="">Прізвище, ім’я, по-батькові автора *</label>
                    <div class="input-container authors">
                        <multiselect
                            v-model="publicationData.authors[i]"
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
                        <button class="remove-author" @click="removeAuthor(i)" v-if="(publicationData.whose_publication == 'my' && item.id != $store.getters.authUser.id) || publicationData.whose_publication != 'my'">&times;</button>
                    </div>
                    <div class="error" v-if="$v.publicationData.authors.$each.$iter[i].$error">
                        Поле обов'язкове для заповнення
                    </div>
                </div>
                <div class="add-group">
                    <div class="input-container hint-container">
                        <button class="add-button btn-blue" @click="addAuthor">
                            Додати автора
                        </button>
                        <label class="item-title find-author" v-if="!findAuthor">
                            <a href="#" @click.prevent="findAuthor=!findAuthor">Не знайшли потрібного вам автора?</a>
                        </label>
                        <div class="hint white-hint"><span>Додати авторів з бази даних сайту, якщо їх більше одного</span></div>
                    </div>
                </div>
            </div>

            <transition name="component-fade" mode="out-in">
                <div class="form-group " v-if="findAuthor">
                    <label class="item-title">Додати автора в базу данних сайту (якщо ви не знайшли потрібного вам автора) <a href="#" @click="findAuthor = false">закрити</a></label>
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

            <div class="form-group" v-show="publicationData.authors.length > 0">
                <label class="item-title">Прізвища та ініціали авторів мовою оригіналу * </label>
                <div class="input-container hint-container">
                    <input class="item-value" type="text" v-model="publicationData.initials">
                    <div class="hint" ><span>Ввести через кому прізвише та ініціали авторів мовою оригіналу публікації</span></div>
                </div>
                <div class="error" v-if="$v.publicationData.initials.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>

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
    import Country from "../Forms/Country";
    import Multiselect from 'vue-multiselect';
    import {required, requiredIf} from "vuelidate/lib/validators";
    export default {
        name: "Step2",
        data() {
            return {
                departments: [],
                divisions: [],
                jobType: null,
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
                    },
                    {
                        name: "Менеджер",
                        value: "categ2/8"
                    }
                ],
                selectCateg: null,
                loadingPersons: false,
                persons: [],
                supervisors: [],
                authors: [],
                country: [],
                otherAuthor: false,
                errorName: null,
                otherAuthorNames: [],
                names: [],
                defaultNewAuthorSSU: {},
                newAuthorSSU: {
                    name: '',
                    kod_div: '',
                    name_div: '',
                    categ_1: '',
                    categ_2: '',
                    job: '',
                    country: ''
                },
                newAuthor: {
                    job: '',
                    name: '',
                    country: null,
                    h_index: '',
                    scopus_autor_id: '',
                    scopus_researcher_id: '',
                    orcid: '',
                    forbes_fortune: 0
                }
            }
        },
        props: {
          publicationData: Object
        },
        components: {
            Multiselect,
            CloseEditButton,
            Country
        },
        mounted() {
            this.defaultNewAuthorSSU = Object.assign(this.defaultNewAuthorSSU, this.newAuthorSSU);
            if(this.publicationData.whose_publication == 'my') {
                this.publicationData.authors.push(this.$store.getters.authUser);
            }
            this.getAuthors();
        },

        validations: {
            publicationData: {
                supervisor: {
                    required: requiredIf ( function() {
                        return this.publicationData.useSupervisor == '1';
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
                required
            },
            newAuthor: {
                country: {
                    required: requiredIf(function() {
                        return this.jobType != 3;
                    })
                },
                job: {
                    required: requiredIf(function() {
                        return this.jobType == 1 || this.jobType == 2;
                    })
                },
                name: {
                    required
                },
                forbes_fortune: {
                    required: requiredIf(function() {
                        return this.jobType == 2;
                    })
                }
            },
        },
        methods: {
            // задає місце роботи для новго автора не з СумДУ
            setJobType() {
                if(this.jobType == 0 || this.jobType == 3) {
                    this.newAuthor.job = "Не працює";
                    this.newAuthor.forbes_fortune = 0;
                } else {
                    this.newAuthor.job = '';
                }
            },
            // пошук схожих імен авторів не з СумДУ
            findNames() {
                this.names = this.otherAuthorNames.filter(item => {
                    return item.name.toLowerCase().indexOf(this.newAuthor.name.toLowerCase()) + 1
                })
            },
            // форматування відображення списку авторів зареєстрованих на сайті (новий автор, керівник)
            nameWithInfo({name, faculty, department, academic_code, job, categ_1, categ_2}) {
                if(name) {
                    if(categ_1 == 1) {
                        return `${name} - ${academic_code}`
                    } else if(categ_1 == 2 || categ_2 == 1 || categ_2 == 2 || categ_2 == 3) {
                        if(department) {
                            return `${name} - ${department}`
                        } else {
                            return `${name} - ${job}`
                        }
                    } else {
                        return `${name} - ${job}`
                    }
                } else {
                    return "Пошук в базі данних сайту"
                }
            },
            // форматування відображення списку авторів із СумДУ в формі додання нового автора СумДУ
            nameWithInfoSSU({name, name_div}) {
                if(name) {
                    if(name_div) {
                        return `${name} - ${name_div}`
                    } else {
                        return `${name}`
                    }
                } else {
                    return "Пошук в базі данних сайту"
                }
            },

            nextStep() {
                this.$v.publicationData.$touch();
                if (this.$v.publicationData.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                this.$emit('getData');
            },
            prevStep() {
                this.$emit('prevStep');
            },
            changeSupervisor() {
                if(this.publicationData.useSupervisor == '0') {
                    this.publicationData.supervisor = null;
                }
            },
            // додання автора в список авторів публікації
            addAuthor() {
                if(!this.publicationData.authors.find(item => item.name == "")) {
                    this.publicationData.authors.push({
                        name: '',
                        faculty: "",
                        academic_code: ""
                    })
                }
            },
            // видалення автора із списку авторів публікації
            removeAuthor(i) {
                if(this.publicationData.authors.length>1) {
                    this.publicationData.authors.splice(i, 1);
                }
                else{
                    swal("Повинен бути хоча б один автор!", {
                        icon: "error",
                    });
                }
            },
            // відображення форми додання автора з СумДУ (n = 1) та іншого (n = 2)
            showNewAuthor(n) {
                if(n == 1) {
                    this.getDivisions();
                }
                if(n == 2) {
                    this.getOtherAuthorNames();
                }
                window.scrollTo(0,0);
                this.otherAuthor = n;
            },
            // сортування кафедр по факультету
            getDepartments() {
                this.departments = this.divisions.find(item => {
                    return this.newAuthor.faculty_code == item.ID_DIV
                }).departments;
            },

            //getters API
            // імена користувачів не з СумДУ
            getOtherAuthorNames() {
                axios.get(`/api/others-authors-name`).then(response => {
                    this.names = [];
                    this.otherAuthorNames = response.data;
                })
            },
            // всі користувачів зареєстровані на сайті
            getAuthors() {
                axios.get(`/api/authors-all`).then(response => {
                    this.authors = response.data;
                })
            },
            // список корисувачів з API ASU по обраній категорії
            getPersonAPI() {
                if(this.selectCateg) {
                    this.loadingPersons = true;
                    this.persons = [];
                    axios.get(`/api/persons/${this.selectCateg}`).then(response => {
                        this.persons = response.data;

                    }).then(result => {
                        this.loadingPersons = false;
                    }).catch(() => {
                        this.loadingPersons = false;
                    })
                }
            },
            // всі факультети, кафедри
            getDivisions() {
                axios.get('/api/sort-divisions').then(response => {
                    this.divisions = response.data;
                })
            },

            // posts API
            // додання новго автора з СумДУ
            addNewAuthorSSU() {
                axios.post('/api/author-ssu', this.newAuthorSSU)
                .then((response) => {
                    if(response.data.status == 'ok') {
                        this.otherAuthor = false;
                        this.selectCateg = null;
                        this.persons = [];
                        this.getAuthors();
                        this.newAuthorSSU = this.defaultNewAuthorSSU;
                        this.publicationData.authors.map(item => {
                            if(item.name == "") {
                                let index = this.publicationData.authors.indexOf(item);
                                this.publicationData.authors.splice(index, 1);
                            }
                        })
                        this.publicationData.authors.push(response.data.user);
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
            // додання нового автора не з СумДУ
            addNewAuthor() {
                this.$v.$touch();
                if (this.$v.newAuthor.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return;
                }
                var findAuthor = this.otherAuthorNames.find(item => item.name.toLowerCase() == this.newAuthor.name.toLowerCase() && (item.job && item.job.toLowerCase() == this.newAuthor.job.toLowerCase()));
                if(findAuthor) {
                    this.errorName = findAuthor;
                    return;
                } else {
                    this.errorName = null;
                }
                if(this.jobType == 3) {
                    this.newAuthor.country = "Україна";
                }
                axios.post('/api/author', this.newAuthor)
                .then((response) => {
                    this.otherAuthor = false;
                    this.getAuthors();
                    this.jobType = null;
                    this.newAuthor = {
                        job: '',
                        name: '',
                        country: null,
                        h_index: '',
                        scopus_autor_id: '',
                        scopus_researcher_id: '',
                        orcid: '',
                        forbes_fortune: 0
                    };
                    this.publicationData.authors.map(item => {
                        if(item.name == "") {
                            let index = this.publicationData.authors.indexOf(item);
                            this.publicationData.authors.splice(index, 1);
                        }
                    })
                    this.publicationData.authors.push(response.data.user);
                    this.$v.$reset();
                    swal("Автора успішно додано!", {
                        icon: "success",
                    });
                }).catch((error) => {
                    swal({
                        icon: "error",
                        title: 'Помилка',
                        text: error.message
                    });
                })
            },
        }
    }
</script>

<style lang="scss" scoped>
    .find-author {
        margin-left: 20px;
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
