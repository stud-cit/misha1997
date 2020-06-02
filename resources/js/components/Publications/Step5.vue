<template>
    <div>
        <p class="step-subtitle">
            Крок 5 з 5. Назва та база для публікації
        </p>

        <div class="step-button-group">
            <button class="next active" @click="nextStep">Продовжити <span>&gt;</span></button>
            <button class="prev" @click="prevStep">На попередній крок</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Step5",
        data() {
            return {
                stepData:{

                }

            }
        },
        methods:{
            nextStep() {
                this.$emit('getData', this.stepData);
            },
            prevStep(){
                this.$emit('prevStep');
            }
        },
        created() {

        }
    }
</script>

<style lang="scss" scoped>


</style>
