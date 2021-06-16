<template>
    <div>
        <button class="export-button" @click="getData()" :disabled="loading">
            <span
                class="spinner-border spinner-border-sm"
                style="width: 19px; height: 19px; margin-right: 10px"
                role="status"
                aria-hidden="true"
                v-if="loading"
            ></span>
            <img v-else src="/img/download.png"> Експорт публікацій Word
        </button>
        <div id="export" v-show="false">
            <template v-if="articleReport.length > 0">
                <h2>Стаття-доповідь у матеріалах наукових конференціях</h2>
                <ol>
                    <li v-for="(item, index) in articleReport" :key="index">
                        {{item.initials}} {{ item.title }}. <i>{{ item.name_magazine }}</i>. {{ item.year }}. {{ item.number }}. C. {{ item.pages }}. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
                    </li>
                </ol>
            </template>


            <template v-if="articleProfessional.length > 0">
                <h2>Стаття у фахових виданнях України</h2>
                <ol>
                    <li v-for="(item, index) in articleProfessional" :key="index">
                        {{item.initials}} {{ item.title }}. <i>{{ item.name_magazine }}</i>. {{ item.year }}. {{ item.number }}. C. {{ item.pages }}. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
                    </li>
                </ol>
            </template>

            <template v-if="otherArticles.length > 0">
                <h2>Інші статті</h2>
                <ol>
                    <li v-for="(item, index) in otherArticles" :key="index">
                        {{item.initials}} {{ item.title }}. <i>{{ item.name_magazine }}</i>. {{ item.year }}. {{ item.number }}. C. {{ item.pages }}. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
                    </li>
                </ol>
            </template>

            <template v-if="monograph.length > 0">
                <h2>Монографії</h2>
                <ol>
                    <li v-for="(item, index) in monograph" :key="index">
                    {{ item.initials }} {{ item.title }} : {{ item.publication_type.title.toLowerCase() }}<span v-if="item.by_editing"> / за ред. {{ item.by_editing }}</span>. {{ item.city }} : ПФ «Видавництво «{{ item.editor_name }}», {{ item.year }}. {{ item.pages }} c. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
                    </li>
                </ol>
            </template>

            <template v-if="parts.length > 0">
                <h2>Розділ монографії / розділ книги</h2>
                <ol>
                    <li v-for="(item, index) in parts" :key="index">
                    {{ item.initials }} {{ item.title }}. <i>{{ item.name_monograph }}</i> : кол. моногр. / <span v-if="item.by_editing"> / за ред. {{ item.by_editing }}.</span> {{ item.city }} : {{ item.editor_name }}, {{ item.year }}. C. {{ item.pages }}.
                    </li>
                </ol>
            </template>

            <template v-if="books.length > 0">
                <h2>Навчальні посібники, підручники</h2>
                <ol>
                    <li v-for="(item, index) in books" :key="index">
                    {{ item.initials }} {{ item.title }} : {{ item.publication_type.title.toLowerCase() }}<span v-if="item.by_editing"> / за ред. {{ item.by_editing }}</span>. {{ item.city }} : {{ item.editor_name }}, {{ item.year }}. {{ item.pages }} c.
                    </li>
                </ol>
            </template>

            <template v-if="patents.length > 0">
                <h2>Патент</h2>
                <ol>
                    <li v-for="(item, index) in patents" :key="index">
                    {{ item.title }} Пат. {{ item.number_certificate }} {{ item.country }}: МПК{{ item.mpk }}. №{{ item.application_number }}; заявл. {{ item.date_application }}; опубл. {{ item.date_publication }}, Бюл. № {{ item.newsletter_number }}.
                    </li>
                </ol>
            </template>

            <template v-if="certificates.length > 0">
                <h2>Свідоцтво про реєстрацію авторських прав на твір / рішення</h2>
                <ol>
                    <li v-for="(item, index) in certificates" :key="index">
                    Свідоцтво про реєстрацію авторського права на твір «{{ item.title }}» № {{ item.number_certificate }} {{ item.country }} / {{ item.initials }} ; {{ item.applicant }} ; заяв. {{ item.date_application }} ; опубл. {{ item.date_publication }}.
                    </li>
                </ol>
            </template>

            <template v-if="methodicals.length > 0">
                <h2>Методичні вказівки</h2>
                <ol>
                    <li v-for="(item, index) in methodicals" :key="index">
                    {{ item.initials }} {{ item.title }}. {{ item.city }} : {{ item.editor_name }}, {{ item.year }}. {{ item.pages }} c.
                    </li>
                </ol>
            </template>

            <template v-if="electronics.length > 0">
                <h2>Електронні видання</h2>
                <ol>
                    <li v-for="(item, index) in electronics" :key="index">
                    {{ item.initials }}. {{ item.title }}: {{ item.publication_type.title.toLowerCase() }}; [електронний ресурс] / {{ item.initials }}. {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. {{ item.pages }} c. URL: {{ item.url }}.
                    </li>
                </ol>
            </template>

            <template v-if="thesis.length > 0">
                <h2>Тези доповіді</h2>
                <ol>
                    <li v-for="(item, index) in thesis" :key="index">
                    {{ item.initials }} {{ item.title }}. <i>{{ item.name_conference }}</i> : тези доповідей <span v-if="item.by_editing"> / за ред. {{ item.by_editing }}</span>. {{ item.city }} : {{ item.editor_name }}, {{ item.year }}. C. {{ item.pages }}. <span v-if="item.doi">DOI: {{ item.doi }}.</span>
                    </li>
                </ol>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
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
                loading: false
            }
        },
        props: {
            filters: Object,
            countPublications: Number
        },
        computed: {
            authUser() {
                return this.$store.getters.authUser
            }
        },
        methods: {
            getData() {
                this.loading = true;
                var url = '/api/publications';
                if(this.$route.name == 'my-publications') {
                    url += '/'+this.authUser.id;
                }
                axios.get(url, {
                    params: {
                        size: this.countPublications,
                        title: this.filters.title,
                        authors_f: this.filters.authors_f,
                        science_type_id: this.filters.science_type_id,
                        year: this.filters.year,
                        country: this.filters.country,
                        publication_type_id: this.filters.publication_type_id,
                        faculty_code: this.filters.faculty_code,
                        department_code: this.filters.department_code,
                        withSupervisor: this.filters.withSupervisor
                    }
                }).then(response => {
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
                        if(item.publication_type_id == 5) {
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
                    this.loading = false;
                }).then(() => {
                    this.exportPublications();
                }).catch(() => {
                    this.loading = false;
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
                fileDownload.download = 'document.doc';
                fileDownload.click();
                document.body.removeChild(fileDownload);
            }
        }
    }
</script>
