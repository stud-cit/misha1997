<template>
  <div>
    <button type="button" class="btn btn-primary" @click="getData()">Отримати дані Scopus</button>
    <div v-for="(item, index) in data" :key="index">
      <!--  -->
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: []
    }
  },
  methods: {
    getData() {
      // 
    }
  }
}
</script>