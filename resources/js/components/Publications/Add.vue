<template>
    <div class="container">
        <h1 class="page-title">Додати нову публікацію</h1>
        <step1 v-if="currentStep==1" @getData="getStepData($event)"></step1>
        <step2 v-if="currentStep==2" @getData="getStepData($event)" @prevStep="prevStep"></step2>
        <step3 v-if="currentStep==3" @getData="getStepData($event)" @prevStep="prevStep"></step3>
        <step4 v-if="currentStep==4" @getData="getStepData($event)" @prevStep="prevStep"></step4>
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
                currentStep: 1,
                stepData: {},
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
                console.log(this.stepData)
                if(this.currentStep !== 4) {
                    this.currentStep++;
                }
            },
            prevStep() {
                if(this.currentStep !== 1){
                    this.currentStep--;
                }

            }
        }
    }
</script>

<style lang="scss" >


</style>
