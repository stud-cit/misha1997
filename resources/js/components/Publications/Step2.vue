<template>
    <div>
        <p class="step-subtitle">
            Крок 2 з 4. Додання авторів
        </p>
        <div class=" step-content">
            <div class="step-item">
                <p class="item-title">Додати автора в базу данних сайту (якщо ви не знайшли потрібного вам автора) :</p>
                <div class="button-group">
                    <button class="small-box btn-blue" @click="showNewAuthor(1)">
                        Додати автора з СумДУ
                    </button>
                    <button class="small-box btn-blue" @click="showNewAuthor(2)">
                        Додати іншого автора
                    </button>
                </div>
            </div>

            <!--add to db author-->
            <div class="other-author" v-if="otherAuthor">
                <div class="wrapper">

                    <!--ssu-->

                    <div class="container" v-if="otherAuthor == 1">
                        <h2 class="popup-title">Додати автора з СумДУ</h2>
                        <div class="step-item">
                            <p class="item-title">Оберіть категорію:</p>
                            <div class="authors supervisor">
                                <select class="item-value" v-model="selectCateg" @change="getPersonAPI">
                                    <option
                                        v-for="item in categ"
                                        :key="item.value"
                                        :value="item.value"
                                    >
                                        {{item.name}}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div v-if="loadingPersons" style="text-align: center">Завантаження</div>

                        <div class="step-item" v-if="persons.length > 0">
                            <p class="item-title">Прізвище, ім’я, по-батькові:</p>
                            <div class="authors supervisor">
                                <multiselect
                                    v-model="ssuAuthor"
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
                            </div>
                        </div>
                        <div class="step-button-group sumdu-base mt-2">
                            <button class="next active" @click="addNewAuthor(ssuAuthor)">Додати <span>&gt;</span></button>
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                        </div>
                    </div>

                    <!--other author-->

                    <div class="container" v-if="otherAuthor == 2">
                        <h2 class="popup-title">Додавання нового автора</h2>
                        <div class="step-item">
                            <label class="item-title">Прізвище, ім’я, по-батькові:</label>
                            <input class="item-value" type="text" v-model="newAuthor.name">
                        </div>
                        <div class="step-item">
                            <label class="item-title">Псевдонім:</label>
                            <input class="item-value" type="text" v-model="newAuthor.alias">
                        </div>
                        <div class="step-item">
                            <label class="item-title">Місце роботи:</label>
                            <select class="item-value" v-model="jobType">
                                <option value="1">Учбовий заклад</option>
                                <option value="1">Підприємство</option>
                                <option value="0">Не працює</option>
                            </select>
                        </div>
                        <div class="step-item" v-if="jobType == 1">
                            <label class="item-title">Назва місця роботи:</label>
                            <input class="item-value" type="text" v-model="newAuthor.job">
                        </div>
                        <div class="step-item">
                            <label class="item-title">Країна:</label>
                            <select class="item-value" v-model="newAuthor.country">
                                <option
                                    v-for="(item, index) in country"
                                    :key="index"
                                    :value="item.name"
                                >{{item.name}}</option>
                            </select>
                        </div>
                        <div class="step-item">
                            <label class="item-title">Індекс Хірша:</label>
                            <input class="item-value" type="text" v-model="newAuthor.h_index">
                        </div>
                        <div class="step-item">
                            <label class="item-title">Author ID:</label>
                            <input class="item-value" type="text" v-model="newAuthor.scopus_autor_id">
                        </div>
                        <div class="step-item">
                            <label class="item-title">Research ID:</label>
                            <input class="item-value" type="text" v-model="newAuthor.scopus_researcher_id">
                        </div>
                        <div class="step-item">
                            <label class="item-title">ORCID:</label>
                            <input class="item-value" type="text" v-model="newAuthor.orcid">
                        </div>
                        <div class="step-button-group">
                            <button class="next active" @click="addNewAuthor(newAuthor)">Додати <span>&gt;</span></button>
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                        </div>
                    </div>

                    <div class="container" v-if="otherAuthor == 3">
                        <h2 class="popup-title">Додати псевдонім</h2>
                        <div class="step-item">
                            <p class="item-title">Оберіть категорію:</p>
                            <div class="authors supervisor">
                                <input class="item-value" type="text" v-model="newAlias.surname_initials">
                            </div>
                        </div>
                        <div class="step-button-group sumdu-base mt-2">
                            <button class="next active" @click="addAlias()">Додати <span>&gt;</span></button>
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="step-item">
                <p class="item-title">Під керівництвом:</p>
                <div class="categories-elem">
                    <input id="leadership1" type="radio" v-model="stepData.useSupervisor" value="1">
                    <label class="small-box" for="leadership1">Так</label>
                    <input id="leadership0" type="radio" v-model="stepData.useSupervisor" value="0">
                    <label class="small-box" for="leadership0">Ні</label>
                </div>
            </div>
            <div class="step-item" v-if="stepData.useSupervisor == '1'">
                <p class="item-title">Керівник:</p>
                <div class="authors supervisor">
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
                </div>

            </div>
            <div class="step-item">
                <p class="item-title" >Автори:</p>

                <button class="small-box btn-blue  mb-4" @click="addAuthor">
                    Додати автора
                </button>
                <div v-for="(item, i) in stepData.authors" :key="i" class="mb-2">
                    <div class="authors supervisor">
                        <p class="num">{{'№&nbsp;' + (i+1)}}</p>
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
                        <button class="remove-author" @click="removeAuthor(i)">&times;</button>
                    </div>
                    <span v-for="(alias, index) in item.alias" :key="index" @click="setAlias(alias)">{{ alias.surname_initials }} - {{ alias.select }}</span>
                    <input type="submit" value="Додати псевдонім" @click="openModalAlias(item, i)">
                </div>
            </div>

        </div>
        <div class="step-button-group">
            <button class="next active" @click="nextStep">Продовжити <span>&gt;</span></button>
            <button class="prev" @click="prevStep">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    export default {
        name: "Step2",
        data() {
            return {
                jobType: null,
                modalAlias: false,
                newAlias: {
                    surname_initials: "",
                    autors_id: null,
                    id: null
                },

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
                        name: "Випускник",
                        value: "categ1/8"
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
                ssuAuthor: "",
                persons: [],
                supervisors: [],
                authors: [],
                job: "",
                country: [],
                otherAuthor: false,
                newAuthor: {
                    guid: null,
                    job: null,
                    name: '',
                    country: "Україна",
                    h_index: '',
                    scopus_autor_id: '',
                    scopus_researcher_id: '',
                    orcid: '',
                    alias: ''
                },
                stepData: {
                    useSupervisor: '0',
                    supervisor: '',
                    authors: [
                        {
                            name: '',
                            alias: [],
                            faculty: "",
                            academic_code: ""
                        },
                    ],
                    initials: '',
                }
            }
        },
        components: {
            Multiselect,
        },
        created() {
            this.getAuthors();
        },
        methods: {
            setAlias(alias) {
                alias.select ? alias.select = 0 : alias.select = 1;
            },
            nameWithInfo({name, faculty, academic_code, job}) {
                if(name == "") {
                    return "Пошук в базі данних сайту"
                } else {
                    return `${name} — ${academic_code ? academic_code : (faculty ? faculty : job)}`
                }
            },
            nextStep () {

                // create initials

                this.stepData.initials = this.stepData.authors.map(
                    (a) => {
                        const arr = a.name.split(' ');
                        return arr[0] + ' ' + arr[1][0] + '. ' + arr[2][0] + '.'
                    }
                ).join(', ');
                //

                this.$emit('getData', this.stepData);
            },
            prevStep () {
                this.$emit('prevStep');
            },
            addAuthor() {
                this.stepData.authors.push({
                    name: '',
                    alias: [],
                    faculty: "",
                    academic_code: ""
                })
            },
            removeAuthor(i) {
                this.stepData.authors.splice(i, 1);
            },
            showNewAuthor(n) {
                this.getCountry();
                this.otherAuthor = n;
            },
            getPersonAPI() {
                if(this.selectCateg) {
                    this.loadingPersons = true;
                    this.persons = [];
                    axios.get(`/api/persons/${this.selectCateg}`).then(response => {
                        return response.data.result.map(item => {
                            return {
                                guid: item.ID_FIO,
                                name: item.F_FIO + " " + item.I_FIO + " " + item.O_FIO,
                                academic_code: item.NAME_GROUP,
                                faculty: item.NAME_DIV
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
                axios.get(`/api/authors`).then(response => {
                    this.authors = response.data;
                })
            },
            addNewAuthor(newAuthor) {
                newAuthor.job = this.jobType == 1 ? newAuthor.job : "Не працює";
                axios.post('/api/author', newAuthor)
                .then((response) => {
                    this.ssuAuthor = "";
                    this.otherAuthor = false;
                    this.authors.push(response.data);
                    swal("Автора успішно додано!", {
                        icon: "success",
                    });
                })
                .catch(() => {
                    this.otherAuthor = false;
                    swal({
                        icon: "error",
                        title: 'Помилка',
                        text: String(error.response.status)
                    });
                });
            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            openModalAlias(item, i) {
                this.otherAuthor = 3
                this.newAlias.autors_id = item.id
                this.newAlias.id = i
            },
            addAlias() {
                axios.post('/api/alias', this.newAlias)
                .then((response) => {
                    this.otherAuthor = false;
                    this.stepData.authors[this.newAlias.id].alias.push(response.data);
                    swal("Автора успішно додано!", {
                        icon: "success",
                    });
                })
                .catch(() => {
                    this.otherAuthor = false;
                    swal({
                        icon: "error",
                        title: 'Помилка'
                    });
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    // .step-item{
    //     .item-title{
    //         padding-left: 0;
    //         margin-bottom: 20px;
    //     }
    // }
    .other-author{
        position: absolute;
        top: 0;
        left: 0;
        z-index: 100;
        padding: 5% 15%;
        width: 100%;
        min-height: 100%;
        background: rgba(0,0,0,0.8);
        .wrapper{
            padding: 60px 0;
            background-color: #fff;
            border-radius: 20px;
            .popup-title{
                margin-bottom: 30px;
                font-family: Montserrat;
                font-style: normal;
                font-weight: normal;
                font-size: 30px;
                text-align: center;
                color: #18A0FB;
            }
        }
    }
    .sumdu-base .authors-btn {
        padding: 10px 40px;
        margin: 0 10px 30px;
        color: #FFFFFF;
        background: #A6A6A6;
        box-sizing: border-box;
        border-radius: 44.5px;
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 25px;
        cursor: pointer;
    }
    .small-box{
        padding: 10px 40px;
        margin: 0 10px;
        border: 1px solid #18A0FB;
        box-sizing: border-box;
        border-radius: 44.5px;
        font-family: Montserrat;
        font-style: normal;
        font-weight: normal;
        font-size: 20px;
        color: #18A0FB;
        cursor: pointer;
        &:first-of-type{
            margin-left: 0;
        }
    }
    .btn-blue{
        background: #18A0FB;
        border: 1px solid #18A0FB;
        color: #fff;
        outline: none;
    }
    input[type="radio"]{
        display: none;
    }
    input:checked +label{
        background: #18A0FB;
        border: 1px solid #18A0FB;
        color: #fff;
    }
</style>
