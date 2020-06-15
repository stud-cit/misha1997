<template>

    <div class="container">
        <h1 class="page-title">Додати нову публікацію</h1>
        <step1 v-show="currentStep==2" @getData="getStepData($event)" ></step1>
        <step2  v-show="currentStep==1" @getData="getStepData($event)" @prevStep="prevStep"></step2>
        <step3  v-show="currentStep==3" @getData="getStepData($event)" @prevStep="prevStep"></step3>
        <step4  v-show="currentStep==4" @getData="getStepData($event)" @prevStep="prevStep"></step4>
        <step5  v-show="currentStep==5" @getData="getStepData($event)" @prevStep="prevStep"></step5>

    </div>
</template>

<script>
    import Step1 from "./Step1";
    import Step2 from "./Step2";
    import Step3 from "./Step3";
    import Step4 from "./Step4";
    import Step5 from "./Step5";

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
            Step5,
        },

        computed: {

        },

        methods: {
            getStepData(event){
                this.stepData = Object.assign(this.stepData, event);
                console.log(1, event);
                if(this.currentStep !== 5) {
                    this.currentStep++;
                }
            },
            prevStep() {

                if(this.currentStep !== 1){
                    this.currentStep--;
                }

            }
        },

    }
</script>

<style lang="scss" >


</style>
