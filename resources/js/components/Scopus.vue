<template>
    <div class="container page-content general-block">

        <div class="main-content">
            <div class="row my-4" id="header-table">
                <div class="col">
                    <select class="form-control w-50" id="sizeTable" v-model="pagination.perPage" @change="getData(1)">
                        <option :value="10">10</option>
                        <option :value="50">50</option>
                        <option :value="100">100</option>
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
                            <th scope="col">Data</th>
                            <th scope="col">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td class="text-left">
                              <div v-html="item['dc:title']"></div>
                              <div v-if="Object.keys(item['info']).length != 0">
                                <br>
                                Тип публікації: {{item['info']['publication_type_id']}}<br>
                                Рік: {{item['info']['year']}}<br>
                                Том (номер): {{item['info']['number']}}<br>
                                Сторінки: {{item['info']['pages']}}<br>
                                Країна: {{item['info']['country']}}<br>
                                Назва журналу: {{item['info']['name_magazine']}}<br>
                                Місто: {{item['info']['city']}}<br>
                                Мова: {{item['info']['languages']}}<br>
                                DOI: {{ item['info']['doi'] }}<br>
                                Автори: {{ item['info']['authors'] }}
                              </div>
                            </td>
                            <td class="text-left"><button @click="showDetails(item, index)">Показати</button></td>
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
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                count: 0,
                loading: true,
                data: [],
                pagination: {
                    currentPage: 1,
                    perPage: 10
                },
            };
        },
        created() {
            this.getData(this.pagination.currentPage);
        },
        methods: {
            scrollHeader() {
                this.getData(this.pagination.currentPage);
            },
            showDetails(item, index) {
                axios.get(item['prism:url'] + '?field=title,volume,pageRange,doi,publicationName,subtypeDescription,language,coverDate,head,authors&httpAccept=application/json&apiKey=01bdf01a22c7c48c8b10fd1dd890e76b').then(response => {
                    let head = response.data['abstracts-retrieval-response']['item']['bibrecord']['head'];
                    let coredata = response.data['abstracts-retrieval-response']['coredata'];
                    let authors = response.data['abstracts-retrieval-response']['authors']['author'];
                    item['info'] = {
                      "title": coredata['dc:title'],
                      "science_type_id": 'Scopus',
                      "publication_type_id": coredata['subtypeDescription'],
                      "year": head['source']['publicationdate']['year'],
                      "number": coredata['prism:volume'],
                      "pages": coredata['prism:pageRange'],
                      "country": head['correspondence']['affiliation']['country'],
                      "name_magazine": coredata['prism:publicationName'],
                      "city": head['correspondence']['affiliation']['city'],
                      "languages": head['citation-info']['abstract-language']['@language'],
                      "doi": coredata['prism:doi'],
                      "authors": authors.map(i => i['ce:indexed-name']).join(', ')
                    }
                    console.log(item['info'])
                })
            },
            getData(page) {
                this.loading = true;
                axios.get('/api/scopus', {
                    params: {
                        size: this.pagination.perPage,
                        page: page
                    }
                }).then(response => {
                    this.count = +response.data['opensearch:totalResults'];
                    this.pagination.currentPage = +response.data['opensearch:startIndex'];
                    this.data = response.data.entry.map(item => {
                      item.info = {};
                      return item;
                    });
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

