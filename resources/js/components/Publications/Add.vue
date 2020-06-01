<template>

    <div class="container">
        <h1 class="page-title">Додати нову публікацію</h1>
        <step1></step1>
        <step2></step2>
    </div>
</template>

<script>
    import Step1 from "./Step1";
    import Step2 from "./Step2";
    export default {
        data() {
            return {
                currentStep: 1,
            };
        },
        components: {
            Step1,
            Step2
        },

        methods: {

        },

    }
</script>

<style lang="scss" scoped>


</style>
