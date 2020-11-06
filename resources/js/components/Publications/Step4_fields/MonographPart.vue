<template>
    <div>
        <div class="step-content">
            <div class="form-group">
                <label class="item-title">Рік видання *</label>
                <div class="input-container">
                    <select class="item-value" v-model="publicationData.year">
                        <option v-for="(year, index) in years" :key="index" :value="year">{{ year }}</option>
                    </select>
                </div>
                <div class="error" v-if="$v.publicationData.year.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Назва монографії </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.name_monograph">
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">За редакцією (у родовому відмінку)</label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.by_editing">
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
                <div class="error" v-if="$v.publicationData.country.$error">
                    Поле обов'язкове для заповнення
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Місто видання </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.city">
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Назва редакції </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.editor_name">
                </div>
            </div>
            <div class="form-group">
                <label class="item-title">Сторінки (з-по) * </label>
                <div class="input-container">
                    <input class="item-value" type="text" v-model="publicationData.pages">
                </div>
                <div class="error" v-if="$v.publicationData.pages.$error">
                    Поле обов'язкове для заповнення
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
            <button class="next active" @click="nextStep">Продовжити</button>
            <close-edit-button v-if="$route.name == 'publications-edit'"></close-edit-button>
        </div>
    </div>
</template>

<script>
    import CloseEditButton from "../../Buttons/CloseEdit";
    import years from '../../mixins/years';
    import country from '../../mixins/country';
    import {required} from "vuelidate/lib/validators";

    export default {
        mixins: [years, country],
        props: {
            publicationData: Object
        },
        components: {
            CloseEditButton
        },
        validations: {
            publicationData: {
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
        methods: {
            nextStep() {
                this.$v.$touch();
                if (this.$v.$invalid) {
                    swal("Не всі поля заповнено!", {
                        icon: "error",
                    });
                    return
                }
                this.$parent.$emit('getData');
            },
            prevStep(){
                this.$parent.$emit('prevStep');
            }
        }
    }
</script>