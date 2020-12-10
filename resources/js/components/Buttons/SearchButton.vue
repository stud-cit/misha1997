<template>
    <button type="button" class="export-button" :disabled="disabled">
        <span
            class="spinner-border spinner-border-sm"
            role="status"
            aria-hidden="true"
            v-if="loading"
        ></span>
        <span class="sr-only" v-if="loading">Loading...</span>
        {{ title }}
    </button>
</template>
<script>
export default {
    props: {
        disabled: Boolean,
        loading: Boolean,
        title: String
    }
}
</script>
<style lang="css" scoped>
    .export-button {
        display: inline-block;
    }
    span.spinner-border {
        width: 19px;
        height: 19px;
    }
</style>