<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Назва журналу *</label>
                <div class="input-container">
                    <input
                        :disabled="publicationData.default_name_magazine || publicationData.scopus_id"
                        class="item-value"
                        type="text"
                        v-model="publicationData.name_magazine"
                    >
                </div>
                <div class="error" v-if="$v.publicationData.name_magazine.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Рік видання *</label>
                <div class="input-container">
                    <select :disabled="publicationData.scopus_id" class="item-value" v-model="publicationData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>
                </div>
                <div class="error" v-if="$v.publicationData.year.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Том (номер) </label>
                <div class="input-container">
                    <input :disabled="publicationData.scopus_id" class="item-value" type="text" v-model="publicationData.number">
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки *</label>
                <div class="input-container">
                    <input :disabled="publicationData.scopus_id" class="item-value" type="text" v-model="publicationData.pages">
                </div>
                <div class="error" v-if="$v.publicationData.pages.$error">
                    Неправильно введені дані
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Країна видання *</label>
                <Country :data="publicationData"></Country>
                <div class="error" v-if="$v.publicationData.country.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">DOI </label>
                <div class="input-container">
                    <input placeholder="10.30888/2415-7538.2021-20-01-004" :disabled="publicationData.scopus_id" class="item-value" type="text" v-model="publicationData.doi">
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
    import Country from "../../Forms/Country";
    import CloseEditButton from "../../Buttons/CloseEdit";
    import years from '../../mixins/years';
    import {required, requiredIf} from "vuelidate/lib/validators";

    export default {
        mixins: [years],
        props: {
          publicationData: Object
        },
        components: {
            Country,
            CloseEditButton
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
                year: {
                    required
                },
                country: {
                    required
                },
            },
        },
        methods:{
            nextStep() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    swal.fire({
                        icon: 'error',
                        title: 'Не всі поля заповнено!'
                    })
                    return
                }
                this.$parent.$emit('getData', 4);
            },
            prevStep() {
                this.$parent.$emit('prevStep');
            },
            nameCountry({id, name}){
                return `${name}`
            },
        }
    }
</script>
