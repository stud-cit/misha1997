<template>
    <div>

        <p class="step-subtitle">
            Крок 1 з 4.
        </p>
        <div class="step-content">

            <form class="form-block">
                <div class="form-group">
                    <label >Назва публікації</label>
                    <div class="input-container">
                        <input type="text"  v-model="stepData.title">

                        <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-lg-4">
                        <label >БД Scopus\WoS</label>
                        <div class="input-container">
                            <select  v-model="stepData.science_type_id">
                                <option value=""></option>
                                <option value="1">Scopus</option>
                                <option value="2">Wos</option>
                                <option value="3">Scopus та Wos</option>
                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-8">
                        <label >Вид публікації</label>
                        <div class="input-container">
                            <select  v-model="stepData.publication_type" v-if="stepData.science_type_id">
                                <option value=""></option>
                                <option v-for="(item, i) in publicationTypes" :key="i" v-if="item.scopus_wos" :value="item">{{item.title }}</option>


                            </select>
                            <select  v-model="stepData.publication_type" v-else>
                                <option value=""></option>
                                <option v-for="(item, i) in publicationTypes" :key="i" :value="item">{{item.title  }}</option>


                            </select>
                            <div class="hint" ><span>Прізвище, ім’я, по-батькові:</span></div>
                        </div>
                    </div>

                </div>




            </form>

        </div>

        <div class="step-button-group">
            <button class="next" @click="nextStep">Продовжити </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step1",
        data() {
            return {
                publicationTypes: [],
                prevVal: '',
                stepData: {
                    title: '',
                    science_type_id: null,
                    publication_type: null
                }
            }
        },
        created() {
            this.getTypePublications();
        },
        methods: {
            getTypePublications() {
                axios.get(`/api/type-publications`).then(response => {
                    this.publicationTypes = response.data;
                })
            },
            nextStep() {

                // check scopus
                if(this.stepData.science_type_id){
                    this.$parent.isScopus = true;
                }
                //
                this.$emit('getData', this.stepData);
            },

        }
    }
</script>

<style lang="scss" scoped>


</style>
