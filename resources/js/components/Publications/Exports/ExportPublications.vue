<template>
    <div>



        <button @click="exportPublications('export')">export publications</button>
        <div id="export">
            <template v-if="exportData.articles[0]">
            <h2>	Статті</h2>
            <ol>
                <li v-for="(item, index) in exportData.articles" :key="index">
                        {{exportParser(item)}}
<!--                    {{item.initials + ' ' + item.title + '. ' + item.name_magazine + '. ' + item.year + '. № ' + item.number + '. С. ' + item.pages}}-->

                </li>

            </ol>
            </template>
            <template v-if="exportData.books[0]">
            <h2>	Монографії, посібники, підручники</h2>
            <ol>
                <li v-for="(item, index) in exportData.books" :key="index">
                    {{exportParser(item)}}
<!--                    {{item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + ' / за ред. ' + item.by_editing + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages}}-->




                </li>

            </ol>
            </template>
            <template v-if="exportData.booksParts[0]">
            <h2>	Частини монографій, книг</h2>
            <ol>
                <li v-for="(item, index) in exportData.booksParts" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="exportData.thesis[0]">
            <h2>	Тези доповіді</h2>
            <ol>
                <li v-for="(item, index) in exportData.thesis" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="exportData.patents[0]">
            <h2>	Патенти</h2>
            <ol>
                <li v-for="(item, index) in exportData.patents" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="exportData.certificates[0]">
            <h2>	Свідоцтва про реєстрації авторських прав на твір/рішення</h2>
            <ol>
                <li v-for="(item, index) in exportData.certificates" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="exportData.methodicals[0]">
            <h2>	Методичні вказівки</h2>
            <ol>
                <li v-for="(item, index) in exportData.methodicals" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="exportData.electronics[0]">
            <h2>	Електронні видання</h2>
            <ol>
                <li v-for="(item, index) in exportData.electronics" :key="index">
                    {{exportParser(item)}}

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
                // articles: [],
                // books: [],
                // booksParts: [],

            };
        },
        props: {
            exportData: Object,
        },




        components: {

        },

        methods: {
            exportParser(item){

                let result = "";
                switch(item.publication_type.type){
                  case "article":
                      result = item.initials + ' ' + item.title + '. ' + item.name_magazine + '. ' + item.year + '. № ' + item.number + '. С. ' + item.pages + '.' + (item.doi ? ' DOI: ' + item.doi : '');
                      break;
                  case "book":
                      result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + 'с.' + (item.doi ? ' DOI: ' + item.doi : '');
                      break;
                  case "book-part":
                      result = item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + (item.by_editing ? ' / за ред. ' + item.by_editing : '') + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С.' + item.pages + '.';
                      break;
                  case "thesis":
                      result = item.initials + ' ' + item.title + ': матеріали ' + item.name_conference + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С.' + item.pages + '. ' + (item.doi ? ' DOI: ' + item.doi : '');
                      break;
                  case "patent":
                      result =  item.title + ': пат. ' + item.number_certificate + ' ' + item.country + ': МПК ' + item.mpk + '. № ' + item.application_number + '; заявл. ' + item.date_application + '; опубл. ' + item.date_publication + ', Бюл. № ' + item.newsletter_number + '.';
                      break;
                  case "certificate":
                      result =  'Свідоцтво про реєстрацію авторського права на твір № ' +  item.number_certificate + '. ' + item.title + ' / ' + item.initials + ' ' + item.country + '; заявл. ' + item.date_application + '; опубл. ' + item.date_publication + '.';
                      break;
                  case "methodical":
                      result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + 'с.';
                      break;
                  case "electronic":
                      result =  item.initials + ' ' + item.title + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. ' + item.pages + 'с. URL: ' + item.url;
                      break;



                      }

                return result;
            },
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

    }
</script>

<style lang="scss" scoped>


</style>
