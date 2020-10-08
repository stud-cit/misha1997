<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Назва журналу *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.name_magazine">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.name_magazine.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Рік видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
            </div>

            <div class="form-group">
                <label class="item-title">Номер (том) </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.number">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.number.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.pages">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.pages.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="stepData.country">
                        <option
                            v-for="(item, index) in country"
                            :key="index"
                            :value="item.name"
                        >{{item.name}}</option>
                    </select>
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>

            </div>

            <div class="form-group">
                <label class="item-title">DOI </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="stepData.doi">
                    <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                </div>
                <div class="error" v-if="$v.stepData.doi.$error">
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
    import {required, requiredIf} from "vuelidate/lib/validators";

    export default {
        data() {
            return {
                country: [],
                stepData: {
                    name_magazine: '',
                    year: new Date().getFullYear(),
                    number: '',
                    pages: '',
                    country: 'Україна',
                    doi: ''
                }
            }
        },
        created() {
            this.getCountry();
            this.checkPublicationData();
        },
        props: {
          publicationData: Object
        },
        validations: {

            stepData: {

                name_magazine: {
                    required
                },
                number: {
                    // required
                },
                pages: {
                    required
                },
                doi: {
                    // required
                },


            },



        },
        computed: {
			years() {
				const year = new Date().getFullYear();
				return Array.from({length: year - 2000}, (value, index) => 2001 + index);
            },
        },
        methods:{
            checkPublicationData() {

                if(this.publicationData && this.$route.name == 'publications-edit'){

                    for( let key in this.stepData){
                        this.stepData[key] = this.publicationData[key];
                    }

                }

            },
            getCountry() {
                axios.get('/api/country').then(response => {
                    this.country = response.data;
                })
            },
            nextStep() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                this.$parent.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            }
        }
    }
</script>

<style lang="scss" scoped>



</style>
