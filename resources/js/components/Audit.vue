<template>
    <div class="container page-content general-block">
        <h1 class="page-title">Аудит</h1>
        <div class="main-content">
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
                            <th scope="col">Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td class="text-left" v-html="item.text"></td>
                            <td>{{ item.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="my-4" v-if="count == 0">
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
            scrollHeader() {
                document.location = '#header-table';
                this.getData(this.pagination.currentPage);
            },
            // getters
            // всі публікації
            getData(page) {
                this.loading = true;
                axios.get('/api/audit', {
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

