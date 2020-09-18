<template>
    <div>



        <button class="export-button" @click="exportPublications('export')"><img src="/img/download.png" alt=""> Експорт усіх публікацій</button>
        <div id="export" v-show="false">
            <template v-if="filteredData.articles[0]">
            <h2>	Статті</h2>
            <ol>
                <li v-for="(item, index) in filteredData.articles" :key="index">
                        {{exportParser(item)}}


                </li>

            </ol>
            </template>
            <template v-if="filteredData.books[0]">
            <h2>	Монографії, посібники, підручники</h2>
            <ol>
                <li v-for="(item, index) in filteredData.books" :key="index">
                    {{exportParser(item)}}
<!--                    {{item.initials + ' ' + item.title + ': ' + item.publication_type.title.toLowerCase() + ' / за ред. ' + item.by_editing + '. ' + item.city + ': ' + item.editor_name + ', ' + item.year + '. С. ' + item.pages}}-->




                </li>

            </ol>
            </template>
            <template v-if="filteredData.booksParts[0]">
            <h2>	Частини монографій, книг</h2>
            <ol>
                <li v-for="(item, index) in filteredData.booksParts" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="filteredData.thesis[0]">
            <h2>	Тези доповіді</h2>
            <ol>
                <li v-for="(item, index) in filteredData.thesis" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="filteredData.patents[0]">
            <h2>	Патенти</h2>
            <ol>
                <li v-for="(item, index) in filteredData.patents" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="filteredData.certificates[0]">
            <h2>	Свідоцтва про реєстрації авторських прав на твір/рішення</h2>
            <ol>
                <li v-for="(item, index) in filteredData.certificates" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="filteredData.methodicals[0]">
            <h2>	Методичні вказівки</h2>
            <ol>
                <li v-for="(item, index) in filteredData.methodicals" :key="index">
                    {{exportParser(item)}}

                </li>

            </ol>
            </template>
            <template v-if="filteredData.electronics[0]">
            <h2>	Електронні видання</h2>
            <ol>
                <li v-for="(item, index) in filteredData.electronics" :key="index">
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


            };
        },
        props: {
            exportList: Array,
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
        computed: {
            filteredData (){
                const publications = this.exportList;
                const exportEmpty = {
                    articles: [],
                    books: [],
                    booksParts: [],
                    thesis: [],
                    patents: [],
                    certificates: [],
                    methodicals: [],
                    electronics: [],
                };
                for (let i = 0; i <publications.length; i++){
                    if(publications[i].publication_type.type == "article"){

                        exportEmpty.articles.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "book"){
                        exportEmpty.books.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "book-part"){
                        exportEmpty.booksParts.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "thesis"){
                        exportEmpty.thesis.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "patent"){
                        exportEmpty.patents.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "certificate"){
                        exportEmpty.certificates.push(publications[i]);
                    }
                    else if(publications[i].publication_type.type  == "methodical"){
                        exportEmpty.methodicals.push(publications[i]);
                    }
                    else {
                        exportEmpty.electronics.push(publications[i]);
                    }
                }

                return exportEmpty;


            }
        },


    }
</script>

<style lang="scss" scoped>


</style>
