<template>
    <div>



        <button @click="exportPublications('export')">export publications</button>
        <div id="export">
            <template v-if="exportData.articles[0]">
            <h2>	Статті</h2>
            <ol>
                <li v-for="(item, index) in exportData.articles" :key="index">

                    {{item.initials + ', ' + item.title + ' // ' + item.name_magazine + '. – ' + item.year + '. – ' + '№ ' + item.number + '. номера сторінок ' + item.pages}}
                    // Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

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
            <template v-if="exportData.booksParts[0]">
            <h2>	Частини монографій, книг</h2>
            <ol>
                <li v-for="(item, index) in exportData.booksParts" :key="index">
                    Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

                </li>

            </ol>
            </template>
            <template v-if="exportData.thesis[0]">
            <h2>	Тези доповіді</h2>
            <ol>
                <li v-for="(item, index) in exportData.thesis" :key="index">
                    Прізвище, ініціали,  назва статті / ініціали, прізвищ(а)е співавтор(ів)а // назва журналу. – рік видання. – Т.  , №  . – С. номера сторінок з – по.

                </li>

            </ol>
            </template>
            <template v-if="exportData.patents[0]">
            <h2>	Патенти</h2>
            <ol>
                <li v-for="(item, index) in exportData.patents" :key="index">
                    Пат. номер патенту країна де отриманий патент, МПК. Назва патенту/прізвище, ініціали автора; заявник і власник патенту. - № номер заявки; заявл. дата подачі заявки; опубл. дата публікації про видачу, Бюл. №  .

                </li>

            </ol>
            </template>
            <template v-if="exportData.certificates[0]">
            <h2>	Свідоцтва про реєстрації авторських прав на твір/рішення</h2>
            <ol>
                <li v-for="(item, index) in exportData.certificates" :key="index">
                    Свідоцтво про реєстрацію авторського права на твір №. Вид твору «Назва твору» / Прізвище Ініціали. (Країна, де отримане свідоцтво). - №; заяв. дата подачі заявки; опубл. дата публікації про видачу свідоцтва/рішення.

                </li>

            </ol>
            </template>
            <template v-if="exportData.methodicals[0]">
            <h2>	Методичні вказівки</h2>
            <ol>
                <li v-for="(item, index) in exportData.methodicals" :key="index">
                    Прізвище, ініціали. Назва. – місто видання : назва видавництва, рік видання. – кількість с.

                </li>

            </ol>
            </template>
            <template v-if="exportData.electronics[0]">
            <h2>	Електронні видання</h2>
            <ol>
                <li v-for="(item, index) in exportData.electronics" :key="index">
                    Прізвище, ініціали назва : вид публікації [електронний ресурс] / ініціали, прізвище. – місто видання : назва редакції, рік видання. – кількість с. – режим доступу:

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
