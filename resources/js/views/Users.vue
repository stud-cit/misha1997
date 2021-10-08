<template>
  <b-container class="wrapper">
    <Back page="Автори"></Back>
    <b-row>
      <b-col md="3" lg="3" xl="3" xs="12">
        <FilterUsers @getUsers="setFilter"></FilterUsers>
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
              <button @click="getUserExport()" class="button green mr-1 mb-2"><b-icon class="mr-1" icon="file-earmark-excel-fill"></b-icon> Експорт Excel</button>
            </b-overlay>
            <button class="button grey mb-2" v-show="selectUsers.length > 0" @click="deleteUsers(selectUsers)">Видалити</button>
          </b-col>
          <b-col cols="6" class="py-2 count">
            Всього користувачів: {{ pagination.countData }}
          </b-col>
          <b-col cols="6">
            <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
          </b-col>
        </b-row>
        <b-overlay :show="loading" rounded="sm">
          <div style="overflow-x: auto">
            <table>
              <tr class="header">
                <th>№</th>
                <th class="sorted" @click='sort("name")'>
                  ПІБ автора 
                    <b-icon icon="arrow-up" v-show="sortBy == 'name' && sortOrder == -1"></b-icon>
                    <b-icon icon="arrow-down" v-show="sortBy == 'name' && sortOrder == 1"></b-icon>
                    <b-icon icon="arrow-down-up" class="opacity-50" v-show="sortBy != 'name'"></b-icon> 
                </th>
                <th>Посада/<br>місце роботи</th>
                <th>Інститут/факультет</th>
                <th>Кафедра/підрозділ</th>
                <th class="sorted" width="100px" @click='sort("h_index")'>
                  Індекс Гірша WoS <b-icon icon="arrow-up" v-show="sortBy == 'h_index' && sortOrder == -1"></b-icon><b-icon icon="arrow-down" v-show="sortBy == 'h_index' && sortOrder == 1"></b-icon><b-icon icon="arrow-down-up" class="opacity-50" v-show="sortBy != 'h_index'"></b-icon> 
                </th>
                <th class="sorted" width="100px" @click='sort("scopus_autor_id")'>
                  Індекс Гірша Scopus <b-icon icon="arrow-up" v-if="sortBy == 'scopus_autor_id' && sortOrder == -1"></b-icon><b-icon icon="arrow-down" v-if="sortBy == 'scopus_autor_id' && sortOrder == 1"></b-icon><b-icon icon="arrow-down-up" class="opacity-50" v-if="sortBy != 'scopus_autor_id'"></b-icon>
                </th>
                <th width="90px"></th>
              </tr>
              <tr v-for="(item, index) in users" :key="item.id">
                <td>{{ pagination.firstItem + index }}</td>
                <td>
                  <a :href="'/user/'+item.id">{{ item.name }}</a>
                </td>
                <td>
                  {{ item.position }}
                </td>
                <td>
                  {{ item.faculty }}
                </td>
                <td>
                  {{ item.department }}
                </td>
                <td>
                  {{ item.h_index }}
                </td>
                <td>
                  {{ item.scopus_autor_id }}
                </td>
                <td>
                  <div class="active">
                  <a :href="'/user/'+item.id"><b-icon icon="pencil-fill"></b-icon></a>
                  <b-icon icon="trash-fill" @click="deleteUsers([item.id])" class="cursor"></b-icon>
                  <b-form-checkbox :value="item.id" inline v-model="selectUsers"></b-form-checkbox>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          <table class="statistic mt-3" v-show="pagination.countData > 0">
            <tr>
              <td>Індекс Гірша Scopus</td>
              <td>{{ scopusAutorId }}</td>
            </tr>
            <tr>
              <td>Індекс Гірша WoS</td>
              <td>{{ hIndex }}</td>
            </tr>
            <tr>
              <td>5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</td>
              <td>{{ fivePublications }}</td>
            </tr>
          </table>
        </b-overlay>
        <b-row class="mt-3">
          <b-col cols="6" class="count">
            Всього користувачів: {{ pagination.countData }}
          </b-col>
          <b-col cols="6">
            <Pagination :pagination="pagination" @scrollHeader="scrollHeader"></Pagination>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <table id="exportUsers" v-show="false">
      <tr>
        <th>ID</th>
        <th>ПІБ</th>
        <th>Вік</th>
        <th>Посада</th>
        <th>Академічна група</th>
        <th>Факультет/інститут</th>
        <th>Кафедра</th>
        <th>Країна</th>
        <th>Індекс Гірша БД WoS</th>
        <th>Індекс Гірша БД Scopus</th>
        <th>Research ID</th>
        <th>ORCID</th>
        <th>5 або більше публікацій у періодичних виданнях в Scopus та/або WoS</th>
      </tr>
      <tr v-for="(item, i) in dataExport" :key="i">
        <td>{{i+1}}</td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>{{item.position}}</td>
        <td>'{{item.academic_code}}'</td>
        <td>{{item.faculty}}</td>
        <td>{{item.department}}</td>
        <td>{{item.country}}</td>
        <td>{{item.h_index}}</td>
        <td>{{item.scopus_autor_id}}</td>
        <td>{{item.scopus_researcher_id}}</td>
        <td>{{item.orcid}}</td>
        <td>{{item.five_publications ? "Так" : "Ні"}}</td>
      </tr>
    </table>
  </b-container>
