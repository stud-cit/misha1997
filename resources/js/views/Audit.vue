<template>
  <b-container class="wrapper">
    <Back page="Аудит"></Back>
    <b-row class="mb-3">
      <b-col cols="12">
        <b-row class="mb-3">
          <b-col md="10" lg="10" xl="10" xs="8">
            <input type="text" class="input mb-2" v-model="search">
          </b-col>
          <b-col md="2" lg="2" xl="2" xs="4">
            <button class="button blue block mb-2" @click="getData(1)">Пошук</button>
          </b-col>
        </b-row>
      </b-col>
      <b-col cols="12">
        <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded="sm">
      <table>
        <tr class="header">
          <th width="50px">№</th>
          <th>Зміни</th>
          <th width="150px">Дата</th>

        <tr v-for="(item, index) in data" :key="item.id">
          <td>
            {{ pagination.firstItem + index }}
          </td>
          <td v-html="item.text"></td>
          <td>
            {{ item.created_at }}
          </td>
        </tr>
      </table>
    </b-overlay>
    <b-row class="mt-3">
      <b-col cols="12">
        <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
      </b-col>
    </b-row>

  </b-container>
</template>
<script>
import Back from "../components/Back.vue";
import Pagination from "../components/Pagination.vue";

export default {
  components: {
    Back,
    Pagination
  },
  data() {
    return {
      loading: false,
      pagination: {
          currentPage: 1,
          countData: 0,
          perPage: 10
      },
      search: "",
      data: []
    }
  },
  created() {
    this.getData(this.pagination.currentPage);
  },
  methods: {
    scrollHeader(currentPage) {
      document.location = '#wrapper';
      this.getData(currentPage);
    },
    getData(page) {
      this.loading = true;
      axios.get('/api/audit', {
        params: {
        page,
        size: this.pagination.perPage,
        search: this.search
      }}).then(response => {
        this.pagination.countData = response.data.count;
        this.pagination.firstItem = response.data.firstItem;
        this.data = response.data.data.data;
        this.statistic = response.data.statistic;
        this.loading = false;
      })
    }
  }
}
</script>
<style lang="css" scoped>
  td, th {
    text-align: left;
  }
  button.button {
    height: 34px !important;
  }
</style>