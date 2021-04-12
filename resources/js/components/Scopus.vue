<template>
    <div class="container page-content general-block">

        <div class="main-content">
            <div class="table-responsive text-center table-list">
                <table :class="['table', 'table-bordered', loading ? 'opacityTable' : '']">
                    <thead>
                        <tr>
                            <th scope="col">Кількість {{ data.length }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td class="text-left">
                              <div>
                                <b>Назва:</b> {{ item.title }}<br>
                                <b>Тип публікації:</b> {{ item.publication_type_id }}<br>
                                <b>Рік:</b> {{ item.year }}<br>
                                <b>Том (номер):</b> {{ item.number }}<br>
                                <b>Сторінки:</b> {{ item.pages }}<br>
                                <b>Країна:</b> {{ item.country }}<br>
                                <b>Назва журналу:</b> {{ item.name_magazine }}<br>
                                <b>Місто:</b> {{ item.city }}<br>
                                <b>Мова:</b> {{ item.languages }}<br>
                                <b>DOI:</b> {{ item.doi }}<br>
                              </div>
                              <br>
                              Автори:
                              <div v-for="author in item.authors">
                                <b>ПІБ:</b> {{ author.name }}<br>
                                <b>scopus_id:</b> {{ author.scopus_id }}<br>
                                <b>Зареєстрований в SCIPUB:</b> {{ author.register ? 'Так' : 'Ні' }}
                              </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="spinner-border my-4" role="status" v-if="loading">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: true,
                data: [],
            };
        },
        created() {
            this.getData();
        },
        methods: {
            getData(page) {
                this.loading = true;
                axios.get('/api/scopus').then(response => {
                  this.data = response.data;
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
</style>

