<template>
    <div>
        <div class="table-responsive text-center table-list">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Вид пуб-ції</th>
                        <th scope="col">Прізвище та ініціали автора\співавторів</th>
                        <th scope="col">Назва публікації</th>
                        <th scope="col">Рік видання</th>
                        <th scope="col">БД Scopus\WoS</th>
                        <th scope="col">Науковий керівник</th>
                        <th scope="col" v-if="(authUser.roles_id == 4 || (access == 'open' && authUser.roles_id != 1))">Редагувати</th>
                        <th scope="col" v-if="(authUser.roles_id == 4 || (access == 'open' && authUser.roles_id != 1))">Обрати</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in filterList" :key="index">
                        <td scope="row">{{ index + 1 + (pagination.currentPage - 1) * pagination.perPage }}</td>
                        <td>{{ item.publication_type.title }}</td>
                        <td>
                            <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                <router-link v-if="!author.supervisor" :to="'/user/'+author.author.id">{{author.author.name}} </router-link>
                            </span>
                        </td>
                        <td><router-link :to="{path: `/publications/${item.id}`}"> {{ item.title }} </router-link> </td>
                        <td>{{ item.year}}</td>
                        <td>{{ item.science_type ? item.science_type.type : '' }}</td>
                        <td>
                            <span class="authors" v-for="(author, index) in item.authors" :key="index">
                                <router-link v-if="author.supervisor" :to="'/user/'+author.author.id">{{author.author.name}}</router-link>
                            </span>
                        </td>
                        <td v-if="(authUser.roles_id == 4 || (access == 'open' && authUser.roles_id != 1))">
                            <router-link :to="`/publications/edit/${item.id}`"><i class="fa fa-edit fa-2x"></i></router-link>
                        </td>
                        <td class="icons" v-if="(authUser.roles_id == 4 || (access == 'open' && authUser.roles_id != 1))">
                            <input
                                type="checkbox"
                                :checked="selectPublications.indexOf(item) != -1 ? true : false"
                                @click="selectItem(item)"
                            >
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="spinner-border my-4" role="status" v-if="loading">
                <span class="sr-only">Loading...</span>
            </div>
            <div class="my-4" v-if="publications.length == 0">
                Піблікації відсутні
            </div>
        </div>
        <paginate
            v-model="pagination.currentPage"
            :page-count="pagination.numPage"

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
        loading: Boolean
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
        selectItem(item) {
            this.$emit('select', item);
        }
    },
    computed: {
        access() {
            return this.$store.getters.accessMode
        },
        filterList() {
            this.pagination.numPage = Math.ceil(this.publications.length / this.pagination.perPage);
            return this.publications.slice((this.pagination.currentPage - 1) * this.pagination.perPage, this.pagination.currentPage * this.pagination.perPage);
        }
    }
}
</script>