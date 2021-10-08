<template>
  <b-container class="wrapper">
    <Back page="Архів"></Back>
    <b-row>
      <b-col cols="3">
        <FilterPublication @getPublications="setFilter"></FilterPublication>
      </b-col>
      <b-col cols="9">
        <b-row>
          <b-col cols="12">
            <button class="button white-green" :disabled="selectPublications.length == 0" @click="restorePublications(selectPublications)">Відновити</button>
          </b-col>
          <b-col cols="6" class="py-2 count">
            Всього публікацій: {{ pagination.countData }}
          </b-col>
          <b-col cols="6">
            <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
          </b-col>
        </b-row>
        <b-overlay :show="loading" rounded="sm">
          <div>
            <table>
              <tr class="header">
                <th>
                  №
                </th>
                <th width="125px">
                  Вид <br> публікації
                </th>
                <th class="sorted" @click='sort("title")'>
                  Назва 
                  <b-icon icon="arrow-up" v-show="sortBy == 'title' && sortOrder == -1"></b-icon>
                  <b-icon icon="arrow-down" v-show="sortBy == 'title' && sortOrder == 1"></b-icon>
                  <b-icon icon="arrow-down-up" class="opacity-50" v-show="sortBy != 'title'"></b-icon>
                </th>
                <th>ПІБ автора/співавторів</th>
                <th width="130px" class="sorted" @click='sort("created_at")'>
                  Дата <br> занесення 
                  <b-icon icon="arrow-up" v-show="sortBy == 'created_at' && sortOrder == -1"></b-icon>
                  <b-icon icon="arrow-down" v-show="sortBy == 'created_at' && sortOrder == 1"></b-icon> 
                  <b-icon icon="arrow-down-up" class="opacity-50" v-show="sortBy != 'created_at'"></b-icon>
                <th width="90px"></th>
              </tr>
              <tr v-for="(item, index) in publications" :key="item.id" :class="(item.scopus_id && item.verification == 0) ? 'no-verification' : ''">
                <td>
                  {{ pagination.firstItem + index }}
                </td>
                <td v-html="item.publication_type.title"></td>
                <td>
                  <a :href="'/publication/'+item.id" :id="item.scopus_id && item.verification == 0 ? 'tooltip-target-public-'+item.id : ''">
                    {{ item.title }}
                    <b-tooltip v-if="item.scopus_id && item.verification == 0" :target="'tooltip-target-public-'+item.id" triggers="hover">
                      Публікацію не верифіковано
                    </b-tooltip>
                  </a>
                </td>
                <td>
                  <a 
                    v-for="authorItem in item.all_authors" 
                    :key="authorItem.id" 
                    :href="'/user/'+authorItem.author.id" 
                    class="mr-1"
                    :id="`popover-${authorItem.id}`"
                  >
                    {{ authorItem.author.name }}
                    <b-popover
                      :target="`popover-${authorItem.id}`"
                      placement="auto"
                      triggers="hover focus"
                      ref="popover"
                    >
                      <PopoverUser :authorItem="authorItem"></PopoverUser>
                    </b-popover>
                  </a>
                </td>
                <td>
                  {{ item.created_at }}
                </td>
                <td>
                  <div class="active">
                  <a :href="'/publication/edit/'+item.id"><b-icon icon="pencil-fill"></b-icon></a>
                  <b-icon class="cursor" icon="arrow-up-square-fill" @click="restorePublications([item.id])"></b-icon>
                  <b-form-checkbox inline :value="item.id" v-model="selectPublications"></b-form-checkbox>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </b-overlay>
        <b-row class="mt-3">
          <b-col cols="6" class="count">
            Всього публікацій: {{ pagination.countData }}
          </b-col>
          <b-col cols="6">
            <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
import Back from "../components/Back.vue";
import Pagination from "../components/Pagination.vue";
import FilterPublication from "../components/FilterPublication.vue";
import PopoverUser from "../components/PopoverUser.vue";

export default {
  components: {
    Back,
    Pagination,
    FilterPublication,
    PopoverUser
  },
  data() {
    return {
      loading: true,
      selectPublications: [],
      pagination: {
          currentPage: 1,
          countData: 0,
          perPage: 10
      },
      sortBy: "created_at",
      sortOrder: -1,
      publications: [],
      filter: {}
    }
  },
  created() {
    this.getPublications(this.pagination.currentPage);
  },
  methods: {
    setFilter(currentPage, filter) {
      this.filter = filter;
      this.getPublications(currentPage);
    },
  	sort(sortBy) {
    	if(this.sortBy === sortBy) {
      	this.sortOrder = -this.sortOrder;
      } else {
      	this.sortBy = sortBy
      }
      this.getPublications(1);
    },
    scrollHeader(currentPage) {
      document.location = '#wrapper';
      this.getPublications(currentPage);
    },
    getPublications(page) {
      this.loading = true;
      var params = Object.assign(this.filter, {
        page,
        size: this.pagination.perPage,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
        status_id: 2,
      });
      axios.get('/api/publications', { params }).then(response => {
        this.pagination.countData = response.data.count;
        this.pagination.firstItem = response.data.firstItem;
        this.publications = response.data.publications.data;
        this.loading = false;
      })
    },
    restorePublications(publications) {
        swal.fire({
            title: 'Бажаєте відновити?',
            text: "Публікації будуть повернути до списку публікацій",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Відновити',
            cancelButtonText: 'Відміна',
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/api/restore-publications', {
                    publications
                })
                .then(() => {
                    this.selectPublications = [];
                    this.getPublications(this.pagination.currentPage);
                    swal.fire("Публікації видалено");
                });
            }
        })
    },
  }
}
</script>
<style lang="css" scoped>

</style>