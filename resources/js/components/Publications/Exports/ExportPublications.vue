<template>
    <div>



        <button @click="exportPublications('export')">export publications</button>
        <div id="export">
            <template v-if="exportData.articles[0]">
            <h2>	Статті</h2>
            <ol>
                <li v-for="(item, index) in exportData.articles" :key="index">
                    Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

                </li>

            </ol>
            </template>
            <template v-if="exportData.books[0]">
            <h2>	Монографії, посібники, підручники</h2>
            <ol>
                <li v-for="(item, index) in exportData.books" :key="index">
                    Прізвище, ініціали,  назва монографії : вид публікації; за ред. ініціали, прізвищ(а)е  - місто видання : назва видавництва, рік видання. –  кількість сторінок с.

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

            exportPublications(id){
                console.log(this.data['Стаття у фахових виданнях України']);
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
            // dataParser(){
            //     const publications = this.data;
            //
            //     for (let i = 0; i <publications.length; i++){
            //         if(publications[i].publication_type.type == "article"){
            //             console.log(1);
            //             this.articles.push(publications[i]);
            //         }
            //         else if(publications[i].publication_type.type  == "book"){
            //             this.books.push(publications[i]);
            //         }
            //         else if(publications[i].publication_type.type  == "book-part"){
            //             this.booksParts.push(publications[i]);
            //         }
            //     }
            //     console.log(this.articles);
            //     // publications.map((a) => a.author.name);
            // }


        },

    }
</script>

<style lang="scss" scoped>


</style>
