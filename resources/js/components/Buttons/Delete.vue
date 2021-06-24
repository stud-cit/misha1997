<template>
    <div>
        <button class="ml-2 delete" :disabled="disabled">{{ title }}</button>
    </div>
</template>
<script>
export default {
    props: {
        disabled: Boolean,
        title: {
          default: "Видалити"
        }
    }
}
</script>
<style lang="css" scoped>
    .delete {
        border-radius: 6px;
    }
</style>