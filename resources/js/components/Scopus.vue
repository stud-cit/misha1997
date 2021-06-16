<template>
    <div class="container page-content general-block">

        <div class="exports">
          <button class="export-button" @click="getDataScopus()">
              <span
                  class="spinner-border spinner-border-sm"
                  style="width: 19px; height: 19px; margin-right: 10px"
                  role="status"
                  aria-hidden="true"
                  v-if="loadingImport"
              ></span>
              <img v-else src="/img/download.png"> Отримати публікації Scopus
          </button>
        </div>

        <div class="main-content">
            <div class="row my-4" id="header-table">
                <div class="col">
                    <select class="form-control w-50 ml-2" id="sizeTable" v-model="pagination.perPage" @change="getData(1)">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="250">250</option>
                        <option :value="500">500</option>
                        <option :value="countPublications">Всі</option>
                    </select>
                </div>
                <div class="col text-right">
                    <paginate
                        class="paginate-top"
                        v-model="pagination.currentPage"
                        :page-count="Math.ceil(countPublications / pagination.perPage)"
                        @click.native="scrollHeader()"

                        prev-text="<"
                        next-text=">"

                        container-class="pagination"
                        page-class="page-item"
                        page-link-class="page-link"
                        prev-class="page-link"
                        next-class="page-link">
                    </paginate>
                </div>
            </div>
            <div class="table-responsive text-center table-list">
                <table :class="['table', 'table-bordered', loading ? 'opacityTable' : '']">
                    <thead>
                        <tr>
                            <td colspan="7" class="bg-white text-left pb-3 pt-0">Всього публікацій: {{countPublications}}</td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                            <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                        </tr>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Вид публікації</th>
                            <th scope="col">ПІБ автора/співавторів</th>
                            <th scope="col">Назва публікації</th>
                            <th scope="col">Рік видання</th>
                            <th scope="col">Верифікація</th>
                            <th scope="col">Дата занесення</th>
                            <th scope="col" v-if="checkAccess">Редагувати</th>
                            <th scope="col" v-if="checkAccess">Обрати</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td scope="row">{{ pagination.firstItem + index }}</td>
                            <td>{{ item.publication_type.title }}</td>
                            <td>
                                <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                    <a :href="'/user/'+author.author.id">{{author.author.name}} </a>
                                </span>
                            </td>
                            <td><a :href="'/publications/'+item.id"> {{ item.title }} </a> </td>
                            <td>{{ item.year }}</td>
                            <td>{{ item.verification ? 'Так' : 'Ні' }}</td>
                            <td>{{ item.created_at }}</td>
                            <td v-if="checkAccess">
                                <a :href="'/publications/edit/'+item.id"><i class="fa fa-edit fa-2x"></i></a>
                            </td>
                            <td class="icons" v-if="checkAccess">
                                <input
                                    type="checkbox"
                                    :checked="selectPublications.indexOf(item) != -1 ? true : false"
                                    @click="selectItem(item)"
                                >
                            </td>
                        </tr>
                        <tr v-if="countPublications != 0">
                            <td colspan="12" class="text-left">Всього публікацій: {{ countPublications }} </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="countPublications == 0 && !loading">
                    Публікації відсутні
                </div>
            </div>
            <paginate
                class="mt-4"
                v-model="pagination.currentPage"
                :page-count="Math.ceil(countPublications / pagination.perPage)"
                @click.native="scrollHeader()"

                prev-text="<"
                next-text=">"

                container-class="pagination"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
              countPublications: 0,
              loading: true,
              loadingImport: false,
              selectPublications: [],
              data: [],
              pagination: {
                  currentPage: 1,
                  perPage: 10,
                  firstItem: 1
              },
            };
        },
        created() {
            this.getData(this.pagination.currentPage);
        },
        computed: {
            // отримуємо авторизованого користувача із store
            authUser() {
                return this.$store.getters.authUser
            },
            checkAccess() {
                if(this.authUser.roles_id == 4) {
                    return true;
                } else if((this.$store.getters.accessMode == 'open' && this.authUser.roles_id == 1) || this.authUser.roles_id != 5) {
                    return true;
                } else {
                    return false;
                }
            },
        },
        methods: {
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            getDataScopus() {
              this.loadingImport = true;
                axios.get('/api/publications-scopus').then(response => {
                  this.loadingImport = false;
                  this.getData(1);
                }).catch(() => {
                    this.loadingImport = false;
                })
            },
            getData(page) {
                axios.get('/api/publications', {
                    params: {
                        size: this.pagination.perPage,
                        page: page,
                        scopus: true
                    }
                }).then(response => {
                    this.countPublications = response.data.count;
                    this.pagination.currentPage = response.data.currentPage;
                    this.pagination.firstItem = response.data.firstItem;
                    this.data = response.data.publications.data;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .opacityTable {
        opacity: 0.5;
    }
    .table-list{
        margin-top: 0px;
    }
    .paginate-top.pagination {
        margin: 0;
        float: right;
    }
    .exports{
        display: grid;
        justify-content: center;
    }
</style>

