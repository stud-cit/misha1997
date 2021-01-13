<template>
    <div>
        <paginate
            v-model="pagination.currentPage"
            :page-count="pagination.numPage"
            @click.native="scrollHeader()"

            :prev-text="'<'"
            :next-text="'>'"

            :container-class="'pagination'"
            page-class="page-item"
            page-link-class="page-link"
            prev-class="page-link"
            next-class="page-link">
        </paginate>
        <div class="table-responsive text-center table-list">
            <table id="header-table" class="table table-bordered">
                <thead id="header-table">
                    <tr>
                        <td colspan="8" class="bg-white text-left pb-3 pt-0">Всього публікацій: {{countPublications}}</td>
                        <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                        <td class="bg-white pb-3 pt-0" v-if="checkAccess"></td>
                    </tr>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Вид публікації</th>
                        <th scope="col">ПІБ автора/співавторів</th>
                        <th scope="col">Назва публікації</th>
                        <th scope="col">Рік видання</th>
                        <th scope="col">БД Scopus/WoS</th>
                        <th scope="col">Науковий керівник</th>
                        <th scope="col">Дата занесення</th>
                        <th scope="col" v-if="checkAccess">Редагувати</th>
                        <th scope="col" v-if="checkAccess">Обрати</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in filterList" :key="index">
                        <td scope="row">{{ index + 1 + (pagination.currentPage - 1) * pagination.perPage }}</td>
                        <td>{{ item.publication_type.title }}</td>
                        <td>
                            <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                <a v-if="!author.supervisor" :href="'/user/'+author.author.id">{{author.author.name}} </a>
                            </span>
                        </td>
                        <td><a :href="'/publications/'+item.id"> {{ item.title }} </a> </td>
                        <td>{{ item.year}}</td>
                        <td>{{ item.science_type ? item.science_type.type : '' }}</td>
                        <td>
                            <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                <a v-if="author.supervisor" :href="'/user/'+author.author.id">{{author.author.name}}</a>
                            </span>
                        </td>
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
                    <tr>
                        <td colspan="12" class="text-left">Всього публікацій: {{ countPublications }} </td>
                    </tr>
                </tbody>
            </table>
            <div class="spinner-border my-4" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="my-4" v-if="countPublications == 0">
                Публікації відсутні
            </div>
        </div>
        <paginate
            v-model="pagination.currentPage"
            :page-count="pagination.numPage"
            @click.native="scrollHeader()"

            :prev-text="'<'"
            :next-text="'>'"

            :container-class="'pagination'"
            page-class="page-item"
            page-link-class="page-link"
            prev-class="page-link"
            next-class="page-link">
        </paginate>
    </div>
</template>
<script>

export default {
    props: {
        publications: Array,
        selectPublications: Array,
        authUser: {
            roles_id: null
        },
        loading: Boolean,
        countPublications: Number
    },
    data() {
        return {
            pagination: {
                currentPage: 1,
                perPage: 10,
                numPage: 1
            },
        }
    },
    methods: {
        scrollHeader() {
            document.location = '#header-table';
            this.$emit('page', this.pagination.currentPage);
        },
        selectItem(item) {
            this.$emit('select', item);
        }
    },
    watch: {
        publications: {
            deep: true,
            handler() {
                this.pagination.currentPage = 1;
                this.pagination.perPage = 10;
                this.pagination.numPage = 1;
            }
        }
    },
    computed: {
        checkAccess() {
            if(this.$store.getters.accessMode == 'open' && this.$route.name == 'my-publications') {
                return true;
            } else if(this.authUser.roles_id == 4 || (this.$store.getters.accessMode == 'open' && this.authUser.roles_id != 1)) {
                return true;
            } else {
                return false;
            }
        },
        filterList() {
            this.pagination.numPage = Math.ceil(this.countPublications / this.pagination.perPage);
            return this.publications.slice((this.pagination.currentPage - 1) * this.pagination.perPage, this.pagination.currentPage * this.pagination.perPage);

        }
    }
}
</script>
