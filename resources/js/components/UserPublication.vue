<template>
  <div>
    <b-row>
      <b-col md="3" lg="3" xl="3" xs="12">
        <FilterPublication @getPublications="setFilter"></FilterPublication>
      </b-col>
      <b-col md="9" lg="9" xl="9" xs="12">
        <b-row>
          <b-col cols="12">
            <b-overlay
              :show="loadingExport"
              rounded
              opacity="0.6"
              spinner-small
              spinner-variant="primary"
              class="d-inline-block"
            >
              <button @click="getPublicationsExport()" class="button blue mr-1"><b-icon class="mr-1" icon="file-earmark-word-fill"></b-icon> Експорт Word</button>
            </b-overlay>
            <b-overlay
              :show="loadingImport"
              rounded
              opacity="0.6"
              spinner-small
              spinner-variant="primary"
              class="d-inline-block"
              id="tooltip-target-1"
              v-if="($route.name == 'profile' || $route.name == 'my-publications') && access == 'open'"
            >
              <button class="button white-blue" :disabled="!authUser.scopus_id" @click="scopusExport">Імпортувати з бази Scopus</button>
            </b-overlay>
            <b-tooltip v-if="!authUser.scopus_id" target="tooltip-target-1" triggers="hover">
              В профілі відсутній <br> Scopus ID
            </b-tooltip>
            <button class="button grey" v-show="selectPublications.length > 0" @click="deletePublications(selectPublications)">Видалити</button>
          </b-col>
          <b-col cols="6" class="py-2 count">
            Всього публікацій: {{ pagination.countData }}
          </b-col>
          <b-col cols="6" class="py-2">
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
                <th width="90px" v-if="checkAccess"></th>
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
                <td v-if="checkAccess">
                  <div class="active">
                    <a :href="'/publication/edit/'+item.id"><b-icon icon="pencil-fill"></b-icon></a>
                    <b-icon class="cursor" @click="deletePublications([item.id])" icon="trash-fill"></b-icon>
                    <b-form-checkbox :value="item.id" inline v-model="selectPublications"></b-form-checkbox>
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
    <!-- Export Table -->
    <ExportWord
      :articleProfessional="articleProfessional"
      :otherArticles="otherArticles"
      :articleReport="articleReport"
      :monograph="monograph"
      :books="books"
      :patents="patents"
      :certificates="certificates"
      :methodicals="methodicals"
      :electronics="electronics"
      :thesis="thesis"
      :parts="parts"
    ></ExportWord>
  </div>
</template>
<script>
import Pagination from "../components/Pagination.vue";
import FilterPublication from "../components/FilterPublication.vue";
import PopoverUser from "../components/PopoverUser.vue";
import ExportWord from "../components/ExportWord.vue";
export default {
  components: {
    Pagination,
    FilterPublication,
    PopoverUser,
    ExportWord
  },
  data() {
    return {
      loading: true,
      loadingImport: false,
      loadingExport: false,
      selectPublications: [],
      pagination: {
        currentPage: 1,
        countData: 0,
        perPage: 10
      },
      sortBy: "created_at",
      sortOrder: -1,
      publications: [],
      filter: {},
      // Export
      articleProfessional: [],
      otherArticles: [],
      articleReport: [],
      monograph: [],
      books: [],
      patents: [],
      certificates: [],
      methodicals: [],
      electronics: [],
      thesis: [],
      parts: [],
    }
  },
  computed: {
      authUser() {
          return this.$store.getters.authUser
      },
      access() {
          return this.$store.getters.accessMode
      },
      checkAccess() {
          if(this.access == 'open') {
              return true;
          } else if(this.authUser.roles_id == 4 || (this.access == 'open' && this.authUser.roles_id != 1)) {
              return true;
          } else {
              return false;
          }
      },
  },
  created() {
    if(this.$store.getters.getFilterPublications) {
      this.filter = this.$store.getters.getFilterPublications;
    }
    this.getPublications(this.pagination.currentPage);
  },
  watch: {
    $route(to, from) {
      this.getPublications(1);
    }
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
        status_id: 1
      });
      axios.get('/api/publications/' + (this.$route.params.id ? this.$route.params.id : this.authUser.id), { params }).then(response => {
        this.pagination.countData = response.data.count;
        this.pagination.firstItem = response.data.firstItem;
        this.publications = response.data.publications.data;
        this.loading = false;
      })
    },
    getPublicationsExport() {
      this.loadingExport = true;
      var params = Object.assign(this.filter, {
        page: 1,
        size: this.pagination.countData,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
        status_id: 1
      });
      axios.get('/api/publications/' + (this.$route.params.id ? this.$route.params.id : this.authUser.id), { params }).then(response => {
        this.articleReport = [];
        this.articleProfessional = [];
        this.otherArticles = [];
        this.monograph = [];
        this.books = [];
        this.patents = [];
        this.methodicals = [];
        this.thesis = [];
        this.parts = [];
        response.data.publications.data.map(item => {
          if(item.publication_type_id == 2) {
              this.articleReport.push(item);
          }
          if(item.publication_type_id == 1) {
              this.articleProfessional.push(item);
          }
          if(item.publication_type_id == 3) {
              this.otherArticles.push(item);
          }
          if(item.publication_type_id == 6) {
              this.monograph.push(item);
          }
          if(item.publication_type_id == 5 || item.publication_type_id == 4) {
              this.books.push(item);
          }
          if(item.publication_type_id == 10) {
              this.patents.push(item);
          }
          if(item.publication_type_id == 11) {
              this.certificates.push(item);
          }
          if(item.publication_type_id == 12) {
              this.methodicals.push(item);
          }
          if(item.publication_type_id == 13) {
              this.methodicals.push(item);
          }
          if(item.publication_type_id == 9) {
              this.thesis.push(item);
          }
          if(item.publication_type_id == 7 || item.publication_type_id == 8) {
              this.parts.push(item);
          }
        })
        this.loadingExport = false;
      }).then(() => {
          this.exportPublications();
      }).catch(() => {
          this.loadingExport = false;
      })
    },
    exportPublications() {
        var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
            "xmlns:w='urn:schemas-microsoft-com:office:word' "+
            "xmlns='http://www.w3.org/TR/REC-html40'>"+
            "<head><meta charset='utf-8'><title>Список наукових публікацій</title><style></style></head><body>";
        var footer = "</body></html>";
        var sourceHTML = header+document.getElementById('export').innerHTML+footer;

        var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
        var fileDownload = document.createElement("a");
        document.body.appendChild(fileDownload);
        fileDownload.href = source;
        fileDownload.download = 'Список наукових публікацій.doc';
        fileDownload.click();
        document.body.removeChild(fileDownload);
    },
    scopusExport() {
      this.loadingImport = true;
      axios.get('/api/publications-scopus-user').then(() => {
        this.getPublications(1);
        swal.fire("Публікації успішно завантажено");
        this.loadingImport = false;
      }).catch(() => {
        swal.fire("Помилка");
        this.loadingImport = false;
      })
    },
    deletePublications(publications) {
        swal.fire({
            title: 'Бажаєте видалити?',
            text: "Після видалення ви не зможете відновити дані!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Видалити',
            cancelButtonText: 'Відміна',
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/api/delete-publications', {
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