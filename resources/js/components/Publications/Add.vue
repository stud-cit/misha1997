<template>
    <div class="container page-content">
        <h1 class="page-title">Додати нову публікацію</h1>
        <keep-alive>
        <step1 v-if="currentStep==1" @getData="getStepData($event)"></step1>
        <step2 v-if="currentStep==2" @getData="getStepData($event)" @prevStep="prevStep"></step2>
        <step3 v-if="currentStep==3" @getData="getStepData($event)" @prevStep="prevStep" ></step3>
        <step4 v-if="currentStep==4" @getData="getStepData($event)" @prevStep="prevStep" :publicationType="stepData.publication_type.type"></step4>
        </keep-alive>
    </div>
</template>

<script>
    import Step1 from "./Step1";
    import Step2 from "./Step2";
    import Step3 from "./Step3";
    import Step4 from "./Step4";
    export default {
        data() {
            return {
                isScopus: false,
                currentStep: 1,
                stepData: {
                    publication_type:{
                        type: ''
                    }
                },
            };
        },

        components: {
            Step1,
            Step2,
            Step3,
            Step4,
        },

        computed: {

        },

        methods: {
            getStepData(event){
                this.stepData = Object.assign(this.stepData, event);

                if(this.currentStep !== 4) {
                    // этот код скрывает 3 шаг

                    if(!this.isScopus && this.currentStep == 2){
                        this.currentStep+=2;
                    }
                    else {
                        this.currentStep++;
                    }
                    //

                    // this.currentStep++;
                } else {
                    axios.post('/api/publication', this.stepData)
                    .then((response) => {

                        swal("Публікацію успішно додано!", {
                            icon: "success",
                        });
                        this.$router.push({path: '/publications'});
                    })
                    .catch(() => {
                        swal({
                            icon: "error",
                            title: 'Помилка'
                        });
                    });
                }
            },
            prevStep() {
                if(this.currentStep !== 1){
                    // этот код скрывает 3 шаг

                    if(!this.isScopus && this.currentStep == 4){
                        this.currentStep-=2;
                    }
                    else {
                        this.currentStep--;
                    }
                    // this.currentStep--;
                }

            }
        }
    }
</script>

<style lang="scss" >


</style>