</template>
<script>
import XLSX from 'xlsx';

import Back from "../components/Back.vue";
import Pagination from "../components/Pagination.vue";
import FilterUsers from "../components/FilterUsers.vue";

export default {
  components: {
    Back,
    Pagination,
    FilterUsers
  },
  data() {
    return {
      scopusAutorId: 0,
      hIndex: 0,
      fivePublications: 0,
      loading: true,
      loadingExport: false,
      selectUsers: [],
      pagination: {
          currentPage: 1,
          countData: 0,
          perPage: 10
      },
      sortBy: "created_at",
      sortOrder: -1,
      users: [],
      filter: {},
      dataExport: []
    }
  },
  created() {
    this.getUsers(this.pagination.currentPage);
  },
  methods: {
  	sort(sortBy) {
    	if(this.sortBy === sortBy) {
      	this.sortOrder = -this.sortOrder;
      } else {
      	this.sortBy = sortBy
      }
      this.getUsers(1);
    },
    scrollHeader(currentPage) {
      document.location = '#wrapper';
      this.getUsers(currentPage);
    },
    setFilter(currentPage, filter) {
      this.filter = filter;
      this.getUsers(currentPage);
    },
    getUsers(page) {
      this.loading = true;
      var params = Object.assign(this.filter, {
        page,
        size: this.pagination.perPage,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder
      });
      axios.get('/api/users', { params }).then(response => {
        this.pagination.countData = response.data.count;
        this.scopusAutorId = response.data.scopusAutorId;
        this.hIndex = response.data.hIndex;
        this.fivePublications = response.data.fivePublications;
        this.pagination.currentPage = response.data.currentPage;
        this.pagination.firstItem = response.data.firstItem;
        this.users = response.data.users.data;
        this.loading = false;
      })
    },
    deleteUsers(users) {
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
            this.loading = true;
            axios.post('/api/delete-users', {
                users
            })
            .then(() => {
                this.selectUses = [];
                this.getUsers(this.pagination.currentPage);
            });
          }
      })
    },
    getUserExport() {
      this.loadingExport = true;
      var params = Object.assign(this.filter, {
        page: 1,
        size: this.pagination.perPage,
        sortBy: this.sortBy,
        sortOrder: this.sortOrder
      });
      axios.get('/api/users', { params }).then(response => {
          this.dataExport = response.data.users.data;
          this.loadingExport = false;
      }).then(() => {
          this.exportUsers();
      }).catch(() => {
          this.loadingExport = false;
      })
    },
    exportUsers() {
      const authors = XLSX.utils.table_to_book(document.getElementById('exportUsers'));
      const wb = XLSX.utils.book_new();
      wb.SheetNames.push("Authors");
      wb.Sheets.Authors = authors.Sheets.Sheet1;
      wb.Sheets.Authors['!cols'] = [
          { wch: 5 },  // id
          { wch: 30 }, // ПІБ
          { wch: 5 },  // Вік
          { wch: 10 }, // Посада
          { wch: 10 }, // Академічна група
          { wch: 10 }, // Факультет/інститут
          { wch: 10 }, // Кафедра
          { wch: 10 }, // Країна
          { wch: 5 }, // Індекс Гірша БД WoS
          { wch: 5 }, // Індекс Гірша БД Scopus
          { wch: 5 }, // Research ID
          { wch: 5 }, // ORCID
          { wch: 5 }  // 5 або більше публікацій в Scopus та/або WoS
      ];
      XLSX.writeFile(wb, 'Authors.xlsx');
    },
  }
}
</script>