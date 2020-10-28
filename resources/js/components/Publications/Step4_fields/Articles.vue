<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Назва журналу *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.name_magazine">
                </div>
                <div class="error" v-if="$v.publicationData.name_magazine.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Рік видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="publicationData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Номер (том) </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.number">
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки *</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.pages">
                </div>
                <div class="error" v-if="$v.publicationData.pages.$error">
                    Неправильно введені дані
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="publicationData.country">
                        <option
                            v-for="(item, index) in country"
                            :key="index"
                            :value="item.name"
                        >{{item.name}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">DOI </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.doi">
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
            }
        },
        created() {
            this.getCountry();
        },
        props: {
          publicationData: Object
        },
        validations: {
            publicationData: {
                name_magazine: {
                    required
                },
                pages: {
                    required,
                    validFormat: val => /^([^a-za-zа-яіїєё]+)$/.test(val), 
                },
                country: {
                    required
                }
            },
        },
        computed: {
			years() {
				const year = new Date().getFullYear();
				return Array.from({length: year - 2000}, (value, index) => 2001 + index);
            },
        },
        methods:{
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
                this.$parent.$emit('getData', this.publicationData);
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            }
        }
    }
</script>