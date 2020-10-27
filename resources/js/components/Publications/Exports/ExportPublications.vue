<template>
    <div>
        <button class="export-button" @click="exportPublications('export')">
            <img src="/img/download.png" alt=""> Експорт публікацій word
        </button>
        <div id="export" v-show="false">
            <template v-if="filteredData.articles.length > 0">
            <h2>Стаття у фахових виданнях України, інші статті</h2>
            <ol>
                <li v-for="(item, index) in filteredData.articles" :key="index">
                    {{item.initials}}, {{ item.title }}, {{ item.name_magazine }}. - {{ item.year }} - T. №. - С. {{ item.pages }}
                </li>
            </ol>
            </template>

            <template v-if="filteredData.monograph.length > 0">
            <h2>Монографії</h2>
            <ol>
                <li v-for="(item, index) in filteredData.monograph" :key="index">
                   {{ item.initials }},  {{ item.title }}: {{ item.publication_type.title.toLowerCase() }}; за ред. {{ item.by_editing }} - {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. - {{ item.pages }} c.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.books.length > 0">
            <h2>Посібники, підручники</h2>
            <ol>
                <li v-for="(item, index) in filteredData.books" :key="index">
                   {{ item.initials }},  {{ item.title }}: - {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. - {{ item.pages }} c.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.patents.length > 0">
            <h2>Патент</h2>
            <ol>
                <li v-for="(item, index) in filteredData.patents" :key="index">
                   Пат. {{ item.number_certificate }}, {{ item.country }}, {{ item.mpk }}. {{ item.title }}; {{ item.applicant }}. - №{{ item.application_number }}; {{ item.date_publication }}, Бюл. №{{ item.newsletter_number }}.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.certificates.length > 0">
            <h2>Свідотство про реєстрації авторських прав на твір/рішення</h2>
            <ol>
                <li v-for="(item, index) in filteredData.certificates" :key="index">
                   Свідотство про реєстрації авторських прав на твір №{{ item.application_number }}. {{ item.publication_type.title }} "{{ item.title }}" / {{ item.initials }}. ({{ item.country }}). - №{{ item.number_certificate }}; заяв. {{ item.date_publication }}.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.methodicals.length > 0">
            <h2>Методичні вказівки</h2>
            <ol>
                <li v-for="(item, index) in filteredData.methodicals" :key="index">
                   {{ item.initials }}. {{ item.title }}. - {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. - {{ item.pages }} c.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.electronics.length > 0">
            <h2>Електронні видання</h2>
            <ol>
                <li v-for="(item, index) in filteredData.electronics" :key="index">
                   {{ item.initials }}, {{ item.title }}: {{ item.publication_type.title.toLowerCase() }}; [електронний ресурс] / {{ item.initials }}. - {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. - {{ item.pages }} c. - Режим доступу: {{ item.access_mode ? "Відкритий" : "Закритий" }}.
                </li>
            </ol>
            </template>

            <template v-if="filteredData.thesis.length > 0">
            <h2>Тези доповіді</h2>
            <ol>
                <li v-for="(item, index) in filteredData.thesis" :key="index">
                   {{ item.initials }}. {{ item.title }} // {{ item.name_conference }} / за заг. ред. {{ item.initials }} - {{ item.city }}: {{ item.editor_name }}, {{ item.year }}. - {{ item.pages }} c.
                </li>
            </ol>
            </template>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            exportList: Array,
        },
        methods: {
            exportPublications(id){
                var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' "+
                    "xmlns:w='urn:schemas-microsoft-com:office:word' "+
                    "xmlns='http://www.w3.org/TR/REC-html40'>"+
                    "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title><style></style></head><body>";
                var footer = "</body></html>";
                var sourceHTML = header+document.getElementById(id).innerHTML+footer;

                var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                var fileDownload = document.createElement("a");
                document.body.appendChild(fileDownload);
                fileDownload.href = source;
                fileDownload.download = 'document.doc';
                fileDownload.click();
                document.body.removeChild(fileDownload);
            },
        },
        computed: {
            filteredData() {
                const publications = this.exportList;
                const exportEmpty = {
                    articles: [],
                    monograph: [],
                    books: [],
                    patents: [],
                    certificates: [],
                    methodicals: [],
                    electronics: [],
                    thesis: [],
                };
                publications.map(item => {
                    if(item.publication_type_id == 1 || item.publication_type_id == 3) {
                        exportEmpty.articles.push(item);
                    }
                    if(item.publication_type_id == 6) {
                        exportEmpty.monograph.push(item);
                    }
                    if(item.publication_type_id == 5) {
                        exportEmpty.books.push(item);
                    }
                    if(item.publication_type_id == 10) {
                        exportEmpty.patents.push(item);
                    }
                    if(item.publication_type_id == 11) {
                        exportEmpty.certificates.push(item);
                    }
                    if(item.publication_type_id == 12) {
                        exportEmpty.methodicals.push(item);
                    }
                    if(item.publication_type_id == 13) {
                        exportEmpty.methodicals.push(item);
                    }
                    if(item.publication_type_id == 9) {
                        exportEmpty.thesis.push(item);
                    }
                })
                return exportEmpty;
            }
        }
    }
</script>