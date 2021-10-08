<template>
  <div class="back">
      <span @click="$router.back()">
        <b-icon class="ml-1" icon="arrow-left"></b-icon> Повернутись назад
      </span> <span v-show="page">/</span> {{ page }}
  </div>
</template>
<script>
export default {
  props: {
    page: String
  }
}
</script>
<style lang="css" scoped>
  .back {
    margin-bottom: 20px;
  }
  .back span {
    font-size: 18px;
    line-height: 21px;
    color: #000;
    cursor: pointer;
  }
</style>