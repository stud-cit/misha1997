<template>
  <b-container class="wrapper">
    <Back page="Повідомлення"></Back>
    <b-row>
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
      <b-col md="6" lg="6" xl="6" xs="12" class="py-2 count">
        <button class="button grey" @click="deleteItems()">Видалити всі повідомлення</button>
      </b-col>
      <b-col md="6" lg="6" xl="6" xs="12">
        <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
      </b-col>
    </b-row>
    <b-overlay :show="loading" rounded="sm">
      <table class="mt-2">
        <tr class="header">
          <th width="50px">№</th>
          <th>Повідомлення</th>
          <th width="150px">Дата</th>
          <th width="70px"></th>
        </tr>
        <tr v-for="(item, index) in data" :key="index">
          <td>
            {{ pagination.firstItem + index }}
          </td>
          <td v-html="item.text"></td>
          <td>
            {{ item.created_at }}
          </td>
          <td>
            <div class="active">
              <b-icon v-if="item.status" icon="envelope" class="cursor"></b-icon>
              <b-icon v-else icon="envelope-fill" class="cursor" @click="watchedItem(item)"></b-icon>
              <b-icon icon="trash-fill" class="cursor" @click="deleteItem(item)"></b-icon>
            </div>
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
import { mapMutations } from 'vuex';
export default {
  components: {
    Back,
    Pagination
  },
  data() {
    return {
      loading: true,
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
    ...mapMutations([
        "updateNotifications"
    ]),
    scrollHeader(currentPage) {
      document.location = '#wrapper';
      this.getData(currentPage);
    },
    deleteItem(item) {
        axios.delete('/api/notifications/'+item.id+'/'+this.$store.getters.authUser.id)
        .then((response) => {
            this.data.splice(this.data.indexOf(item), 1);
            this.updateNotifications(response.data.count);
        });
    },
    deleteItems() {
        axios.delete('/api/notifications/'+this.$store.getters.authUser.id)
        .then(() => {
            this.data = [];
            this.updateNotifications(0);
        });
    },
    watchedItem(item) {
        axios.post('/api/notifications/'+item.id+'/'+this.$store.getters.authUser.id, {
            status: 1
        }).then((response) => {
            item.status = 1;
            this.updateNotifications(response.data.count);
        });
    },
    getData(page) {
      this.loading = true;
      axios.get('/api/notifications', { params: {
        page,
        search: this.search
      }}).then(response => {
        this.pagination.countData = response.data.count;
        this.pagination.firstItem = response.data.firstItem;
        this.data = response.data.data.data;
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