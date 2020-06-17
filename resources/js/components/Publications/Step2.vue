<template>
    <div>
        <p class="step-subtitle">
            Крок 2 з 5. Додання авторів
        </p>
        <div class=" step-content">
            <div class="step-item">
                <p class="item-title">Додати автора в базу данних сайту (якщо ви не знайшли потрібного вам автора) :</p>
                <div class="button-group">
                    <button class="small-box btn-blue">
                        Додати автора з СумДУ
                    </button>
                    <button class="small-box btn-blue">
                        Додати іншого автора
                    </button>
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

                <button class="small-box btn-blue  mb-4">
                    Додати автора
                </button>

                <div class="authors" v-for="(item, i) in stepData.authors" :key="i">
                    <multiselect v-if="!item.useAlias"
                        v-model="item.name"
                        :options="authorsData"
                        :placeholder="'Пошук в базі данних сайту'"
                        :selectLabel="'Натисніть для вибору'"
                        :selectedLabel="'Вибрано'"
                        :deselectLabel="'Натисніть для видалення'"
                    >
                        <span slot="noResult">По даному запиту немає результатів</span>
                    </multiselect>
                    <multiselect v-else
                        v-model="item.alias"
                        :options="item.aliasOptions"
                        :placeholder="'Виберіть псевдонім'"
                        :selectLabel="'Натисніть для вибору'"
                        :selectedLabel="'Вибрано'"
                        :deselectLabel="'Натисніть для видалення'"
                    >
                        <span slot="noResult">По даному запиту немає результатів</span>
                    </multiselect>
                    <button class="authors-btn" @click="useAlias(i)"> {{ item.useAlias ? 'Використати ПІБ' : 'Використати псевдонім'}}</button>
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
                value: '',
                authorsData: ['Петренко', 'Іванов', 'Ivanenko', 'Петросян'],

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
                        {
                            name: '',
                            useAlias: false,
                            alias: '',
                            aliasOptions: ['Ivanov', 'Иванов']
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

            useAlias (i) {
               this.stepData.authors[i].useAlias = !this.stepData.authors[i].useAlias;
            }
        },

    }
</script>

<style lang="scss" scoped>
    .step-item{
        .item-title{
            padding-left: 0;
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

    input{
        display: none;
    }
    input:checked +label{
        background: #18A0FB;
        border: 1px solid #18A0FB;
        color: #fff;
    }



</style>
