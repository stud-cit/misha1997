<template>
  <div class="pagination-nav">
    <select class="select" v-model="pagination.perPage" @change="$emit('scrollHeader', 1)">
      <option :value="10">10</option>
      <option :value="50">50</option>
      <option :value="100">100</option>
      <option :value="250">250</option>
      <option :value="500">500</option>
      <option :value="pagination.countData">Всі</option>
    </select>
    <b-pagination
      v-model="pagination.currentPage"
      :total-rows="pagination.countData"
      :per-page="pagination.perPage"
      @click.native="$emit('scrollHeader', pagination.currentPage)"
      size="sm"
      align="right"
    ></b-pagination>
  </div>
</template>
<script>
export default {
  props: {
    pagination: Object
  }
}
</script>
<style lang="css" scoped>
  .select {
    width: 80px;
    border: 1px solid #dee2e6;
    margin-right: 10px;
    padding: 4px 12px;
    border-radius: 4px;
    height: auto;
  }
  .pagination-nav {
    display: flex;
    justify-content: flex-end;
  }
</style>