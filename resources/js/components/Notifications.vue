<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Повідомлення</h1>
        <div class="main-content">
            <div class="text-right mt-1">
                <button type="button" class="btn btn-primary" @click="deleteItems()">Видалити всі повідомлення</button>
            </div>
            <div class="row mt-4">
                <div class="col-lg-10">
                    <input v-model="search" class="form-control" type="text" placeholder="Пошук">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn-block btn btn-primary" @click="getData(1)">Пошук</button>
                </div>
            </div>
            <div class="row my-4" id="header-table">
                <div class="col">
                    <select class="form-control w-50" id="sizeTable" v-model="pagination.perPage" @change="getData(1)">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
                        <option :value="250">250</option>
                        <option :value="500">500</option>
                        <option :value="count">Всі</option>
                    </select>
                </div>
                <div class="col text-right">
                    <paginate
                        class="paginate-top"
                        v-model="pagination.currentPage"
                        :page-count="Math.ceil(count / pagination.perPage)"
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
                            <th scope="col"></th>
                            <th scope="col" width="90px">Дата</th>
                            <th scope="col" width="120px">Прочитати</th>
                            <th scope="col" width="120px">Видалити</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td class="text-left" v-html="item.text"></td>
                            <td>{{ item.created_at }}</td>
                            <td>
                                <i v-if="item.status" class="fa fa-envelope-o fa-2x"></i>
                                <i v-else class="fa fa-envelope cursor fa-2x" @click="watchedItem(item)"></i>
                            </td>
                            <td>
                                <i class="fa fa-trash-o fa-2x" @click="deleteItem(item)"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="count == 0 && !loading">
                    Дані відсутні
                </div>
            </div>
            <paginate
                class="mt-4"
                v-model="pagination.currentPage"
                :page-count="Math.ceil(count / pagination.perPage)"
                @click.native="scrollHeader()"

                prev-text="<"
                next-text=">"

                container-class="pagination"
                page-class="page-item"
                page-link-class="page-link"
                prev-class="page-link"
                next-class="page-link">
            </paginate>

            <div class="step-button-group">
                <back-button></back-button>
            </div>
        </div>
    </div>
</template>

<script>
    import BackButton from "./Buttons/Back";
    import { mapMutations } from 'vuex';
    export default {
        data() {
            return {
                count: 0,
                loading: true,
                data: [],
                search: "",
                pagination: {
                    currentPage: 1,
                    perPage: 10,
                    firstItem: 1
                },
            };
        },
        components: {
            BackButton
        },
        created() {
            this.getData(this.pagination.currentPage);
        },
        methods: {
            ...mapMutations([
                "updateNotifications"
            ]),
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
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
            // getters
            // всі публікації
            getData(page) {
                this.loading = true;
                axios.get('/api/notifications/'+this.$store.getters.authUser.id, {
                    params: {
                        size: this.pagination.perPage,
                        page: page,
                        search: this.search
                    }
                }).then(response => {
                    this.count = response.data.count;
                    this.pagination.currentPage = response.data.currentPage;
                    this.pagination.firstItem = response.data.firstItem;
                    this.data = response.data.data.data;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
    .fa-2x {
        cursor: pointer;
    }
    .opacityTable {
        opacity: 0.5;
    }
    .paginate-top.pagination {
        margin: 0;
        float: right;
    }
    .table-list{
        margin-top: 0px;
    }
</style>

