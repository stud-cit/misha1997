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

            <!--            add to db author-->
            <div class="other-author" v-if="otherAuthor" >
                <div class="wrapper" >

                    <!--ssu-->

                    <div class="container" v-if="otherAuthor == 1">
                        <h2 class="popup-title">Додати автора з СумДУ</h2>
                        <div class="step-item" >
                            <p class="item-title">Прізвище, ім’я, по-батькові :</p>
                            <div class="authors supervisor">
                                <multiselect
                                    v-model="ssuAuthor"
                                    :options="persons"
                                    :placeholder="'Пошук в базі данних СумДУ'"
                                    :selectLabel="'Натисніть для вибору'"
                                    :selectedLabel="'Вибрано'"
                                    :deselectLabel="'Натисніть для видалення'"
                                >
                                    <span slot="noResult">По даному запиту немає результатів</span>
                                </multiselect>
                            </div>
                        </div>
                        <div class="step-button-group mt-2">
                            <button class="next active" @click="">Додати <span>&gt;</span></button>
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                        </div>
                    </div>

                    <!--     other author-->

                    <div class="container" v-if="otherAuthor == 2">
                        <h2 class="popup-title">Додавання нового автора</h2>
                        <div class="step-item">
                            <label class="item-title">Прізвище, ім’я, по-батькові :</label>
                            <input class="item-value" type="text" v-model="newAuthor.name">

                        </div>
                        <div class="step-item">
                            <label class="item-title">Псевдонім :</label>
                            <input class="item-value" type="text" v-model="newAuthor.alias">

                        </div>
                        <div class="step-item">
                            <label class="item-title">Місце роботи :</label>
                            <input class="item-value" type="text" v-model="newAuthor.job">

                        </div>
                        <div class="step-item">
                            <label class="item-title">Країна :</label>
                            <input class="item-value" type="text" v-model="newAuthor.country">

                        </div>
                        <div class="step-item">
                            <label class="item-title">Індекс Хірша :</label>
                            <input class="item-value" type="text" v-model="newAuthor.hIndex">

                        </div>
                        <div class="step-item">
                            <label class="item-title">Ідентифікатор профілю :</label>
                            <input class="item-value" type="text" v-model="newAuthor.profId">

                        </div>
                        <div class="step-button-group">
                            <button class="next active" @click="">Додати <span>&gt;</span></button>
                            <button class="prev" @click="otherAuthor = !otherAuthor">Назад</button>
                        </div>
                    </div>
                </div>
            </div>



            <div class="step-item">
                <p class="item-title">Під керівництвом :</p>
                <div class="categories-elem">
                    <input id="leadership1" type="radio" v-model="stepData.useSupervisor" value="1">
                    <label class="small-box" for="leadership1">Так</label>
                    <input id="leadership0" type="radio" v-model="stepData.useSupervisor" value="0">
                    <label class="small-box" for="leadership0">Ні</label>
                </div>
            </div>
            <div class="step-item" v-if="stepData.useSupervisor == '1'">
                <p class="item-title">Керівник :</p>
                <div class="authors supervisor">
                    <multiselect
                        v-model="stepData.supervisor"
                        :options="authorsData"
                        :placeholder="'Пошук в базі данних сайту'"
                        :selectLabel="'Натисніть для вибору'"
                        :selectedLabel="'Вибрано'"
                        :deselectLabel="'Натисніть для видалення'"
                    >
                        <span slot="noResult">По даному запиту немає результатів</span>
                    </multiselect>
                </div>

            </div>
            <div class="step-item">
                <p class="item-title" >Автори :</p>

                <button class="small-box btn-blue  mb-4" @click="addAuthor">
                    Додати автора
                </button>
                <div v-for="(item, i) in stepData.authors" :key="i" class="mb-2">

                    <div class="authors" >
                        <p class="num">{{'№&nbsp;' + (i+1)}}</p>
                        <multiselect
                            v-model="item.name"
                            :options="authorsData"
                            :placeholder="'Пошук в базі данних сайту'"
                            :selectLabel="'Натисніть для вибору'"
                            :selectedLabel="'Вибрано'"
                            :deselectLabel="'Натисніть для видалення'"
                            :disabled="item.useAlias"
                        >
                            <span slot="noResult">По даному запиту немає результатів</span>
                        </multiselect>

                        <button class="authors-btn" @click="useAlias(i)"> {{ item.useAlias ? 'Використати ПІБ' : 'Використати псевдонім'}}</button>
                        <button class="remove-author" @click="removeAuthor(i)">&times;</button>



                    </div>
                    <div class="authors alias" v-if="item.useAlias">
                        <multiselect
                            v-model="item.alias"
                            :options="item.aliasOptions"
                            :placeholder="'Виберіть псевдонім'"
                            :selectLabel="'Натисніть для вибору'"
                            :selectedLabel="'Вибрано'"
                            :deselectLabel="'Натисніть для видалення'"
                        >
                            <span slot="noResult">По даному запиту немає результатів</span>
                        </multiselect>
                    </div>

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
                otherAuthor: false,
                newAuthor: {
                    name: '',
                    alias: '',
                    job: '',
                    country: '',
                    hIndex: '',
                    profId: ''
                },
                ssuAuthor: '',
                authorsData: ['Петренко', 'Іванов', 'Іваненко', 'Петросян'],
                persons: [],

                stepData: {
                    useSupervisor: '0',
                    supervisor: '',
                    authors: [
                        {
                            name: '',
                            useAlias: false,
                            alias: '',
                            aliasOptions: ['Petrenko', 'Петренко']
                        },


                    ],

                }

            }
        },
        components: {
            Multiselect,
        },
        methods:{
            nextStep () {
                this.$emit('getData', this.stepData);
            },
            prevStep () {
                this.$emit('prevStep');
            },
            addAuthor(){
                this.stepData.authors.push({
                    name: '',
                    useAlias: false,
                    alias: '',
                    aliasOptions: []
                })
            },
            removeAuthor(i){
                this.stepData.authors.splice(i, 1);
            },

            useAlias (i) {
                this.stepData.authors[i].useAlias = !this.stepData.authors[i].useAlias;
            },
            showNewAuthor(n){
                this.otherAuthor = n;
            },
            getPerson() {
                axios.get(`/api/persons/${this.ssuAuthor}`).then(response => {
                    this.persons = response.data;
                    console.log(this.persons)
                });
            },
        },

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
