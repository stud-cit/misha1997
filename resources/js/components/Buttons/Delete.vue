<template>
    <div>
        <button class="ml-2 delete" :disabled="disabled">Видалити</button>
    </div>
</template>
<script>
export default {
    props: {
        disabled: Boolean
    }
}
</script>
